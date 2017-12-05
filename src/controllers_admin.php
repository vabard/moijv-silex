<?php

use Symfony\Component\HttpFoundation\Request;


// ESPACE LOGIN POUR LES ADMINS
$app->get('/loginadmin', function(Request $request) use ($app) {
    return $app['twig']->render('admin/login_admin.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})
->bind('loginadmin')
;


// ESPACE PROTEGE DISPO QUE POUR LES ADMINS
// 
// declaration de groupe des routes
$adminGroup = $app['controllers_factory'];

//route pour la page Home de Admin - Dashboard
$adminGroup->get('dashboard', function () use ($app){
    return $app['twig']->render('admin/dashboard.html.twig');
    
})
->bind('admin_dashboard')
;

// route pour liste des users :
$adminGroup->get('userlist', function () use ($app){
    
    $users = $app['users.dao']->findMany();
    
    // on transmet à notre template les données de $users (toujours un array!)
    return $app['twig']->render('admin/userlist.html.twig', [
        'users' => $users
    ]);
    
})
->bind('admin_userlist')
;

// route pour supprimer un user -> id dynamique
$adminGroup->get('/userdelete/{id}', function ($id) use ($app){
    
    // on selectionne notre user par son id
    $user = $app['users.dao']->find($id);
    
    // on supprime notre user
    $app['users.dao']->delete($user);
    
    // après la suppression on est redirigé sur la liste des user
    return $app->redirect($app['url_generator']->generate('/admin/userlist'));
})
->bind('admin_userdelete')
;


// route pour editer un user -> id dynamique
$adminGroup->match('/useredit/{id}', function (Request $request, $id) use ($app){
    
    // on selectionne notre user par son id
    $user = $app['users.dao']->find($id);
    
    $form = $app['form.factory']->createBuilder(\FormType\UserType::class, $user)
            ->remove('password')//on enlève le champs password car l'admin n'a pas d'acces aux mdp des user
            ->getForm();
    
    $form->handleRequest($request); // gerer - etape de validation
    
    if($form->isValid()){ // si tous les contraintes sont respéctés
        
        //creation de grain de sel
        $salt = md5(time());
        $user->setSalt($salt); // on la stock dans notre objet
        
        // encodage du password
        $encodedPassword = $app['security.encoder_factory']
                ->getEncoder($user) //recupere notre encoder personalisé pour chaque user
                ->encodePassword($user->getPassword(), $user->getSalt()); //encodage avec le sel
        
        // on stock notre mdp encodé dans notre objet
        $user->setPassword($encodedPassword);
        
        // on utilise une methode de la classe SimpleDAO pour enregistrer notre user dans bdd
        $app['users.dao']->save($user); 
        return $app->redirect($app['url_generator']->generate('admin_userlist'));
        
    }
    
    $formView = $form->createView();
    
    return $app['twig']->render('admin/useredit.html.twig', ['form' => $formView]);
})
->bind('admin_useredit')
->method('GET|POST')
;



// MONTAGE de la groupe
$app->mount('/admin', $adminGroup);

