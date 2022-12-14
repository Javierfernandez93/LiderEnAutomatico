<?php

namespace GranCapital;

use HCStudio\Orm;

class TransactionRequirementPerUser extends Orm {
    const PENDING = 1;
    const DELETED = -1;
    const EXPIRED = 0;
    const VALIDATED = 2;
    
    const CRONJOB = 1;
    const PAYPAL_CDN = 3;
    const STRIPE_CDN = 4;
    const ADMIN = 2;

    CONST DEFAULT_CURRENCY = 'USD';
    protected $tblName = 'transaction_requirement_per_user';

    public function __construct() {
        parent::__construct();
    }

    public function getTransactionsPending() 
    {
        return $this->getTransactions(" WHERE {$this->tblName}.status = '".self::PENDING."'");
    }

    public function getTransactions(string $filter = null) 
    {
        $sql = "SELECT
                    {$this->tblName}.{$this->tblName}_id,
                    {$this->tblName}.ammount,
                    {$this->tblName}.user_login_id,
                    {$this->tblName}.item_number,
                    {$this->tblName}.txn_id,
                    {$this->tblName}.image,
                    {$this->tblName}.create_date,
                    {$this->tblName}.checkout_data,
                    {$this->tblName}.validation_method,
                    {$this->tblName}.user_support_id,
                    {$this->tblName}.payment_reference,
                    {$this->tblName}.catalog_payment_method_id,
                    {$this->tblName}.registration_date,
                    {$this->tblName}.validate_date,
                    {$this->tblName}.status,
                    user_login.email,
                    user_data.names
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
                    {$filter}
                GROUP BY 
                    {$this->tblName}.transaction_requirement_per_user_id 
                ";

        return $this->connection()->rows($sql);
    }

    public function getLastTransactions(int $user_login_id = null,string $limit = '') 
    {
        if(isset($user_login_id) === true)
        {
            $sql = "SELECT
                        {$this->tblName}.{$this->tblName}_id,
                        {$this->tblName}.fee,
                        {$this->tblName}.ammount,
                        {$this->tblName}.item_number,
                        {$this->tblName}.txn_id,
                        {$this->tblName}.create_date,
                        {$this->tblName}.catalog_payment_method_id,
                        {$this->tblName}.checkout_data,
                        {$this->tblName}.validate_date,
                        {$this->tblName}.registration_date,
                        {$this->tblName}.payment_reference,
                        {$this->tblName}.status
                    FROM 
                        {$this->tblName}
                    WHERE 
                        {$this->tblName}.user_login_id = '{$user_login_id}'
                    ORDER BY 
                        {$this->tblName}.create_date
                    DESC 
                        {$limit}
                    ";

            return $this->connection()->rows($sql);
        }

        return false;
    }
    
    public function checkTransactionStatus(int $transaction_requirement_per_user_id = null,string $filter = '') 
    {
        if(isset($transaction_requirement_per_user_id) === true)
        {
            $sql = "SELECT
                        {$this->tblName}.{$this->tblName}_id
                    FROM 
                        {$this->tblName}
                    WHERE 
                        {$this->tblName}.transaction_requirement_per_user_id = '{$transaction_requirement_per_user_id}'
                        {$filter}
                    ";

            return $this->connection()->field($sql) ? true : false;
        }

        return false;
    }

    public function isPending(int $transaction_requirement_per_user_id = null) : bool
    {
        return $this->checkTransactionStatus($transaction_requirement_per_user_id," AND {$this->tblName}.status = '".self::PENDING."'");
    }
    
    public function isPendingForRegistration(int $transaction_requirement_per_user_id = null) : bool
    {
        return $this->checkTransactionStatus($transaction_requirement_per_user_id," AND {$this->tblName}.status = '".self::PENDING."' AND {$this->tblName}.payment_reference = ''");
    }
    
    public function isAviableToReactive(int $transaction_requirement_per_user_id = null) : bool
    {
        return $this->checkTransactionStatus($transaction_requirement_per_user_id," AND {$this->tblName}.status IN ('".self::DELETED."','".self::EXPIRED."')");
    }
}