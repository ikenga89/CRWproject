<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Provider\FormServiceProvider;
use Silex\Provider\FacebookServiceProvider;

$app = new Silex\Application();

$app->register(new FacebookServiceProvider(), array(
    'facebook.config' => array(
        'appId'      => '414516295351453',
        'secret'     => 'd7e480e45243e668ee39e6c868af52db',
        'fileUpload' => false, // optional
    ),
    'facebook.permissions' => array('email'),
));




$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

// Twig text

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addExtension(new \Twig_Extensions_Extension_Text());
    return $twig;
}));

// Url generator
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new FormServiceProvider());

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('en'),
));

// Configs
$app['debug'] = true;

/*
$app->get('/', function() {
    return 'Hello!';
});*/


// Controllers
$app->mount('', include '../controllers/crw.php');


$app->run();
