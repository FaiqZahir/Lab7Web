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

namespace CodeIgniter\Exceptions;

/**
 * Class FrameworkException
 *
 * A collection of exceptions thrown by the framework
 * that can only be determined at run time.
 */
class FrameworkException extends RuntimeException
{
    use DebugTraceableTrait;

    /**
     * @return static
     */
    public static function forEnabledZlibOutputCompression()
    {
        return new static(lang('Core.enabledZlibOutputCompression'));
    }

    /**
     * @return static
     */
    public static function forInvalidFile(string $path)
    {
        return new static(lang('Core.invalidFile', [$path]));
    }

    /**
     * @return static
     */
    public static function forInvalidDirectory(string $path)
    {
        return new static(lang('Core.invalidDirectory', [$path]));
    }

    /**
     * @return static
     */
    public static function forCopyError(string $path)
    {
        return new static(lang('Core.copyError', [$path]));
    }

    /**
     * @return static
     *
     * @deprecated 4.5.0 No longer used.
     */
    public static function forMissingExtension(string $extension)
    {
        if (str_contains($extension, 'intl')) {
            // @codeCoverageIgnoreStart
            $message = sprintf(
                'The framework needs the following extension(s) installed and loaded: %s.',
                $extension,
            );
            // @codeCoverageIgnoreEnd
        } else {
            $message = lang('Core.missingExtension', [$extension]);
        }

        return new static($message);
    }

    /**
     * @return static
     */
    public static function forNoHandlers(string $class)
    {
        return new static(lang('Core.noHandlers', [$class]));
    }

    /**
     * @return static
     */
    public static function forFabricatorCreateFailed(string $table, string $reason)
    {
        return new static(lang('Fabricator.createFailed', [$table, $reason]));
    }
}
