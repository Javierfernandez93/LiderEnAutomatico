<?php

namespace GranCapital;

use HCStudio\Orm;

use GranCapital\UserWallet;
use GranCapital\TransactionPerWallet;
use GranCapital\CatalogPlan;
use PayPal\Api\Amount;

class UserPlan extends Orm {
  protected $tblName  = 'user_plan';
	
  /* constants */
  const MAX_ADDITIONAL_PROFIT = 2;
	const FIRST_PLAN = 1;
	const FIRST_PLAN_AMOUNT = 1000;

  public function __construct() {
    parent::__construct();
  }

	public static function attachFirstPlan(int $user_login_id = null,int $catalog_plan_id = null,float $ammount = null,float $additional_profit = 0,float $sponsor_profit = 0)
	{
		if(isset($user_login_id,$catalog_plan_id) === true)
		{
      $UserPlan = new UserPlan;

      if(!$UserPlan->cargarDonde("user_login_id = ?",$user_login_id))
      {
          $UserPlan->user_login_id = $user_login_id;
          $UserPlan->create_date = time();
      }

      $UserPlan->additional_profit = $additional_profit ? $additional_profit : $UserPlan->additional_profit;
      $UserPlan->sponsor_profit = $sponsor_profit ? $sponsor_profit : $UserPlan->sponsor_profit;

      $UserPlan->ammount = $ammount;
      $UserPlan->catalog_plan_id = $ammount > 0 ? $catalog_plan_id : 0;
      
      return $UserPlan->save();
		}

    return false;
	}


  public function hasPlan($user_login_id = null) : bool
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

  public function getPlan(int $user_login_id = null) 
  {
    if(isset($user_login_id) === true)
    {
      $sql = "SELECT
                {$this->tblName}.{$this->tblName}_id,
                {$this->tblName}.ammount,
                {$this->tblName}.additional_profit,
                {$this->tblName}.sponsor_profit,
                catalog_plan.profit,
                catalog_plan.name
              FROM 
                {$this->tblName}
              LEFT JOIN 
                catalog_plan 
              ON 
                catalog_plan.catalog_plan_id = {$this->tblName}.catalog_plan_id
              WHERE 
                {$this->tblName}.user_login_id = '{$user_login_id}'
              AND 
                {$this->tblName}.status = '1'
              ";

      return $this->connection()->row($sql);
    }

    return false;
  }
  
  public function getUserId($user_plan_id = null) 
  {
    if(isset($user_plan_id) === true)
    {
      $sql = "SELECT
                {$this->tblName}.user_login_id
              FROM 
                {$this->tblName}
              WHERE 
                {$this->tblName}.user_plan_id = '{$user_plan_id}'
              AND 
                {$this->tblName}.status = '1'
              ";

      return $this->connection()->field($sql);
    }

    return false;
  }
  
  public function getUserPlanId(int $user_login_id = null) 
  {
    if(isset($user_login_id) === true)
    {
      $sql = "SELECT
                {$this->tblName}.user_plan_id
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
  
  public function getMyRange(int $user_login_id = null) 
  {
    if(isset($user_login_id) === true)
    {
      $sql = "SELECT
                {$this->tblName}.catalog_plan_id
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

  public function getActivePlans(string $filter = '') 
  {
    $sql = "SELECT
              {$this->tblName}.{$this->tblName}_id,
              {$this->tblName}.additional_profit,
              {$this->tblName}.ammount,
              {$this->tblName}.sponsor_profit,
              {$this->tblName}.user_login_id,
              catalog_plan.catalog_plan_id,
              catalog_plan.profit,
              catalog_plan.name
            FROM 
              {$this->tblName}
            LEFT JOIN 
              catalog_plan 
            ON 
              catalog_plan.catalog_plan_id = {$this->tblName}.catalog_plan_id
            WHERE 
              {$this->tblName}.status = '1'
              {$filter}
            ";

    return $this->connection()->rows($sql);
  }

  public function calculateProfit(int $user_login_id = null,$additional_profit = null,int $catalog_plan_id = null) 
  {
    if($actual_plan = $this->getPlan($user_login_id))
    {
      if($next_profit = (new CatalogPlan)->getProfit($catalog_plan_id))
      {
        if($actual_plan['profit'] != $next_profit)
        {
          if($actual_plan['additional_profit'] + $actual_plan['profit'] > $next_profit)
          {
            $additional_profit = ($actual_plan['additional_profit'] + $actual_plan['profit']) - $next_profit;
          }
        }
      }
    }

    return $additional_profit;
  }

  public function setPlan(int $user_login_id = null,$additional_profit = null,$sponsor_profit = null) : bool
  {
    if(isset($user_login_id) === true)
    {
      $UserWallet = new UserWallet;

      if($UserWallet->getSafeWallet($user_login_id))
      {
        $TransactionPerWallet = new TransactionPerWallet;

        $ammount = $TransactionPerWallet->getSumDepositsByUser($UserWallet->getId());
        
        $CatalogPlan = new CatalogPlan;

        if($catalog_plan_id = $CatalogPlan->getCatalogPlanIdBetween($ammount))
        {
          $UserPlan = new UserPlan;

          if(!$UserPlan->cargarDonde("user_login_id = ?",$user_login_id))
          {
              $UserPlan->user_login_id = $user_login_id;
              $UserPlan->create_date = time();
          }

          $UserPlan->additional_profit = $additional_profit ? $additional_profit : $UserPlan->additional_profit;
          $UserPlan->sponsor_profit = $sponsor_profit ? $sponsor_profit : $UserPlan->sponsor_profit;

          $UserPlan->ammount = $ammount;
          $UserPlan->catalog_plan_id = $ammount > 0 ? $catalog_plan_id : 0;
          
          return $UserPlan->save();
        }
      }
    }

    return false;
  }
}