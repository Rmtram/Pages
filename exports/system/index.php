<?php

require __DIR__ . '/../vendor/autoload.php';

use Rmtram\Pages\System\Controllers\PagesController;
use Rmtram\SimpleTextDb\Connector;

$app = new \Silex\Application();

$app['debug'] = true;

$app['connector'] = function() {
    return new Connector(__DIR__ . '/database/');
};

$app->register(new Silex\Provider\UrlGeneratorServiceProvider);
$app->register(new Silex\Provider\TwigServiceProvider, [
    'twig.path' => __DIR__ . '/views',
]);

$app->get('/', function() use($app) {
    /** @var Connector $connector */
    $connector = $app['connector'];
    $menus = $connector->connection('menus')->all();
    $pages = $connector->connection('page')->all();
    return $app['twig']->render('contents/dashboard.twig', compact('pages', 'menus'));
});

$app->mount('pages', new PagesController());


$app->run();