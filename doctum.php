<?php

use Doctum\Doctum;
use Symfony\Component\Finder\Finder;
use Doctum\RemoteRepository\GitHubRemoteRepository;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in('./src');

return new Doctum($iterator, [
    'title' => 'Saloon v1 API',
    'language' => 'en',
    'build_dir' => __DIR__ . '/build',
    'cache_dir' => __DIR__ . '/cache',
    'source_dir' => __DIR__,
    'remote_repository' => new GitHubRemoteRepository('sammyjo20/saloon', __DIR__),
    'default_opened_level' => 2, // optional, 2 is the default value
]);
