<?php

	use Symfony\Component\HttpFoundation\Request;


	$blog = $app['controllers_factory'];

	$blog->get('/', function() use ($app){
		
	})->bind('home');


	return $blog;