<?php

declare(strict_types=1);

namespace Saloon\Tests\Fixtures\Connectors;

use Iterator;
use Saloon\Contracts\Request;
use Saloon\Http\Connector;
use Saloon\Http\Paginators\PerPagePaginator;
use Saloon\Http\Paginators\ResultsPerPagePaginator;
use Saloon\Http\Paginators\TestPaginator;
use Saloon\Traits\Plugins\AcceptsJson;

class PerPagePaginatorConnector extends Connector
{
    use AcceptsJson;

    public bool $unique = false;

    /**
     * Define the base url of the api.
     *
     * @return string
     */
    public function resolveBaseUrl(): string
    {
        return apiUrl();
    }

    /**
     * Define the base headers that will be applied in every request.
     *
     * @return string[]
     */
    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    public function paginate(Request $request): Iterator
    {
        return new TestPaginator($this, $request);
    }
}
