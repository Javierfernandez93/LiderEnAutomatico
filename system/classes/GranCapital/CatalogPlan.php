<?php

namespace GranCapital;

use HCStudio\Orm;

class CatalogPlan extends Orm {
	const MAX_PROFIT = 12;
	protected $tblName = 'catalog_plan';
	public function __construct() {
		parent::__construct();
	}

	public function getAll()
	{
		$sql = "SELECT 
					{$this->tblName}.{$this->tblName}_id,
					{$this->tblName}.name,
					{$this->tblName}.goal,
					{$this->tblName}.image,
					{$this->tblName}.additional_data,
					{$this->tblName}.profit
				FROM 
					{$this->tblName}
				WHERE 
					{$this->tblName}.status = '1'
				";
		
		return $this->connection()->rows($sql);
	}
	
	public function isAviableProfit(int $catalog_plan_id = null,$additional_profit = null,$sponsor_profit = null)
	{
		$profit = $this->getProfit($catalog_plan_id);

		return $profit + $additional_profit + $sponsor_profit <= self::MAX_PROFIT;
	}

	public function getProfit(int $catalog_plan_id = null)
	{
		if(isset($catalog_plan_id) === true)
		{
			$sql = "SELECT 
						{$this->tblName}.profit
					FROM 
						{$this->tblName}
					WHERE 
						{$this->tblName}.catalog_plan_id = '{$catalog_plan_id}'
					AND 
						{$this->tblName}.status = '1'
					";
			
			return $this->connection()->field($sql);
		}

		return false;
	}
	
	public function getCatalogPlanIdBetween(int $ammount = null)
	{
		if(isset($ammount) === true)
		{
			$catalog_plan_id = 0;

			if($catalog_plans = $this->getAll())
			{
				foreach($catalog_plans as $key => $catalog_plan)
				{
					$additional_data = json_decode($catalog_plan['additional_data'],true);

					if($ammount >= $additional_data['min'] && $ammount <= $additional_data['max'])
					{
						$catalog_plan_id = $catalog_plan['catalog_plan_id'];
					}
				}
			}

			return $catalog_plan_id;
		}

		return false;
	}
}