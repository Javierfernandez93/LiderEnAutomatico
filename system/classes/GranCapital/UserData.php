<?php

namespace GranCapital;

use HCStudio\Orm;

class UserData extends Orm {
  protected $tblName  = 'user_data';

  public function __construct() {
    parent::__construct();
  }

  public static function make(array $data = null) : bool
  {
    $UserData = new UserData;
		
		if(!$UserData->cargarDonde('user_login_id =?',$data['user_login_id']))
		{
			$UserData->user_login_id = $data['user_login_id'];
		}
		
		$UserData->names = $data['names'] ? $data['names'] : $UserData->names;
		$UserData->last_name = $data['last_name'] ? $data['last_name'] : $UserData->last_name;
		$UserData->sur_name = $data['sur_name'] ? $data['sur_name'] : $UserData->sur_name;
		$UserData->birthday = $data['birthday'] ? $data['birthday'] : $UserData->birthday;
		$UserData->id_number = $data['id_number'] ? $data['id_number'] : $UserData->id_number;
		$UserData->gender = $data['gender'] ? $data['gender'] : $UserData->gender;
		
		return $UserData->save();
  }
  
  public function getNames($user_login_id = null) 
  {
    if(isset($user_login_id) === true)
    {
      $sql = "SELECT
                LOWER(CONCAT_WS(' ',
                  {$this->tblName}.names,
                  {$this->tblName}.last_name,
                  {$this->tblName}.sur_name
                )) as names
              FROM 
                {$this->tblName}
              WHERE 
                {$this->tblName}.user_login_id = '{$user_login_id}'
              ";
      return $this->connection()->field($sql);
    }

    return false;
  }

  public function search($names = null,$filter = "") 
  {
    if(isset($names) === true && empty($names) === false)
    {
      $sql = "SELECT
                {$this->tblName}.user_login_id,
                user_type.catalog_user_type_id,
                LOWER(CONCAT_WS(' ',
                  {$this->tblName}.names,
                  {$this->tblName}.last_name,
                  {$this->tblName}.sur_name
                )) as names
              FROM 
                {$this->tblName}
              LEFT JOIN 
                client_per_seller
              ON 
                client_per_seller.user_login_id = {$this->tblName}.user_login_id
              LEFT JOIN 
                user_type
              ON 
                user_type.user_login_id = {$this->tblName}.user_login_id
              WHERE 
                (
                    {$this->tblName}.names LIKE '%{$names}%'
                  OR 
                    CONCAT_WS(' ',{$this->tblName}.names,{$this->tblName}.last_name)  LIKE '%{$names}%'
                  OR 
                    CONCAT_WS(' ',{$this->tblName}.names,{$this->tblName}.last_name,{$this->tblName}.sur_name)  LIKE '%{$names}%'
                )
                {$filter}
              ";
      
      return $this->connection()->rows($sql);
    }

    return false;
  }
}