<?php


namespace App\Component; 

use App\Component\SecurityMap;
use App\Component\Util;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

use App\Component\MagentoVersionMap;


/**
 *   * 
 *  * 
 *  * git push -u origin master
 */
class Magento
{

	private $downloaderURL = "/downloader";
	private $cssURL = "/skin/frontend/default/default/css/styles.css";


	public function getMagentoVersion($domain){
		$domain = trim($domain);
		$url = Util::addhttp($domain);
		$code = Util::getUrlCode($url);
		if ($code) {
			if (intval($code) >= 400){
				return array('status' => 'fail', 'message' => "An error has occurred.");
			}
		}else{
			return array('status' => 'fail', 'message' => "An error has occurred.");
		}
		$v = $this->getMagento1VersionByDownloader($domain);
		if($v){
			return array('status' => 'success', 'framework' => '1', 'version' => $v); 
		}else{
			$v = $this->getMagento1VersionByHash($domain);
			if($v){
				return array('status' => 'success', 'framework' => '1', 'version' => $v); 
			}else{
				$v = $this->getMagento1VersionByCopyright($domain);
				if($v){
					return array('status' => 'success', 'framework' => '1', 'version' => $v); 
				}else{
					$ver2 = $this->getMagento2Version($domain);
					// if false return that means none magento site or undetectable
					if($ver2 == false){
						return array('status' => 'success', 'framework' => '0', 'version' =>'unknow');
					}else{
						return array('status' => 'success', 'framework' => '2', 'version' => $ver2);
					}
					
				}
			}
		}

	}

	
	public function getMagento2Version($domain) {

		$url = 'http://' . $domain . '/magento_version';
		
		$objClient = resolve(Client::class);
		
		try{
			$objResponse = $objClient->get($url);
			$strCode = $objResponse->getStatusCode();
			$strBody = $objResponse->getBody()->getContents();
		} catch (ClientException $e) {
			$objResponse = $e->getResponse();
			$strCode = $objResponse->getStatusCode();
			$strBody = $objResponse->getBody()->getContents();
		}

		if($strCode == 404) {
			return false;
		} else {
			$isMage = false;
			$isCE = false;
			$strVer = '';
			$strBody = strtolower($strBody);
			if(preg_match("/magento/", $strBody)){
				$isMage = true;
				if(strpos($strBody, 'community')){
					$isCE = true;
				}
				if(preg_match("/magento\/([^\s]*)/", $strBody, $arrOut)){
					$strVer = $arrOut[1];
				} else {
					$strVer = 'unknow';
				}
				
				$result = $isCE ? 'CE' : 'EE';
				$result = $result . ' ' . $strVer;
				
				return $result;
				
			} else {
				return false;
			}
			
		}
		
		
		
	}

	// return empty str if failed to find version
	// return version str if find version, e.g. "1.9.2.x"
	public function getMagento1VersionByHash($domain){
		if(!$domain){
			return "";
		}

		try {
			$url = Util::addhttp($domain);
			foreach (MagentoVersionMap::load() as $file => $hashs) {
				$strHtml = Util::http_get($url . $file);
				if (!$strHtml){
					return "";
				}

				$md5 = md5($strHtml);
				if (isset($hashs[$md5])){
					$version = $hashs[$md5];
					return $version;
				}
			}
			return "";
		
		} catch (\Exception $e) {
    		return "";
		}
	}

	// return empty str if failed to find version
	// return version str if find version, e.g. "1.9.2.x"
	public function getMagento1VersionByDownloader($domain)
	{
		if(!$domain){
			return "";
		}

		try {
			$url = Util::addhttp($domain);
			$strHtml = Util::http_get($url . $this->downloaderURL);
			if (!$strHtml){
					return "";
			}
			$strHtml = str_replace("\n", "", $strHtml);
			$strHtml = str_replace("\r", "", $strHtml);
			
			if(preg_match("/\(Magento Connect Manager ver.([^\)]*)\)/", $strHtml, $arrOut)){
				return "CE " . trim($arrOut[1]);
			} else {
				return "";
			}
		} catch (\Exception $e) {
    		return "";
		}
	}


	// return empty str if failed to find version
	// return version str if find version, e.g. "1.9.2.x"
	public function getMagento1VersionByCopyright($domain)
	{
		if(!$domain){
			return "";
		}

		try {
				$url = Util::addhttp($domain);
				$code = Util::getUrlCode($url);
				if ((int)$code >= 400){
					return "";
				}
				$strHtml = Util::http_get($url . $this->cssURL);
				if (!$strHtml || strpos($strHtml, 'Magento') == false){
					return "";
				}
				if (strpos($strHtml, '2015') !== false) {
					return "CE 1.9.x.x";
				}elseif (strpos($strHtml, '2014') !== false) {
					return "CE 1.9.x.x";
				}elseif(strpos($strHtml, '2013') !== false) {
					return "CE 1.8.x.x";
				}elseif(strpos($strHtml, '2012') !== false) {
					return "CE 1.7.x.x";
				}elseif(strpos($strHtml, '2011') !== false) {
					return "CE 1.6.x.x";
				}elseif(strpos($strHtml, '2010') !== false) {
					//return "CE 1.4.1.x-1.5.x.x";
					return "CE 1.5.x.x";
				}elseif(strpos($strHtml, '2009') !== false) {
					return "CE 1.4.0.x";
				}elseif(strpos($strHtml, '2008') !== false) {
					return "CE 1.0-1.3";
				}else{
					return "";
				}

		} catch (\Exception $e) {
    		return "";
		}
	}

	
		
}
