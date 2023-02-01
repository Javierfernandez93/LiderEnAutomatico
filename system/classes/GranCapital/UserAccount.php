<?php

namespace GranCapital;

use HCStudio\Orm;
use HCStudio\Token;

class UserAccount extends Orm {
  const DEFAULT_IMAGE = '../../src/img/user/user.png';
  protected $tblName  = 'user_account';

  const DEFAULT_SING_CODE_LENGTH = 7;
  public function __construct() {
    parent::__construct();
  }
  
  public static function attachSignature(int $user_login_id = null,string $signature = null) 
  {
    $UserAccount = new UserAccount;
    
    if($UserAccount->cargarDonde('user_login_id = ?',$user_login_id))
    {
      $UserAccount->signature = $signature;
      
      return $UserAccount->save();
    }

    return false;
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
  
  public function getIdBySignCode(string $sign_code = null) 
  {
    if(isset($sign_code) === true)
    {
      $sql = "SELECT    
                {$this->tblName}.user_login_id 
              FROM 
                {$this->tblName}
              WHERE 
                {$this->tblName}.sign_code = '{$sign_code}'
              ";

      return $this->connection()->field($sql);
    }

    return false;
  }
  
  public function getUserSignature(int $user_login_id = null) 
  {
    if(isset($user_login_id) === true)
    {
      $sql = "SELECT    
                {$this->tblName}.signature 
              FROM 
                {$this->tblName}
              WHERE 
                {$this->tblName}.user_login_id = '{$user_login_id}'
              ";

      return $this->connection()->field($sql);
    }

    return false;
  }

  public static function generateSignCode(int $user_login_id = null)
  {
    if(isset($user_login_id) === true)
    {
      $UserAccount = new UserAccount;
      
      if($UserAccount->cargarDonde('user_login_id = ?',$user_login_id))
      {
        $UserAccount->sign_code = Token::__randomKey(self::DEFAULT_SING_CODE_LENGTH);
        
        if($UserAccount->save())
        {
          return $UserAccount->sign_code;
        }
      }
    }
  }
}