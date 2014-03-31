<?php

	use Symfony\Component\HttpFoundation\Request;

	use Silex\Provider\FormServiceProvider;

	use Symfony\Component\Validator\Constraints as Assert;


	$blog = $app['controllers_factory'];

	
	$blog->match('/', function(Request $request) use ($app){

		$facebook_posts = $app['facebook']->api('/110864882309437/posts');
		$facebook_picture = $app['facebook']->api('/110864882309437?fields=picture');

		$twitter_client = new \Guzzle\Http\Client('https://api.twitter.com/{version}', array(
                'version' => '1.1'
        ));


		// twitter

        $twitter_client->addSubscriber(new \Guzzle\Plugin\Oauth\OauthPlugin(array(
            'consumer_key'  => 'ARfJFo5NwBMBvQ7MCJRBkQ',
            'consumer_secret' => 'ixMxmsuHV2LFLNE9QOXex3DcxGBkzX5ucLuKh4K3xWg',
            'token'       => '2420427098-eK2Fdjo4dwkf3Rc0Huptgv4QeFCjNBBQKbLwBMa',
            'token_secret'  => 'TXfS7cZW9THyI65vurPnSOp5rEmpnX9eoRi55ryS9cdju'
        )));
 
        $result = $twitter_client->get('statuses/user_timeline.json');
        $result->getQuery()->set('count', 5);
        $result->getQuery()->set('screen_name', 'SciencesULyon');
        $response = $result->send();

        $tweets = json_decode($response->getBody());


            
        // Création formulaire
		$form = $app['form.factory']->createBuilder('form')
		->add('nom', 'text', array(
			'label' => 'Nom : ',
			'constraints' => array(
			new Assert\NotBlank(), 
			new Assert\Length(array('min' => 2))
			)
		))
		->add('email', 'email', array(
			'label' => 'Mail : ',
			'constraints' => array(
			new Assert\NotBlank(), 
			new Assert\Length(array('min' => 2))
			)
		))
		->add('section', 'text', array(
			'label' => 'Section : ',
			'constraints' => array(
			new Assert\NotBlank(), 
			new Assert\Length(array('min' => 2))
			)
		))
		->add('compose', 'textarea', array(
			'label' => 'Message : ',
			'constraints' => array(
			new Assert\NotBlank(), 
			new Assert\Length(array('min' => 2))
			)
		))
		->getForm();  

		$form->handleRequest($request);

		if($form->isValid()){
			$data = $form->getData();
			// redirect somewhere
	        return $app->redirect($app['url_generator']->generate('thanks'));
		}
		//FIN FORMULAIRE

		/*
		echo '<pre>';
		var_dump($facebook_picture);
		die();
		*/
		


		return $app['twig']->render('home.twig', array(

			'posts' => $facebook_posts['data'],
			'facebook_picture' => $facebook_picture['picture']['data'], 
			'tweets' => $tweets,
			'form' => $form->createView(), 	

		));


	})->bind('home');

	$blog->get('/thanks', function () use ($app){
		return $app['twig']->render('thanks.twig', array());
	})
	->bind('thanks');

	return $blog;