<?php

namespace GranCapital;

use HCStudio\Orm;

use GranCapital\UserPlan;

class UserReferral extends Orm {
  protected $tblName  = 'user_referral';

  public function __construct() {
    parent::__construct();
  }
  
  public function getLastReferrals(int $referral_id = null) 
  {
    return $this->getReferrals($referral_id," ORDER BY {$this->tblName}.create_date DESC LIMIT 5 ");
  }

  public function getReferralCount(int $referral_id = null,string $filter = '') 
  {
    if(isset($referral_id) === true) 
    {
      $sql = "SELECT 
                COUNT({$this->tblName}.user_login_id) as c
              FROM 
                {$this->tblName} 
              WHERE 
                {$this->tblName}.referral_id = '{$referral_id}' 
              AND 
                {$this->tblName}.status = '1'
                {$filter}
              ";

      return $this->connection()->field($sql);
    }
  }

  public function getUserReferralId(int $user_login_id = null) 
  {
    if(isset($user_login_id) === true) 
    {
      $sql = "SELECT 
                {$this->tblName}.referral_id
              FROM 
                {$this->tblName} 
              WHERE 
                {$this->tblName}.user_login_id = '{$user_login_id}' 
              AND 
                {$this->tblName}.status = '1'
              ";

      return $this->connection()->field($sql);
    }
  }

  public function getReferrals(int $referral_id = null,string $filter = '') 
  {
    if(isset($referral_id) === true) 
    {
      $sql = "SELECT 
                {$this->tblName}.user_login_id,
                user_data.names,
                user_account.image,
                user_login.signup_date,
                user_login.company_id,
                user_login.email
              FROM 
                {$this->tblName} 
              LEFT JOIN 
                user_data
              ON 
                user_data.user_login_id = {$this->tblName}.user_login_id
              LEFT JOIN 
                user_account
              ON 
                user_account.user_login_id = {$this->tblName}.user_login_id
              LEFT JOIN 
                user_login
              ON 
                user_login.user_login_id = {$this->tblName}.user_login_id
              WHERE 
                {$this->tblName}.referral_id = '{$referral_id}' 
              AND 
                {$this->tblName}.status = '1'
              GROUP BY 
                user_login.company_id
                {$filter}
              ";

      return $this->connection()->rows($sql);
    }
  }
  
  public function getReferral(int $user_login_id = null) 
  {
    if(isset($user_login_id) === true) 
    {
      $sql = "SELECT 
                user_login.user_login_id,
                user_data.names,
                user_account.image,
                user_login.signup_date,
                user_login.email
              FROM 
                {$this->tblName} 
              LEFT JOIN 
                user_data
              ON 
                user_data.user_login_id = {$this->tblName}.referral_id
              LEFT JOIN 
                user_account
              ON 
                user_account.user_login_id = {$this->tblName}.referral_id
              LEFT JOIN 
                user_login
              ON 
                user_login.user_login_id = {$this->tblName}.referral_id
              WHERE 
                {$this->tblName}.user_login_id = '{$user_login_id}' 
              AND 
                {$this->tblName}.status = '1'
              GROUP BY 
                {$this->tblName}.user_login_id
              ";
              
      return $this->connection()->row($sql);
    }
  }
  
  public function getUser(int $user_login_id = null) 
  {
    if(isset($user_login_id) === true) 
    {
      $sql = "SELECT 
                user_login.user_login_id,
                user_data.names,
                user_account.image,
                user_login.signup_date,
                user_login.email
              FROM 
                {$this->tblName} 
              LEFT JOIN 
                user_data
              ON 
                user_data.user_login_id = {$this->tblName}.user_login_id
              LEFT JOIN 
                user_account
              ON 
                user_account.user_login_id = {$this->tblName}.user_login_id
              LEFT JOIN 
                user_login
              ON 
                user_login.user_login_id = {$this->tblName}.user_login_id
              WHERE 
                {$this->tblName}.user_login_id = '{$user_login_id}' 
              AND 
                {$this->tblName}.status = '1'
              GROUP BY 
                {$this->tblName}.user_login_id
              ";
              
      return $this->connection()->row($sql);
    }
  }

  public function _getNetwork(int $limit = -1,int $referral_id = null) 
  {
    if($levels = $this->getNetwork($limit,$referral_id))
    {
      $UserPlan = new UserPlan;
      $users = [];

      foreach($levels as $levelKey => $level) 
      {
        foreach($level as $key => $user_login_id) {
          $users[$levelKey][$key] = array_merge($this->getUser($user_login_id),["plan" => $UserPlan->getPlan($user_login_id)]);
        }
      }

      return $users;
    }

    return false;
  }

  public function getNetwork(int $limit = -1,$referral_id = null,int $count = 0) 
  {
    $result = [];
    $sql = "SELECT {$this->tblName}.user_login_id FROM {$this->tblName} WHERE {$this->tblName}.referral_id IN ({$referral_id})";      

    if (($count != $limit) && ($data = $this->connection()->column($sql))) 
    {
      $count++;
     
      $join = join(",", $data);

      $result = $this->getNetwork($limit, $join, $count);

      $result = array_merge([$data], $result);
    }
    
    return $result;
  }
}