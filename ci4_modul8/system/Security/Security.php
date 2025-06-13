<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\Security;

use CodeIgniter\Cookie\Cookie;
use CodeIgniter\Exceptions\InvalidArgumentException;
use CodeIgniter\Exceptions\LogicException;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\Method;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\I18n\Time;
use CodeIgniter\Security\Exceptions\SecurityException;
use CodeIgniter\Session\Session;
use Config\Cookie as CookieConfig;
use Config\Security as SecurityConfig;
use ErrorException;

/**
 * Class Security
 *
 * Provides methods that help protect your site against
 * Cross-Site Request Forgery attacks.
 *
 * @see \CodeIgniter\Security\SecurityTest
 */
class Security implements SecurityInterface
{
    public const CSRF_PROTECTION_COOKIE  = 'cookie';
    public const CSRF_PROTECTION_SESSION = 'session';
    protected const CSRF_HASH_BYTES      = 16;

    /**
     * CSRF Protection Method
     *
     * Protection Method for Cross Site Request Forgery protection.
     *
     * @var string 'cookie' or 'session'
     *
     * @deprecated 4.4.0 Use $this->config->csrfProtection.
     */
    protected $csrfProtection = self::CSRF_PROTECTION_COOKIE;

    /**
     * CSRF Token Randomization
     *
     * @var bool
     *
     * @deprecated 4.4.0 Use $this->config->tokenRandomize.
     */
    protected $tokenRandomize = false;

    /**
     * CSRF Hash (without randomization)
     *
     * Random hash for Cross Site Request Forgery protection.
     *
     * @var string|null
     */
    protected $hash;

    /**
     * CSRF Token Name
     *
     * Token name for Cross Site Request Forgery protection.
     *
     * @var string
     *
     * @deprecated 4.4.0 Use $this->config->tokenName.
     */
    protected $tokenName = 'csrf_token_name';

    /**
     * CSRF Header Name
     *
     * Header name for Cross Site Request Forgery protection.
     *
     * @var string
     *
     * @deprecated 4.4.0 Use $this->config->headerName.
     */
    protected $headerName = 'X-CSRF-TOKEN';

    /**
     * The CSRF Cookie instance.
     *
     * @var Cookie
     */
    protected $cookie;

    /**
     * CSRF Cookie Name (with Prefix)
     *
     * Cookie name for Cross Site Request Forgery protection.
     *
     * @var string
     */
    protected $cookieName = 'csrf_cookie_name';

    /**
     * CSRF Expires
     *
     * Expiration time for Cross Site Request Forgery protection cookie.
     *
     * Defaults to two hours (in seconds).
     *
     * @var int
     *
     * @deprecated 4.4.0 Use $this->config->expires.
     */
    protected $expires = 7200;

    /**
     * CSRF Regenerate
     *
     * Regenerate CSRF Token on every request.
     *
     * @var bool
     *
     * @deprecated 4.4.0 Use $this->config->regenerate.
     */
    protected $regenerate = true;

    /**
     * CSRF Redirect
     *
     * Redirect to previous page with error on failure.
     *
     * @var bool
     *
     * @deprecated 4.4.0 Use $this->config->redirect.
     */
    protected $redirect = false;

    /**
     * CSRF SameSite
     *
     * Setting for CSRF SameSite cookie token.
     *
     * Allowed values are: None - Lax - Strict - ''.
     *
     * Defaults to `Lax` as recommended in this link:
     *
     * @see https://portswigger.net/web-security/csrf/samesite-cookies
     *
     * @var string
     *
     * @deprecated `Config\Cookie` $samesite property is used.
     */
    protected $samesite = Cookie::SAMESITE_LAX;

    private readonly IncomingRequest $request;

    /**
     * CSRF Cookie Name without Prefix
     */
    private ?string $rawCookieName = null;

    /**
     * Session instance.
     */
    private ?Session $session = null;

    /**
     * CSRF Hash in Request Cookie
     *
     * The cookie value is always CSRF hash (without randomization) even if
     * $tokenRandomize is true.
     */
    private ?string $hashInCookie = null;

    /**
     * Security Config
     */
    protected SecurityConfig $config;

    /**
     * Constructor.
     *
     * Stores our configuration and fires off the init() method to setup
     * initial state.
     */
    public function __construct(SecurityConfig $config)
    {
        $this->config = $config;

        $this->rawCookieName = $config->cookieName;

        if ($this->isCSRFCookie()) {
            $cookie = config(CookieConfig::class);

            $this->configureCookie($cookie);
        } else {
            // Session based CSRF protection
            $this->configureSession();
        }

        $this->request      = service('request');
        $this->hashInCookie = $this->request->getCookie($this->cookieName);

        $this->restoreHash();
        if ($this->hash === null) {
            $this->generateHash();
        }
    }

    private function isCSRFCookie(): bool
    {
        return $this->config->csrfProtection === self::CSRF_PROTECTION_COOKIE;
    }

    private function configureSession(): void
    {
        $this->session = service('session');
    }

    private function configureCookie(CookieConfig $cookie): void
    {
        $cookiePrefix     = $cookie->prefix;
        $this->cookieName = $cookiePrefix . $this->rawCookieName;
        Cookie::setDefaults($cookie);
    }

    /**
     * CSRF verification.
     *
     * @return $this
     *
     * @throws SecurityException
     */
    public function verify(RequestInterface $request)
    {
        // Protects POST, PUT, DELETE, PATCH
        $method           = $request->getMethod();
        $methodsToProtect = [Method::POST, Method::PUT, Method::DELETE, Method::PATCH];
        if (! in_array($method, $methodsToProtect, true)) {
            return $this;
        }

        $postedToken = $this->getPostedToken($request);

        try {
            $token = ($postedToken !== null && $this->config->tokenRandomize)
                ? $this->derandomize($postedToken) : $postedToken;
        } catch (InvalidArgumentException) {
            $token = null;
        }

        // Do the tokens match?
        if (! isset($token, $this->hash) || ! hash_equals($this->hash, $token)) {
            throw SecurityException::forDisallowedAction();
        }

        $this->removeTokenInRequest($request);

        if ($this->config->regenerate) {
            $this->generateHash();
        }

        log_message('info', 'CSRF token verified.');

        return $this;
    }

    /**
     * Remove token in POST or JSON request data
     */
    private function removeTokenInRequest(RequestInterface $request): void
    {
        assert($request instanceof Request);

        if (isset($_POST[$this->config->tokenName])) {
            // We kill this since we're done and we don't want to pollute the POST array.
            unset($_POST[$this->config->tokenName]);
            $request->setGlobal('post', $_POST);
        } else {
            $body = $request->getBody() ?? '';
            $json = json_decode($body);
            if ($json !== null && json_last_error() === JSON_ERROR_NONE) {
                // We kill this since we're done and we don't want to pollute the JSON data.
                unset($json->{$this->config->tokenName});
                $request->setBody(json_encode($json));
            } else {
                parse_str($body, $parsed);
                // We kill this since we're done and we don't want to pollute the BODY data.
                unset($parsed[$this->config->tokenName]);
                $request->setBody(http_build_query($parsed));
            }
        }
    }

    private function getPostedToken(RequestInterface $request): ?string
    {
        assert($request instanceof IncomingRequest);

        // Does the token exist in POST, HEADER or optionally php:://input - json data or PUT, DELETE, PATCH - raw data.

        if ($tokenValue = $request->getPost($this->config->tokenName)) {
            return is_string($tokenValue) ? $tokenValue : null;
        }

        if ($request->hasHeader($this->config->headerName)) {
            $tokenValue = $request->header($this->config->headerName)->getValue();

            return (is_string($tokenValue) && $tokenValue !== '') ? $tokenValue : null;
        }

        $body = (string) $request->getBody();

        if ($body !== '') {
            $json = json_decode($body);
            if ($json !== null && json_last_error() === JSON_ERROR_NONE) {
                $tokenValue = $json->{$this->config->tokenName} ?? null;

                return is_string($tokenValue) ? $tokenValue : null;
            }

            parse_str($body, $parsed);
            $tokenValue = $parsed[$this->config->tokenName] ?? null;

            return is_string($tokenValue) ? $tokenValue : null;
        }

        return null;
    }

    /**
     * Returns the CSRF Token.
     */
    public function getHash(): ?string
    {
        return $this->config->tokenRandomize ? $this->randomize($this->hash) : $this->hash;
    }

    /**
     * Randomize hash to avoid BREACH attacks.
     *
     * @params string $hash CSRF hash
     *
     * @return string CSRF token
     */
    protected function randomize(string $hash): string
    {
        $keyBinary  = random_bytes(static::CSRF_HASH_BYTES);
        $hashBinary = hex2bin($hash);

        if ($hashBinary === false) {
            throw new LogicException('$hash is invalid: ' . $hash);
        }

        return bin2hex(($hashBinary ^ $keyBinary) . $keyBinary);
    }

    /**
     * Derandomize the token.
     *
     * @params string $token CSRF token
     *
     * @return string CSRF hash
     *
     * @throws InvalidArgumentException "hex2bin(): Hexadecimal input string must have an even length"
     */
    protected function derandomize(string $token): string
    {
        $key   = substr($token, -static::CSRF_HASH_BYTES * 2);
        $value = substr($token, 0, static::CSRF_HASH_BYTES * 2);

        try {
            return bin2hex(hex2bin($value) ^ hex2bin($key));
        } catch (ErrorException $e) {
            // "hex2bin(): Hexadecimal input string must have an even length"
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Returns the CSRF Token Name.
     */
    public function getTokenName(): string
    {
        return $this->config->tokenName;
    }

    /**
     * Returns the CSRF Header Name.
     */
    public function getHeaderName(): string
    {
        return $this->config->headerName;
    }

    /**
     * Returns the CSRF Cookie Name.
     */
    public function getCookieName(): string
    {
        return $this->config->cookieName;
    }

    /**
     * Check if request should be redirect on failure.
     */
    public function shouldRedirect(): bool
    {
        return $this->config->redirect;
    }

    /**
     * Sanitize Filename
     *
     * Tries to sanitize filenames in order to prevent directory traversal attempts
     * and other security threats, which is particularly useful for files that
     * were supplied via user input.
     *
     * If it is acceptable for the user input to include relative paths,
     * e.g. file/in/some/approved/folder.txt, you can set the second optional
     * parameter, $relativePath to TRUE.
     *
     * @param string $str          Input file name
     * @param bool   $relativePath Whether to preserve paths
     */
    public function sanitizeFilename(string $str, bool $relativePath = false): string
    {
        // List of sanitize filename strings
        $bad = [
            '../',
            '<!--',
            '-->',
            '<',
            '>',
            "'",
            '"',
            '&',
            '$',
            '#',
            '{',
            '}',
            '[',
            ']',
            '=',
            ';',
            '?',
            '%20',
            '%22',
            '%3c',
            '%253c',
            '%3e',
            '%0e',
            '%28',
            '%29',
            '%2528',
            '%26',
            '%24',
            '%3f',
            '%3b',
            '%3d',
        ];

        if (! $relativePath) {
            $bad[] = './';
            $bad[] = '/';
        }

        $str = remove_invisible_characters($str, false);

        do {
            $old = $str;
            $str = str_replace($bad, '', $str);
        } while ($old !== $str);

        return stripslashes($str);
    }

    /**
     * Restore hash from Session or Cookie
     */
    private function restoreHash(): void
    {
        if ($this->isCSRFCookie()) {
            if ($this->isHashInCookie()) {
                $this->hash = $this->hashInCookie;
            }
        } elseif ($this->session->has($this->config->tokenName)) {
            // Session based CSRF protection
            $this->hash = $this->session->get($this->config->tokenName);
        }
    }

    /**
     * Generates (Regenerates) the CSRF Hash.
     */
    public function generateHash(): string
    {
        $this->hash = bin2hex(random_bytes(static::CSRF_HASH_BYTES));

        if ($this->isCSRFCookie()) {
            $this->saveHashInCookie();
        } else {
            // Session based CSRF protection
            $this->saveHashInSession();
        }

        return $this->hash;
    }

    private function isHashInCookie(): bool
    {
        if ($this->hashInCookie === null) {
            return false;
        }

        $length  = static::CSRF_HASH_BYTES * 2;
        $pattern = '#^[0-9a-f]{' . $length . '}$#iS';

        return preg_match($pattern, $this->hashInCookie) === 1;
    }

    private function saveHashInCookie(): void
    {
        $this->cookie = new Cookie(
            $this->rawCookieName,
            $this->hash,
            [
                'expires' => $this->config->expires === 0 ? 0 : Time::now()->getTimestamp() + $this->config->expires,
            ],
        );

        $response = service('response');
        $response->setCookie($this->cookie);
    }

    private function saveHashInSession(): void
    {
        $this->session->set($this->config->tokenName, $this->hash);
    }
}
