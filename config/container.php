<?php

$container = new Pimple\Container();

$container['logger'] = function ($container) {
      return new Monolog\Logger(
          'logger',
          [new Monolog\Handler\StreamHandler(realpath(__DIR__) . '/log.txt')]
      );
};

$container['httpClient'] = function ($container) {
    return new Guzzle\Http\Client();
};

return $container;
