<?php
/**
*
*/

namespace JFStudio;

use JFStudio\Curl;

class Currency
{
    public $key = "cc6d90f6d73be4d3e1c9ef185070ac90";
    public $url = "https://xecdapi.xe.com/v1/convert_to.json/?to=CAD&from=USD,EUR&amount=1000&username=dronesprueba985325638&password=17mgk0deofacqh39nkkdlvjo7s";
    public $primary_currency = "USD";

	private static $instance;

	public static function getInstance()
 	{
	    if(!self::$instance instanceof self)
	      self::$instance = new self;

	    return self::$instance;
 	}

	public function get_currency_api_xecdapi($url)
	{
		if(isset($url) === true)
		{
			$Curl = new Curl;
			$Curl->get("https://xecdapi.xe.com/v1/convert_to.json/?to=CAD&from=USD,EUR&amount=1000&username=dronesprueba985325638&password=17mgk0deofacqh39nkkdlvjo7s");

			print_r( $Curl->getResponse(true));
		}
    }

    public function get_currency($url = null)
	{
		if(isset($url) === true)
		{
			$Curl = new Curl;
			$Curl->get($url);

			print_r( $Curl->getResponse(true));
		}
	}
    
    public function get_currency_api_exchange($currency=null , $Amount =null){
        if(isset($currency,$Amount)===true){
           $url= "https://api.exchangeratesapi.io/latest?base=".$this->primary_currency."&symbols=".$currency;
           
          
        //    print_r($this->get_currency($url));

           return $this->get_currency($url);
        }
    }


    public function get_currency_api_cambio($currency=null , $Amount =null){
        if(isset($currency,$Amount)===true){
         $url = 'https://api.cambio.today/v1/quotes/'.$this->primary_currency."/".$currency."/json?quantity=".$Amount."&key=6500|M7zWxBeZmX9HMAj0E_6Cu*vL*~0hgfUr";

           return $this->get_currency($url);
        }

    }
    
}