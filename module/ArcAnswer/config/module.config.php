<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'ArcAnswer\Controller\User' => 'ArcAnswer\Controller\UserController',
			'ArcAnswer\Controller\Thread' => 'ArcAnswer\Controller\ThreadController',
			'ArcAnswer\Controller\Post' => 'ArcAnswer\Controller\PostController',
		),
	),

	'router' => array(
		'routes' => array(

			// Route to User controller
			// /user/index => infos de l'utilisateur courant
			// /user/create => création d'un nouvel utilisateur
			// /user/update => modification de l'utilisateur courant
			'user' => array(
				'type' => 'segment',
                'options' => array(
                    'route' => '/user/:action',
                    'constraints' => array(
                        'action' => '[a-zA-Z]+',
                    ),
                    'defaults' => array(
                        'controller' => 'ArcAnswer\Controller\User',
                        'action' => 'index',
                    ),
                ),
			),

			// Route to Thread controller
			// /thread/index => affichage de la page d'accueil
			// /thread/create => création d'un nouveau thread
            // /thread/addTag => ajout de tags à un thread existant
			'thread' => array(
				'type' => 'literal',
				'options' => array(
					'route' => '/thread',
					'defaults' => array(
						'controller' => 'ArcAnswer\Controller\Thread',
						'action' => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'index' => array(
						'type' => 'literal',
						'options' => array(
							'route' => '/index',
							'defaults' => array(
								'action' => 'index',
							),
						),
					),
					'create' => array(
						'type' => 'literal',
						'options' => array(
							'route' => '/create',
							'defaults' => array(
								'action' => 'create',
							),
						),
					),
                    'hideInfo' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/hideInfo',
                            'defaults' => array(
                                'action' => 'hideInformationBox',
                            ),
                        ),
                    ),
                    'showInfo' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/showInfo',
                            'defaults' => array(
                                'action' => 'showInformationBox',
                            ),
                        ),
                    ),
                    'addtag' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/addtag',
                            'defaults' => array(
                                'action' => 'addtag',
                            ),
                        ),
                    ),
				),
			),

			// Route to Post controller
			// /post/index/12 => affichage du thread 12
			// /post/create/12 => création d'un post pour le thread 12
			// /post/vote/134/1 => vote positif pour le post 134
			// /post/elect/134 => élection du post 134 comme solution de son thread
			'post' => array(
				'type' => 'literal',
				'options' => array(
					'route' => '/post',
					'defaults' => array(
						'controller' => 'ArcAnswer\Controller\Post',
						'action' => 'index',
					),
				),
				'may_terminate' => false,
				'child_routes' => array(
					'index' => array(
						'type' => 'segment',
						'options' => array(
							'route' => '/index/:threadid',
							'constraints' => array(
								'threadid' => '[0-9]+',
							),
							'defaults' => array(
								'action' => 'index',
							),
						),
					),
					'create' => array(
						'type' => 'segment',
						'options' => array(
							'route' => '/create/:threadid',
							'constraints' => array(
								'threadid' => '[0-9]+',
							),
							'defaults' => array(
								'action' => 'create',
							),
						),
					),
					'vote' => array(
						'type' => 'segment',
						'options' => array(
							'route' => '/vote/:postid/:val',
							'constraints' => array(
								'postid' => '[0-9]+',
								'val' => '-?1',
							),
							'defaults' => array(
								'action' => 'vote',
							),
						),
					),
					'elect' => array(
						'type' => 'segment',
						'options' => array(
							'route' => '/elect/:postid',
							'constraints' => array(
								'postid' => '[0-9]+',
							),
							'defaults' => array(
								'action' => 'elect',
							),
						),
					),
				),
			),
		),
	),

	'view_manager' => array(
		'template_map' => array(
			'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'layout/infoBox' => __DIR__ . '/../view/layout/infoBox.phtml',
        ),
		'template_path_stack' => array(
			'arcanswer' => __DIR__ . '/../view',
		),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
	),

	'doctrine' => array(
		'driver' => array(
			'ArcAnswer_driver' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(__DIR__ . '/../src/' . 'ArcAnswer/Entity')
			),
			'orm_default' => array(
				'drivers' => array(
					'ArcAnswer\Entity' =>'ArcAnswer_driver'
				)
			),
		),
		'authentication' => array(
			'orm_default' => array(
				'objectManager' => 'Doctrine\ORM\EntityManager',
				'identityClass' => 'ArcAnswer\Entity\User',
				'identityProperty' => 'login',
				'credentialProperty' => 'password',
				'credentialCallable' => 'ArcAnswer\Entity\User::testPassword',
			),
		),
	),
);