<?php

namespace FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Description of UserType
 *
 * @author vassilina
 */

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        global $app;
        $builder->add('username', TextType::class, [
            'constraints' => [
                new Assert\NotBlank(), 
                new Assert\Length(['min' => 2, 'max' => 50]),
                new \Constraints\UniqueEntity([
                    'field' => 'username',
                    'dao' => $app['users.dao']
                ])
            ],
            'label' => 'Nom d\'utilisateur'
        ])
        ->add('email', TextType::class, [
            'constraints' => [
                new Assert\NotBlank(), 
                new Assert\Email(),
                new \Constraints\UniqueEntity([
                    'field' => 'email',
                    'dao' => $app['users.dao']
                ])
            ],
            'label' => 'Adresse mail'
        ])
        ->add('lastname', TextType::class, [
            'constraints' => [
                new Assert\NotBlank(), 
                new Assert\Length(['max' => 100])
            ],
            'label' => 'Nom'
        ])
        ->add('firstname', TextType::class, [
            'constraints' => [
                new Assert\NotBlank(), 
                new Assert\Length(['max' => 100])
            ],
            'label' => 'Prénom'
        ])
                
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'options' => array('attr' => array('class' => 'password-field')),
            'required' => true,
            'first_options'  => [
                'constraints' => [
                    new Assert\NotBlank(), 
                    new Assert\Length(['min' => 5, 'max' => 30])
                ],
                'label' => 'Mot de passe'
            ],
            'second_options' => [
                'label' => 'Mot de passe de confirmation'
            ]
        ])
        
        ->add('submit', SubmitType::class, [
            'label' => 'Envoyer'
        ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => \Entity\User::class 
        ]);
    }
}
