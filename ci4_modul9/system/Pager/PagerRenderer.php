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

namespace CodeIgniter\Pager;

use CodeIgniter\HTTP\URI;

/**
 * Class PagerRenderer
 *
 * This class is passed to the view that describes the pagination,
 * and is used to get the link information and provide utility
 * methods needed to work with pagination.
 *
 * @see \CodeIgniter\Pager\PagerRendererTest
 */
class PagerRenderer
{
    /**
     * First page number in the set of links to be displayed.
     *
     * @var int
     */
    protected $first;

    /**
     * Last page number in the set of links to be displayed.
     *
     * @var int
     */
    protected $last;

    /**
     * Current page number.
     *
     * @var int
     */
    protected $current;

    /**
     * Total number of items.
     *
     * @var int
     */
    protected $total;

    /**
     * Total number of pages.
     *
     * @var int
     */
    protected $pageCount;

    /**
     * URI base for pagination links
     *
     * @var URI
     */
    protected $uri;

    /**
     * Segment number used for pagination.
     *
     * @var int
     */
    protected $segment;

    /**
     * Name of $_GET parameter
     *
     * @var string
     */
    protected $pageSelector;

    /**
     * Returns the number of results per page that should be shown.
     */
    protected ?int $perPage;

    /**
     * The number of items the page starts with.
     */
    protected ?int $perPageStart = null;

    /**
     * The number of items the page ends with.
     */
    protected ?int $perPageEnd = null;

    /**
     * Constructor.
     */
    public function __construct(array $details)
    {
        // `first` and `last` will be updated by `setSurroundCount()`.
        // You must call `setSurroundCount()` after instantiation.
        $this->first = 1;
        $this->last  = $details['pageCount'];

        $this->current      = $details['currentPage'];
        $this->total        = $details['total'];
        $this->uri          = $details['uri'];
        $this->pageCount    = $details['pageCount'];
        $this->segment      = $details['segment'] ?? 0;
        $this->pageSelector = $details['pageSelector'] ?? 'page';
        $this->perPage      = $details['perPage'] ?? null;
        $this->updatePerPages();
    }

    /**
     * Sets the total number of links that should appear on either
     * side of the current page. Adjusts the first and last counts
     * to reflect it.
     *
     * @return PagerRenderer
     */
    public function setSurroundCount(?int $count = null)
    {
        $this->updatePages($count);

        return $this;
    }

    /**
     * Checks to see if there is a "previous" page before our "first" page.
     */
    public function hasPrevious(): bool
    {
        return $this->first > 1;
    }

    /**
     * Returns a URL to the "previous" page. The previous page is NOT the
     * page before the current page, but is the page just before the
     * "first" page.
     *
     * @return string|null
     */
    public function getPrevious()
    {
        if (! $this->hasPrevious()) {
            return null;
        }

        $uri = clone $this->uri;

        if ($this->segment === 0) {
            $uri->addQuery($this->pageSelector, $this->first - 1);
        } else {
            $uri->setSegment($this->segment, $this->first - 1);
        }

        return URI::createURIString(
            $uri->getScheme(),
            $uri->getAuthority(),
            $uri->getPath(),
            $uri->getQuery(),
            $uri->getFragment(),
        );
    }

    /**
     * Checks to see if there is a "next" page after our "last" page.
     */
    public function hasNext(): bool
    {
        return $this->pageCount > $this->last;
    }

    /**
     * Returns a URL to the "next" page. The next page is NOT, the
     * page after the current page, but is the page that follows the
     * "last" page.
     *
     * @return string|null
     */
    public function getNext()
    {
        if (! $this->hasNext()) {
            return null;
        }

        $uri = clone $this->uri;

        if ($this->segment === 0) {
            $uri->addQuery($this->pageSelector, $this->last + 1);
        } else {
            $uri->setSegment($this->segment, $this->last + 1);
        }

        return URI::createURIString(
            $uri->getScheme(),
            $uri->getAuthority(),
            $uri->getPath(),
            $uri->getQuery(),
            $uri->getFragment(),
        );
    }

    /**
     * Returns the URI of the first page.
     */
    public function getFirst(): string
    {
        $uri = clone $this->uri;

        if ($this->segment === 0) {
            $uri->addQuery($this->pageSelector, 1);
        } else {
            $uri->setSegment($this->segment, 1);
        }

        return URI::createURIString(
            $uri->getScheme(),
            $uri->getAuthority(),
            $uri->getPath(),
            $uri->getQuery(),
            $uri->getFragment(),
        );
    }

    /**
     * Returns the URI of the last page.
     */
    public function getLast(): string
    {
        $uri = clone $this->uri;

        if ($this->segment === 0) {
            $uri->addQuery($this->pageSelector, $this->pageCount);
        } else {
            $uri->setSegment($this->segment, $this->pageCount);
        }

        return URI::createURIString(
            $uri->getScheme(),
            $uri->getAuthority(),
            $uri->getPath(),
            $uri->getQuery(),
            $uri->getFragment(),
        );
    }

    /**
     * Returns the URI of the current page.
     */
    public function getCurrent(): string
    {
        $uri = clone $this->uri;

        if ($this->segment === 0) {
            $uri->addQuery($this->pageSelector, $this->current);
        } else {
            $uri->setSegment($this->segment, $this->current);
        }

        return URI::createURIString(
            $uri->getScheme(),
            $uri->getAuthority(),
            $uri->getPath(),
            $uri->getQuery(),
            $uri->getFragment(),
        );
    }

    /**
     * Returns an array of links that should be displayed. Each link
     * is represented by another array containing of the URI the link
     * should go to, the title (number) of the link, and a boolean
     * value representing whether this link is active or not.
     *
     * @return list<array{uri:string, title:int, active:bool}>
     */
    public function links(): array
    {
        $links = [];

        $uri = clone $this->uri;

        for ($i = $this->first; $i <= $this->last; $i++) {
            $uri     = $this->segment === 0 ? $uri->addQuery($this->pageSelector, $i) : $uri->setSegment($this->segment, $i);
            $links[] = [
                'uri' => URI::createURIString(
                    $uri->getScheme(),
                    $uri->getAuthority(),
                    $uri->getPath(),
                    $uri->getQuery(),
                    $uri->getFragment(),
                ),
                'title'  => $i,
                'active' => ($i === $this->current),
            ];
        }

        return $links;
    }

    /**
     * Updates the first and last pages based on $surroundCount,
     * which is the number of links surrounding the active page
     * to show.
     *
     * @param int|null $count The new "surroundCount"
     *
     * @return void
     */
    protected function updatePages(?int $count = null)
    {
        if ($count === null) {
            return;
        }

        $this->first = $this->current - $count > 0 ? $this->current - $count : 1;
        $this->last  = $this->current + $count <= $this->pageCount ? $this->current + $count : (int) $this->pageCount;
    }

    /**
     * Updates the start and end items per pages, which is
     * the number of items displayed on the active page.
     */
    protected function updatePerPages(): void
    {
        if ($this->total === null || $this->perPage === null) {
            return;
        }

        // When the page is the last, perform a different calculation.
        if ($this->last === $this->current) {
            $this->perPageStart = $this->perPage * ($this->current - 1) + 1;
            $this->perPageEnd   = $this->total;

            return;
        }

        $this->perPageStart = $this->current === 1 ? 1 : ($this->perPage * $this->current) - $this->perPage + 1;
        $this->perPageEnd   = $this->perPage * $this->current;
    }

    /**
     * Checks to see if there is a "previous" page before our "first" page.
     */
    public function hasPreviousPage(): bool
    {
        return $this->current > 1;
    }

    /**
     * Returns a URL to the "previous" page.
     *
     * You MUST call hasPreviousPage() first, or this value may be invalid.
     *
     * @return string|null
     */
    public function getPreviousPage()
    {
        if (! $this->hasPreviousPage()) {
            return null;
        }

        $uri = clone $this->uri;

        if ($this->segment === 0) {
            $uri->addQuery($this->pageSelector, $this->current - 1);
        } else {
            $uri->setSegment($this->segment, $this->current - 1);
        }

        return URI::createURIString(
            $uri->getScheme(),
            $uri->getAuthority(),
            $uri->getPath(),
            $uri->getQuery(),
            $uri->getFragment(),
        );
    }

    /**
     * Checks to see if there is a "next" page after our "last" page.
     */
    public function hasNextPage(): bool
    {
        return $this->current < $this->last;
    }

    /**
     * Returns a URL to the "next" page.
     *
     * You MUST call hasNextPage() first, or this value may be invalid.
     *
     * @return string|null
     */
    public function getNextPage()
    {
        if (! $this->hasNextPage()) {
            return null;
        }

        $uri = clone $this->uri;

        if ($this->segment === 0) {
            $uri->addQuery($this->pageSelector, $this->current + 1);
        } else {
            $uri->setSegment($this->segment, $this->current + 1);
        }

        return URI::createURIString(
            $uri->getScheme(),
            $uri->getAuthority(),
            $uri->getPath(),
            $uri->getQuery(),
            $uri->getFragment(),
        );
    }

    /**
     * Returns the page number of the first page in the set of links to be displayed.
     */
    public function getFirstPageNumber(): int
    {
        return $this->first;
    }

    /**
     * Returns the page number of the current page.
     */
    public function getCurrentPageNumber(): int
    {
        return $this->current;
    }

    /**
     * Returns the page number of the last page in the set of links to be displayed.
     */
    public function getLastPageNumber(): int
    {
        return $this->last;
    }

    /**
     * Returns total number of pages.
     */
    public function getPageCount(): int
    {
        return $this->pageCount;
    }

    /**
     * Returns the previous page number.
     */
    public function getPreviousPageNumber(): ?int
    {
        return ($this->current === 1) ? null : $this->current - 1;
    }

    /**
     * Returns the next page number.
     */
    public function getNextPageNumber(): ?int
    {
        return ($this->current === $this->pageCount) ? null : $this->current + 1;
    }

    /**
     * Returns the total items of the page.
     */
    public function getTotal(): ?int
    {
        return $this->total;
    }

    /**
     * Returns the number of items to be displayed on the page.
     */
    public function getPerPage(): ?int
    {
        return $this->perPage;
    }

    /**
     * Returns the number of items the page starts with.
     */
    public function getPerPageStart(): ?int
    {
        return $this->perPageStart;
    }

    /**
     * Returns the number of items the page ends with.
     */
    public function getPerPageEnd(): ?int
    {
        return $this->perPageEnd;
    }
}
