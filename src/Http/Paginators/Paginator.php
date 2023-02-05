<?php

namespace Saloon\Http\Paginators;

use Saloon\Contracts\Connector;
use Saloon\Contracts\Paginator as PaginatorContract;
use Saloon\Contracts\Request;
use Saloon\Contracts\Response;

abstract class Paginator implements PaginatorContract
{
    /**
     * Connector
     *
     * @var \Saloon\Contracts\Connector
     */
    protected Connector $connector;

    /**
     * Original Request
     *
     * @var \Saloon\Contracts\Request
     */
    protected Request $originalRequest;

    /**
     * Should we iterate through results
     *
     * @var bool
     */
    protected bool $iterateThroughResults = false;

    /**
     * Last response
     *
     * @var \Saloon\Contracts\Response|null
     */
    protected ?Response $lastResponse = null;

    /**
     * The page
     *
     * @var int
     */
    protected int $page = 1;

    /**
     * The total results we have processed
     *
     * @var int
     */
    protected int $totalResults = 0;

    /**
     * Constructor
     *
     * @param \Saloon\Contracts\Connector $connector
     * @param \Saloon\Contracts\Request $request
     * @param bool $iterateThroughResults
     */
    public function __construct(Connector $connector, Request $request, bool $iterateThroughResults = false)
    {
        $this->connector = $connector;
        $this->originalRequest = clone $request;
        $this->iterateThroughResults = $iterateThroughResults;
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return Response
     */
    public function current(): Response
    {
        $pendingRequest = $this->connector->createPendingRequest($this->originalRequest);

        $pendingRequest->query()->add('page', $this->page);

        $response = $pendingRequest->send();

        // We will count the total results we've processed

        $this->totalResults += count($this->getResults($response));
        $this->lastResponse = $response;

        return $response;
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next(): void
    {
        // Todo: get working for limit/offset

        $this->page++;
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return int
     */
    public function key(): int
    {
        return $this->page;
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid(): bool
    {
        if (! isset($this->lastResponse)) {
            return true;
        }

        return $this->hasNextPage($this->lastResponse);
    }

    public function rewind(): void
    {
        $this->lastResponse = null;
        $this->page = 1;
        $this->totalResults = 0;
    }
}
