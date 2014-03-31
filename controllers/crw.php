<?php

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
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

        $all_tweet = array();
        foreach ($tweets as $tweet) {
			$text_tweet = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" TARGET=_BLANK >$1</a>', $tweet->text);
			$all_tweet[] = array('text' => $text_tweet, 'created_at' => $tweet->created_at);
        }

        // Création formulaire
		$form = $app['form.factory']->createBuilder('form')
		->add('nom', 'text', array(
			'label' => 'Nom : ',
			'attr' => array(
				'class' => 'text',
			),
			'constraints' => array(
			new Assert\NotBlank(), 
			new Assert\Length(array('min' => 2))
			)
		))
		->add('email', 'email', array(
			'label' => 'Mail : ',
			'attr' => array(
				'class' => 'text',
			),
			'constraints' => array(
			new Assert\NotBlank(), 
			new Assert\Length(array('min' => 2))
			)
		))
		->add('section', 'text', array(
			'label' => 'Section : ',
			'attr' => array(
				'class' => 'text',
			),
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
		->add('send', 'submit', array(
			'label' => 'Envoyer',
			'attr' => array(
				'class' => 'button button-style1',
			)
		))
		->getForm();

		$form->handleRequest($request);

		if($form->isValid()){

			$data = $form->getData();
			return $app->redirect($app['url_generator']->generate('thanks'));
		}
		//FIN FORMULAIRE

		return $app['twig']->render('home.twig', array(

			'posts' => $facebook_posts['data'],
			'facebook_picture' => $facebook_picture['picture']['data'], 
			'tweets' => $all_tweet,
			'form' => $form->createView(),

		));

	})->bind('home');

	$blog->get('/thanks', function () use ($app){
		return $app['twig']->render('thanks.twig', array());
	})
	->bind('thanks');



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