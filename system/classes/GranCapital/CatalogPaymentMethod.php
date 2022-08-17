<?php

namespace GranCapital;

use HCStudio\Orm;
use HCStudio\Util;


class CatalogPaymentMethod extends Orm {
	protected $tblName = 'catalog_payment_method';
	const CRYPTO = 1;

	const PAYPAL = 5;
	const STRIPE = 6;
	const STRIPE_USA = 7;
	const TRANSFER_MXN = 8;

	// state
	const DELETED = -1;
	const UNAVIABLE = 0;
	const AVIABLE = 1;
	public function __construct() {
		parent::__construct();
	}

	public static function getFee(float $fee, float $ammount = null) : float
	{
		return $fee > 0 ? Util::getPercentaje($ammount,$fee) : 0;
	}

	public function getAll(string $filter = "WHERE catalog_payment_method.status = '1'")
	{
		$sql = "SELECT 
					{$this->tblName}.{$this->tblName}_id,
					{$this->tblName}.currency,
					{$this->tblName}.fee,
					{$this->tblName}.catalog_currency_id,
					{$this->tblName}.additional_info,
					{$this->tblName}.image,
					{$this->tblName}.description,
					{$this->tblName}.create_date,
					{$this->tblName}.additional_data,
					{$this->tblName}.recomended,
					{$this->tblName}.status,
					{$this->tblName}.code
				FROM 
					{$this->tblName}
					{$filter}
				";
		
		return $this->connection()->rows($sql);
	}
	
	public function isCrypto(int $catalog_payment_method_id = null) : bool
	{
		return $this->getCatalogCurrencyId($catalog_payment_method_id) == self::CRYPTO ? true : false;
	}
	
	public function getCode(int $catalog_payment_method_id = null) : bool
	{
		$sql = "SELECT 
					{$this->tblName}.code
				FROM 
					{$this->tblName}
				WHERE 
					{$this->tblName}.catalog_payment_method_id = '{$catalog_payment_method_id}'
				AND  
					{$this->tblName}.status = '1'
				";
		
		return $this->connection()->field($sql);
	}
	
	public function getCurrency(int $catalog_payment_method_id = null) : string
	{
		$sql = "SELECT 
					{$this->tblName}.currency
				FROM 
					{$this->tblName}
				WHERE 
					{$this->tblName}.catalog_payment_method_id = '{$catalog_payment_method_id}'
				AND  
					{$this->tblName}.status = '1'
				";
		
		return $this->connection()->field($sql);
	}
	
	public function getCatalogCurrencyId(int $catalog_payment_method_id = null)
	{
		if(isset($catalog_payment_method_id) === true)
		{
			$sql = "SELECT 
						{$this->tblName}.catalog_currency_id
					FROM 
						{$this->tblName}
					WHERE 
						{$this->tblName}.catalog_payment_method_id = '{$catalog_payment_method_id}'
					AND  
						{$this->tblName}.status = '1'
					";
			
			return $this->connection()->field($sql);
		}
	}
	
	public function get(int $catalog_payment_method_id = null)
	{
		if(isset($catalog_payment_method_id) === true)
		{
			$sql = "SELECT 
						{$this->tblName}.catalog_currency_id,
						{$this->tblName}.fee,
						{$this->tblName}.currency,
						{$this->tblName}.code,
						{$this->tblName}.description,
						{$this->tblName}.registrable,
						{$this->tblName}.additional_data,
						{$this->tblName}.image
					FROM 
						{$this->tblName}
					WHERE 
						{$this->tblName}.catalog_payment_method_id = '{$catalog_payment_method_id}'
					AND  
						{$this->tblName}.status = '1'
					";
			
			return $this->connection()->row($sql);
		}
	}
}