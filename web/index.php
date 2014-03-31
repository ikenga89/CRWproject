<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->register(new FormServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Twig text
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addExtension(new \Twig_Extensions_Extension_Text());

    return $twig;
}));

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('fr'),
));

// Url generator
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\ValidatorServiceProvider());

// Configs
$app['debug'] = true;

/*
$app->get('/', function() {
    return 'Hello!';
});
*/

// Controllers
$app->mount('', include '../controllers/crw.php');


$app->run();
