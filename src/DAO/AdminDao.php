<?php

namespace DAO;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;


/**
 * Description of AminDao
 *
 * @author Vassilina
 */
class AdminDao extends UserDao {
    
     protected $tableName = 'user';
     
     public function __construct(\PDO $db)
     {
        parent::__construct($db, 'user');
        $this->entityClassName = '\Entity\User';
        
     }

     public function loadUserByUsername($username) 
    {
        // SELECT * FROM user WHERE username = ? AND role LIKE ? LIMIT 1
        // bindValue(1, $username)
        $user = $this->findOne(array(
            'username = ?'=>$username,
            'role LIKE ?' =>"%ROLE_ADMIN%"
        ));
        
        if(! $user){
            throw new UsernameNotFoundException("User with username $username does not exist");
        }
        
        return $user;
    }

}
