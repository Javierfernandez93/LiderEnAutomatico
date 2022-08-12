<?php

namespace JFStudio;

use JFStudio\Curl;

class ApiFixer
{
    private static $instance;
	
    const API_URL = 'https://data.fixer.io/api/'; 
    const ACCESS_KEY = '905fb66be96d7e2bbf1372ded44058a3';
    const CONVERT_ENDPOINT = 'convert';

    //states
    const SUCCESS = 1;

	public function __construct(){ }

	public static function getInstance()
 	{
	    if(!self::$instance instanceof self)
	      self::$instance = new self;

	    return self::$instance;
    }

	public function getEndpointUrl(string $endpoint = null) : string
    {
        return isset($endpoint) === true ? self::API_URL.$endpoint : self::API_URL;
    }

	public function convert(float $amount = null,string $from = null,string $to = null)
    {
        if(isset($amount,$from,$to) === true)
        {
            if($url = $this->getEndpointUrl(self::CONVERT_ENDPOINT))
            {
                $Curl = new Curl;
                $Curl->get($url,[
                    'access_key' => self::ACCESS_KEY,
                    'from' => $from,
                    'to' => $to,
                    'amount' => $amount,
                ]);
        
                $response = $Curl->getResponse(true);

                if($response['success'] == self::SUCCESS)
                {
                    return $response['result'];
                }
            }
        }


        return false;
    }
}	