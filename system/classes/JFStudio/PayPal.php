<?php

namespace JFStudio;

use HCStudio\Util;
use JFStudio\ValidatorHelper;
use JFStudio\ModelPayPalErrors;
use Jcart\ModelPaymentMethod;
use Exception;

class PayPal {
    // const CLIENT_ID = "AfR2RRNUmTU82_mkmKJvUgCnYYrILn-p3NKjlHAOnLpIHXiNVR0Ag7vpouPxiLuPwG8O8ad65BCJF1oo"; 
    // const CLIENT_SECRET = "ENgAx4PgpYXzBgbiPvL74RaOL3LuHfjpqdwq1Rk3z2xn9sFkxiswVig4IqZfiJjx5XHrFJExMHFYaoDD";

    /* states */
    const APPROVED = 'approved';


    const CLIENT_ID = "AdwyJ9IikdGSF6yu1XhcWMybTjuba30NjQZfKLnPXN3_cYOHDsZlglkPoVMPLdTTfgSKzrBRXe4jir-Y"; 
    const CLIENT_SECRET = "EDeQdrc4logyni_uoHNFyALxzKyiBFFexNbfzZAWGU16nFztxEgHB8Yw54Fqm86PFhGnQESyPpqrwJJt";

    const RETURN_URL = "http://localhost:8888/LiderEnAutomatico/apps/paypal/index.php";
    const CANCEL_URL = "http://localhost:8888/LiderEnAutomatico/apps/backoffice";
    const URL = "http://localhost:8888/LiderEnAutomatico/apps/admin/subcore/application/validate_buy.php";
    
    const MODE = 'sandbox'; // 'live', 'sandbox'
    
    const FAILED = "failed";
    const TEMPORAL_VALIDATION = true;

    private static $instance;
    
	public static function getInstance()
 	{
    	if(!self::$instance instanceof self)
      		self::$instance = new self;

    	return self::$instance;
 	}

 	public function validateBuy($paymentId = null,&$BuyPerUser = null)
 	{
 		if (isset($paymentId)) 
 		{	
 			if($buy_per_user_login = $BuyPerUser->getBuyPerUserLoginIdByReference($paymentId))
 			{
                if($buy_per_user_login['verified'] == '0')
                {
                    if($buy_per_user_login['payment_method'] == ModelPaymentMethod::$PAYPAL)
                    {
                        $BuyPerUser->cargarDonde("buy_per_user_login_id = ?",$buy_per_user_login['buy_per_user_login_id']);

                        if(ValidatorHelper::$TEMPORAL_VALIDATION === true)
                        {
                            $BuyPerUser->second_reference = "validación temporal-".date("Y-m-d H:i:s");
                            
                            if($BuyPerUser->save() == true)
                            {
                                $response['s'] == 1;

                                return $response;
                            } else {
                                throw new Exception(ModelPayPalErrors::$VALIDATE_TEMPORAL_ERROR);        
                            }
                        } else {
             				$response = Util::doCurl($this->URL,[
            					'PHP_AUTH_USER' => Util::$username,
                                'PHP_AUTH_PW' => Util::$password,
                                'buy_per_user_login_id' => $buy_per_user_login['buy_per_user_login_id']
            				]);
                            
                            if($response['s'] == "1")
                            {
                                return $response;
                            } else if($response['r'] == 'VALIDATE_ERROR') {
                                throw new Exception(ModelPayPalErrors::$VALIDATE_ERROR);        
                            }
                        }
                    } else {
                        throw new Exception(ModelPayPalErrors::$NOT_DEPOSIT);
                    }
                } else if($buy_per_user_login['verified'] == '1') {
                    throw new Exception(ModelPayPalErrors::$ALREADY_VALIDATED);
                } else if($buy_per_user_login['verified'] == '-1') {
                    throw new Exception(ModelPayPalErrors::$NOT_FOUND);
                } else if($buy_per_user_login['verified'] == '2') {
                    throw new Exception(ModelPayPalErrors::$NOT_FOUND);
                }
 			} else {
                throw new Exception(ModelPayPalErrors::$NOT_FOUND);
            }
 		}

 		return false;
 	}
}