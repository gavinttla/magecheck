<?php


namespace App\Component; 

use App\Component\SecurityMap;
use App\Component\Util;
use GuzzleHttp\Client;


/**
 * This class use to scan a website and out put to temp file  
 *  
 *  
 */
class ScanSecurity
{
		
	public function process($strDomain, $strPatch)
	{
		
		$arrSetting = $this->getPatchSetting($strPatch);
		
		$outputFile = storage_path() . '/audit/' . $strPatch . "_" . $strDomain . "_" . date("md-His") . ".txt";

		$url = "http://" . $strDomain;
		
		$objClient = new Client();
		
		if($arrSetting['method'] == 'get'){
			$url .= $arrSetting['url'];
			$objResult = $objClient->get($url);
			$strBody = $objResult->getBody()->getContents();
			file_put_contents($outputFile, $strBody);
		
		} elseif($arrSetting['method'] == 'post') {
				
				
				
		}
		
		
		echo "done get page, check file: " . $outputFile . "\n";
		

	}
	
	
	public function getPatchSetting($strPatch) 
	{
		$arrAllPath = $this->getAllPatch();
		
		if(!array_key_exists($strPatch, $arrAllPath)){
			throw new \Exception('Patch key not exist');
		}
		
		return $arrAllPath[$strPatch];

	}
	
	
	public function getAllPatch()
	{
		return array(
			'item1' => array(
				'method' => 'get',
				'url'	=> '/',
				
			),
		
			'item2' => array(
			
			),
		
		
		);
	}
	
	
}