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

		return $app['twig']->render('home.twig', array(
			'posts' => $posts['data'],	
		));
		

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

	})->bind('home');
	


	return $blog;