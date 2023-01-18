<?php

namespace GranCapital;

use HCStudio\Orm;

class UserAccount extends Orm {
  const DEFAULT_IMAGE = '../../src/img/user/user.png';
  protected $tblName  = 'user_account';

  public function __construct() {
    parent::__construct();
  }
  
  public function getLPOA(int $user_login_id = null) 
  {
    if(isset($user_login_id) === true)
    {
      $sql = "SELECT    
                {$this->tblName}.lpoa 
              FROM 
                {$this->tblName}
              WHERE 
                {$this->tblName}.user_login_id = '{$user_login_id}'
              ";
      return $this->connection()->field($sql);
    }

    return false;
  }
}