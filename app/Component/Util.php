<?php


namespace App\Component; 

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

class Util
{
		
	// refausa.com  => http://refausa.com
    public static function addhttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }

    // refausa.com  => https://refausa.com
    public static function addhttps($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "https://" . $url;
        }
        return $url;
    }

    // get html source, capable of handling redirects
    public static function http_get ($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }
	
	
	public static function trimDomain($strDomain)
	{
			

		return $strDomain;
	}
	
	
	public static function getGuzzleBody($objResponse)
	{
		
		
	}
	
	
	public static function getVersion($strHtml)
	{
		$strHtml = str_replace("\n", "", $strHtml);
		$strHtml = str_replace("\r", "", $strHtml);
		
		if(preg_match("/\(Magento Connect Manager([^\)]*)\)/", $strHtml, $arrOut)){
			return trim($arrOut[1]);
		} else {
			return false;
		}
	}
	
	/**
	 * pass over url, scan and test the http return code.
	 */
	public static function getUrlCode($url)
	{
		$url = Util::addhttp($url);
		try {
			$objClient = resolve(Client::class);
			$objResponse = $objClient->get($url);				
			$strCode = $objResponse->getStatusCode();
			
		} catch (ClientException $e) {
			$objResponse = $e->getResponse();
			$strCode = $objResponse->getStatusCode();
		} catch (\Exception $e) {
			return false;
		}
		
		return $strCode;
		
	}

	public static function getClientIp() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
		else
        $ipaddress = 'UNKNOWN';
		
		return $ipaddress;
	}	
	
	/**
	 *  strip change line character
	 */
	public static function strip($str)
	{
		$str = str_replace("\n", "", $str);
		$str = str_replace("\r", "", $str);
		return $str;
	}
		
		
	public static function getPageHref($strHtml)
	{
		$strHtml = strtolower($strHtml);
		$strHtml = self::strip($strHtml);
		
		$pattern = "/<a[^>]*href=[\"'](.*?)[\"']/";

		$arrFinal = array();
		if(preg_match_all($pattern, $strHtml, $arrOut)){
			return $arrOut[1];			
			
		} else {
			return false;
		}

	}
}
