<?php

namespace Saloon\Http\Paginators;

use Saloon\Contracts\Response;

class TestPaginator extends Paginator
{
    /**
     * Retrieve the results for a given response
     *
     * @param \Saloon\Contracts\Response $response
     * @return array
     */
    public function getResults(Response $response): array
    {
        return $response->json('data') ?? [];
    }

    /**
     * Check if we have a next page
     *
     * @param \Saloon\Contracts\Response $response
     * @return bool
     */
    public function hasNextPage(Response $response): bool
    {
        return ! empty($response->json('next_page_url'));
    }
}
