<?php

	use Symfony\Component\HttpFoundation\Request;


	$blog = $app['controllers_factory'];

	
	$blog->get('/', function() use ($app){

		$posts = $app['facebook']->api('/110864882309437/posts');

		/*
		foreach ($posts['data'] as $key => $value) {
			echo $value['message'].'<br /><br />';
		}
		*/
		

		
		/*
		echo '<pre>';
		var_dump($posts);
		die();
		*/
		
		/*
		return $app['twig']->render('home.twig', array(
			'posts' => $posts,	
		));
		*/

		return $app['twig']->render('home.twig');


	})->bind('home');
	


	return $blog;