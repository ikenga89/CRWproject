<?php

	use Symfony\Component\HttpFoundation\Request;


	$blog = $app['controllers_factory'];

	
	$blog->get('/', function() use ($app){
		return $app['twig']->render('home.twig');
	})->bind('home');
	


	return $blog;