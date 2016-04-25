<?php

require __DIR__ . '/../vendor/autoload.php';

use Rmtram\SimpleTextDb\Connector;
use Rmtram\SimpleTextDb\Driver\ListDriver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

$app->post('pages/demo', function(Request $request) use($app) {
    $description = $request->get('description');
    $text = null;
    if (!empty($description)) {
        $text = (new Parsedown)->text($description);
    }
    return new Response($text);
});

$app->get('pages/index', function() use($app) {
});

$app->get('pages/add', function() use($app) {
    return $app['twig']->render('contents/pages/add.twig');
});

$app->post('pages/add', function() use($app) {

});

$app->get('/navigation/add', function() use($app) {
    /** @var Connector $connector */
    $connector = $app['connector'];
    /** @var ListDriver $navigation */
    $navigation = $connector->connection('navigation');
    $items = $navigation->all();
});

$app->post('/navigation/add', function() use($app) {
    /** @var Connector $connector */
    $connector = $app['connector'];
    /** @var ListDriver $navigation */
    $navigation = $connector->connection('navigation');
    $items = $navigation->add([]);
});

$app->get('/navigation/edit/{id}', function() use($app) {

});

$app->post('/navigation/edit/{id}', function() use($app) {

});

$app->get('/navigation/delete/{id}', function() use($app) {

});

$app->run();