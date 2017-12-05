<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use App\CustomApp;


$app = new CustomApp();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
});


// SERVICES/MODELS pour chaque DAO

$app['users.dao'] = function($app){
    return new \DAO\UserDao($app['pdo']);
};

$app['admins.dao'] = function($app){
    return new \DAO\AdminDao($app['pdo']);
};

$app['categories.dao'] = function($app){
    return new \DAO\CategoryDao($app['pdo']);
};

$app['loanings.dao'] = function($app){
    return new \DAO\LoaningDao($app['pdo']);
};

$app['games.dao'] = function($app){
    return new \DAO\GameDao($app['pdo']);
};

// Création de l'objet PDO
$app['pdo'] = function($app){
    $options = $app['pdo.options'];
    return new \PDO("{$options['dbms']}://host={$options['host']};dbname={$options['dbname']};charset={$options['charset']}", 
                $options['username'], 
                $options['password'], 
                array(
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ));
};

$app->register(new Silex\Provider\SessionServiceProvider()); // pour fournir la session

// création de l'espace protégé
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => [
        // On met firewall Admin AVANT ce de Front (questions de priorité)
        'admin' => array(// firewall pour backoffice
            'pattern' => '^/admin/',
            'http' => true,
            'anonymous' => false,
            'form' => array(
                'login_path' => '/loginadmin', 
                'check_path' => '/admin/login_check',
                'always_use_default_target_path' => true, // pour la rédirection apres login
                'default_target_path' => '/admin/dashboard' // pour la rédirection apres login
            ),
            'logout' => array(
                'logout_path' => '/admin/logoutadmin', 
                'invalidate_session' => true
            ),
            'users' => function () use ($app) {
                return $app['admins.dao'];
            }
        ),

        'front' => array( // firewall pour le frontoffice
            'pattern' => '^/', // correspond a toutes les routes (par contre pour backoffice on ajoute \admin ou \back
            'http' => true,
            'anonymous' => true, //on peut utiliser notre site sans se connecter (par contre il faut enlever cette ligne pour backoffice - donc pour les admin)
            'form' => array('login_path' => '/login', 'check_path' => '/login_check'), // configuration de formulaire de connexion
            'logout' => array('logout_path' => '/logout', 'invalidate_session' => true),
            'users' => function () use ($app){
                return $app['users.dao'];
            }
            
        ),
    ]
));
        
// pour traduire le site (ici les erreurs) :
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('fr'),
    'translator.domains' => [
        'messages' => [
            'fr' => [
                'The credentials were changed from another session.' => 'Les identifiants ont été changés dans une autre session.',
                'The presented password cannot be empty.' => 'Le mot de passe ne peut pas être vide.',
                'The presented password is invalid.' => 'Le mot de passe entré est invalide.',
                'Bad credentials.' => 'Les identifiants sont incorrects'
            ]
        ]
    ]
));

//formulaire 
$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());


return $app;
