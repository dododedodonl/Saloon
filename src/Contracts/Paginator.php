<?php

namespace Saloon\Contracts;

use Iterator;

// Todo: Add serializable to contract

interface Paginator extends Iterator
{
    /**
     * Retrieve the results for a given response
     *
     * @param \Saloon\Contracts\Response $response
     * @return array
     */
    public function getResults(Response $response): array;

    /**
     * Check if we have a next page
     *
     * @param \Saloon\Contracts\Response $response
     * @return bool
     */
    public function hasNextPage(Response $response): bool;
}
