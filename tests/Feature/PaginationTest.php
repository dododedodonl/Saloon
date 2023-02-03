<?php

use Illuminate\Support\LazyCollection;
use Saloon\Contracts\Response;
use Saloon\Tests\Fixtures\Connectors\PerPagePaginatorConnector;
use Saloon\Tests\Fixtures\Requests\PerPageSuperheroRequest;

test('you can configure a per page paginator and it will iterate over every request but yield every item', function () {
    $connector = new PerPagePaginatorConnector();
    $request = new PerPageSuperheroRequest();

    $superheroes = [];

    $request->middleware()->onRequest(function () {
        ray()->count();
    });

    $iterator = $connector->paginate($request);

    $lazy = LazyCollection::make(fn () => yield from $iterator)
        ->map(function (Response $response) {
            return $response->json('data');
        });

    dd($lazy->all());

    // Todo: Learn how to get the actual response data, similar to yielding from a generator
    // Todo: Learn how to serialize an interator half way through

    foreach ($iterator as $index => $response) {
        $superheroes = array_merge($superheroes, $response->json('data'));
    }

    dd(collect($superheroes)->pluck('superhero'));
});

// Todo: Teach people in the docs how to use a LazyCollection with Saloon's paginator
