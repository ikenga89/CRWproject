<?php

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;	

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



	// envoie de mail bloqué par le pare feu impossible de faire le test
/*	$blog->get('/feedback', function () use ($app) {
    $request = $app['request'];

   $message = \Swift_Message::newInstance()
        ->setSubject('Inscription U-NETWORK')
        ->setFrom(array('unetwork89@gmail.com'))
        ->setTo(array('yang_eric@hotmail.fr'))
        ->setBody('toto');

    $app['mailer']->send($message);

    return new Response('Thank you for your feedback!', 201);
});
*/	


	return $blog;