<?php

require 'vendor/autoload.php';

require_once __DIR__ . '/Constants.php';
require_once BASE_DIR . '/Errors.php';
require_once BASE_DIR . '/FindCircles.php';

$app = new \Slim\Slim();

$app->get('/', function () {
    include 'home.php';
});

$app->get('/(env/:env/)find-circles/?(/in/:whatImage/?)' .
            '(/min-radius/:minRadius/?)' .
            '(/max-radius/:maxRadius/?)' .
            '(/sensitivity/:sensitivity/?)', function (
        $env = null,
        $whatImage = null,
        $minRadius = null,
        $maxRadius = null,
        $sensitivity = null) {

    try {
        $findCircles = new FindCircles(
            $env, $whatImage, $minRadius, $maxRadius, $sensitivity);
        print $findCircles->execute();
    } catch (Exception $e) {
        printError($e->getMessage());
    }
});

$app->post('/find-circles/?', function () use ($app) {

    $vars = $app->request->post();

    $env = isset($vars['env']) ? $vars['env'] : null;
    $whatImage = isset($vars['whatImage']) ? $vars['whatImage'] : null;
    $minRadius = isset($vars['minRadius']) ? $vars['minRadius'] : null;
    $maxRadius = isset($vars['maxRadius']) ? $vars['maxRadius'] : null;
    $sensitivity = isset($vars['sensitivity']) ? $vars['sensitivity'] : null;
    $image64 = isset($vars['image64']) ? $vars['image64'] : null;

    try {
        $findCircles = new FindCircles(
            $env, $whatImage, $minRadius, $maxRadius, $sensitivity, $image64);
        print $findCircles->execute();
    } catch (Exception $e) {
        printError($e->getMessage());
    }
});

$app->get('/inputs/default/?', function () use ($app) {
	showImage($app, DEFAULT_IMAGE_PATH);
});

$app->get('/inputs/last/?', function () use ($app) {
	showImage($app, LAST_IMAGE_PATH);
});

$app->get('/results/default/?', function () use ($app) {
    showImage($app, DEFAULT_IMAGE_RESULT_PATH);
});

$app->get('/results/last/?', function () use ($app) {
    showImage($app, LAST_IMAGE_RESULT_PATH);
});

$app->run();

function showImage($app, $path) {
	$image = file_get_contents($path);
	$app->response->header('Content-Type', 'content-type: image/jpeg');
	print $image;
}

function printError($errorMsg) {
    $res["status"] = "error";
    $res["message"] = $errorMsg;
    print json_encode($res);
    exit;
}