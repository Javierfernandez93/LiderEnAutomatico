<?php

namespace GranCapital;

use HCStudio\Orm;

class CatalogPaymentMethod extends Orm {
	protected $tblName = 'catalog_payment_method';
	public function __construct() {
		parent::__construct();
	}

	public function getAll()
	{
		$sql = "SELECT 
					{$this->tblName}.{$this->tblName}_id,
					{$this->tblName}.currency,
					{$this->tblName}.fee,
					{$this->tblName}.additional_info,
					{$this->tblName}.image,
					{$this->tblName}.description,
					{$this->tblName}.recomended,
					{$this->tblName}.code
				FROM 
					{$this->tblName}
				WHERE 
					{$this->tblName}.status = '1'
				";
		
		return $this->connection()->rows($sql);
	}
	
	public function getCode(int $catalog_currency_id = null)
	{
		$sql = "SELECT 
					{$this->tblName}.code
				FROM 
					{$this->tblName}
				WHERE 
					{$this->tblName}.catalog_currency_id = '{$catalog_currency_id}'
				AND  
					{$this->tblName}.status = '1'
				";
		
		return $this->connection()->field($sql);
	}
}