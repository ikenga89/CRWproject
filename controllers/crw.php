<?php

	use Symfony\Component\HttpFoundation\Request;


	$blog = $app['controllers_factory'];

	
	$blog->get('/', function() use ($app){

		$facebook_posts = $app['facebook']->api('/110864882309437/posts');

		$twitter_client = new \Guzzle\Http\Client('https://api.twitter.com/{version}', array(
                'version' => '1.1'
            ));
            $twitter_client->addSubscriber(new \Guzzle\Plugin\Oauth\OauthPlugin(array(
                'consumer_key'  => 'ARfJFo5NwBMBvQ7MCJRBkQ',
                'consumer_secret' => 'ixMxmsuHV2LFLNE9QOXex3DcxGBkzX5ucLuKh4K3xWg',
                'token'       => '2420427098-eK2Fdjo4dwkf3Rc0Huptgv4QeFCjNBBQKbLwBMa',
                'token_secret'  => 'TXfS7cZW9THyI65vurPnSOp5rEmpnX9eoRi55ryS9cdju'
            )));
 
            $request = $twitter_client->get('statuses/user_timeline.json');
            $request->getQuery()->set('count', 5);
            $request->getQuery()->set('screen_name', 'SciencesULyon');
            $response = $request->send();
 
            $tweets = json_decode($response->getBody());
            
        // Création formulaire
        //return $app['twig']->render('home.twig');
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
		->add('compose', 'text', array(
			'label' => 'Composé : ',
			'constraints' => array(
			new Assert\NotBlank(), 
			new Assert\Length(array('min' => 2))
			)
		))
		->getForm();    

		return $app['twig']->render('home.twig', array(
			'posts' => $facebook_posts, 'tweets' => $tweets	
		));


	})->bind('home');

	return $blog;