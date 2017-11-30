<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
});

$app['users.dao'] = function($app){
    return new \DAO\UserDao($app['pdo']);
};

$app['category.dao'] = function($app){
    return new \DAO\CategoryDao($app['pdo']);
};

$app['loaning.dao'] = function($app){
    return new \DAO\LoaningDao($app['pdo']);
};

$app['game.dao'] = function($app){
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

return $app;
