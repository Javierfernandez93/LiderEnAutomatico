<?php

namespace GranCapital;

use HCStudio\Orm;

class UserStripe extends Orm {
  protected $tblName  = 'user_stripe';
  public function __construct() {
    parent::__construct();
  }
  
  public function updateCustomer(int $user_login_id = null,string $customer_id = null) : bool
  {
    if(isset($user_login_id,$customer_id) === true)
    {
      if(!$this->cargarDonde('user_login_id = ?',$user_login_id))
      {
        $this->create_date = time();
        $this->user_login_id = $user_login_id;
      }
      
      $this->customer_id = $customer_id;

      return $this->save();
    }

    return false;
  }
  
  public function existCustomer(int $user_login_id = null) : bool
  {
    if(isset($user_login_id) === true)
    {
      $sql = "SELECT 
                {$this->tblName}.{$this->tblName}_id 
              FROM 
                {$this->tblName}
              WHERE
                {$this->tblName}.user_login_id = '{$user_login_id}'
              AND 
                {$this->tblName}.status = '1'
              ";

      return $this->connection()->field($sql) ? true : false;
    }

    return false;
  }
 
  public function getCustomerId(int $user_login_id = null)
  {
    if(isset($user_login_id) === true)
    {
      $sql = "SELECT 
                {$this->tblName}.customer_id
              FROM 
                {$this->tblName}
              WHERE
                {$this->tblName}.user_login_id = '{$user_login_id}'
              AND 
                {$this->tblName}.status = '1'
              ";

      return $this->connection()->field($sql);
    }

    return false;
  }
  
  public function getUserLoginId(string $customer_id = null)
  {
    if(isset($customer_id) === true)
    {
      $sql = "SELECT 
                {$this->tblName}.user_login_id
              FROM 
                {$this->tblName}
              WHERE
                {$this->tblName}.customer_id = '{$customer_id}'
              AND 
                {$this->tblName}.status = '1'
              ";

      return $this->connection()->field($sql);
    }

    return false;
  }
}