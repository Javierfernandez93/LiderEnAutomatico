<?php

namespace GranCapital;

use HCStudio\Orm;

class UserAddress extends Orm {
  protected $tblName  = 'user_address';

  public function __construct() {
    parent::__construct();
  }

  public static function make(array $data = null) : bool
  {
    $UserAddress = new UserAddress;
		
		if(!$UserAddress->cargarDonde('user_login_id =?',$data['user_login_id']))
		{
			$UserAddress->user_login_id = $data['user_login_id'];
		}
		
		$UserAddress->address = $data['address'] ? $data['address'] : $UserAddress->address;
		$UserAddress->colony = $data['colony'] ? $data['colony'] : $UserAddress->colony;
		$UserAddress->zip_code = $data['zip_code'] ? $data['zip_code'] : $UserAddress->zip_code;
		$UserAddress->city = $data['city'] ? $data['city'] : $UserAddress->city;
		$UserAddress->state = $data['state'] ? $data['state'] : $UserAddress->state;
		$UserAddress->contry = $data['contry'] ? $data['contry'] : $UserAddress->contry;
		$UserAddress->contry_id = $data['contry_id'] ? $data['contry_id'] : $UserAddress->contry_id;

		return $UserAddress->save();
  }

  public function getAddress($user_login_id = null)
  {
    if(isset($user_login_id) === true)
    {
      $sql = "SELECT
                {$this->tblName}.address,
                {$this->tblName}.city,
                {$this->tblName}.colony,
                {$this->tblName}.zip_code,
                {$this->tblName}.state,
                {$this->tblName}.country_id
              FROM 
                {$this->tblName}
              WHERE
                {$this->tblName}.user_login_id = '{$user_login_id}'
              ";
                
      return $this->connection()->row($sql);
    }

    return false;
  }
}