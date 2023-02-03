<?php

use Saloon\Tests\Fixtures\Connectors\PerPagePaginatorConnector;
use Saloon\Tests\Fixtures\Requests\PerPageSuperheroRequest;

test('you can configure a per page paginator and it will iterate over every request but yield every item', function () {
    $connector = new PerPagePaginatorConnector();
    $request = new PerPageSuperheroRequest();

    $superheroes = [];

    foreach ($connector->paginate($request) as $response) {
        $superheroes = array_merge($superheroes, $response->json('data'));
    }

    dd(collect($superheroes)->pluck('superhero')->unique());
});
