<?php

namespace Saloon\Http\Paginators;

use Iterator;
use Saloon\Contracts\Connector;
use Saloon\Contracts\Request;
use Saloon\Contracts\Response;

class ResultsPerPagePaginator implements Iterator
{
    /**
     * Connector
     *
     * @var \Saloon\Contracts\Connector
     */
    protected Connector $connector;

    /**
     * Request
     *
     * @var \Saloon\Contracts\Request
     */
    protected Request $request;

    /**
     * Current Response
     *
     * @var \Saloon\Contracts\Response|null
     */
    protected ?Response $currentResponse = null;

    /**
     * Current Page
     *
     * @var int
     */
    protected int $currentPage = 1;

    /**
     * Total results processed for keys
     *
     * @var int
     */
    protected int $totalResultsProcessed = 0;

    /**
     * Current Results Count
     *
     * @var int
     */
    protected int $currentResultsCount = 0;

    /**
     * Results cursor position
     *
     * @var int
     */
    protected int $resultsCursor = 0;

    /**
     * Constructor
     *
     * @param \Saloon\Contracts\Connector $connector
     * @param \Saloon\Contracts\Request $request
     */
    public function __construct(Connector $connector, Request $request)
    {
        $this->connector = $connector;
        $this->request = $request;
    }

    /**
     * Return the current element
     *
     * @link https://php.net/manual/en/iterator.current.php
     * @return TValue Can return any type.
     */
    public function current(): mixed
    {
        if ($this->iteratingOverResults()) {
            ray('through results');

            return $this->currentResponse->json('data')[$this->resultsCursor];
        }

        $this->currentPage++;

        $this->getNextRequest();

        return $this->currentResponse->json('data')[$this->resultsCursor];
    }

    /**
     * Get the next request
     *
     * @return \Saloon\Contracts\Response
     */
    protected function getNextRequest(): Response
    {
        $pendingRequest = $this->connector->createPendingRequest($this->request);

        $pendingRequest->query()->add('page', $this->currentPage);

        $this->currentResponse = $pendingRequest->send();

        $this->resultsCursor = 0;
        $this->currentResultsCount = count($this->currentResponse->json('data'));

        return $this->currentResponse;
    }

    /**
     * Move forward to next element
     *
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next(): void
    {
        if ($this->iteratingOverResults()) {
            $this->resultsCursor++;
            $this->totalResultsProcessed++;
        } else {
            $this->currentPage++;
        }
    }

    /**
     * Return the key of the current element
     *
     * @link https://php.net/manual/en/iterator.key.php
     * @return TKey|null TKey on success, or null on failure.
     */
    public function key(): int
    {
        // Key for iterating over results needs to constantly increase so the index is always increasing

        return $this->totalResultsProcessed;
    }

    /**
     * Checks if current position is valid
     *
     * @link https://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid(): bool
    {
        if (! isset($this->currentResponse) || $this->iteratingOverResults()) {
            return true;
        }

        return is_string($this->currentResponse->json('next_page_url'));
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind(): void
    {
        $this->currentResponse = null;
        $this->currentPage = 1;
        $this->resultsCursor = 0;
        $this->totalResultsProcessed = 0;
        $this->currentResultsCount = 0;
    }

    /**
     * Check if we are iterating over the results
     *
     * @return bool
     */
    protected function iteratingOverResults(): bool
    {
        if (! isset($this->currentResponse)) {
            return false;
        }

        return isset($this->currentResponse) && $this->resultsCursor !== $this->currentResultsCount;
    }
}
