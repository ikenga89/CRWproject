<?php

	use Symfony\Component\HttpFoundation\Request;


	$blog = $app['controllers_factory'];

	
	$blog->get('/', function() use ($app){

		$posts = $app['facebook']->api('/110864882309437/posts');

		foreach ($posts['data'] as $key => $value) {
			echo $value['message'].'<br /><br />';
		}
		die();
		echo '<pre>';
		var_dump($posts);
		

		return $app['twig']->render('home.twig', array(
			'posts'));
	})->bind('home');
	


	return $blog;