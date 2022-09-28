<?php

namespace GranCapital;

use HCStudio\Orm;

class InvestorPerUser extends Orm {
	protected $tblName = 'investor_per_user';
	public function __construct() {
		parent::__construct();
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