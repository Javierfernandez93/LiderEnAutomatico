<?php

namespace GranCapital;

use HCStudio\Orm;

class InvestorPerUser extends Orm {
	protected $tblName = 'investor_per_user';
	public function __construct() {
		parent::__construct();
	}

	public static function make(array $data = null) : bool
	{
		$InvestorPerUser = new InvestorPerUser;
		
		if(!$InvestorPerUser->cargarDonde('user_login_id =?',$data['user_login_id']))
		{
			$InvestorPerUser->user_login_id = $data['user_login_id'];
			$InvestorPerUser->create_date = time();
		}
		
		$InvestorPerUser->number = $data['number'] ? $data['number'] : $InvestorPerUser->number;
		$InvestorPerUser->password = $data['password'] ? $data['password'] : $InvestorPerUser->password;
		
		return $InvestorPerUser->save();
	}

	public function getInvestorInfo(int $user_login_id = null)
	{
		if(isset($user_login_id) === true)
		{
			$sql = "SELECT 
						{$this->tblName}.{$this->tblName}_id,
						{$this->tblName}.number,
						{$this->tblName}.password
					FROM 
						{$this->tblName}
					WHERE 
						{$this->tblName}.user_login_id = '{$user_login_id}'
					AND 
						{$this->tblName}.status = '1'
					";
			
			return $this->connection()->row($sql);
		}

		return false;
	}
}