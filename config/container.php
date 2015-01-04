<?php

$container = new Pimple\Container();

$container['logger'] = function ($container) {
    return new Monolog\Logger(
        'logger',
        [new Monolog\Handler\StreamHandler(realpath(__DIR__) . '/log.txt')]
    );
};

$container['searchClient'] = function ($container) {
    $params['logging']   = true;
    $params['logObject'] = $container['logger'];
    $client = new Elasticsearch\Client($params);
    /*$client->indices()->create([
        'index' => 'recipes'
    ]);*/
    return $client;
};

$container['httpClient'] = function ($container) {
    return new Guzzle\Http\Client('http://gotovim-doma.ru/');
};

return $container;
