<?php


namespace App\Component; 

use App\Component\SecurityMap;
use App\Component\Util;
use GuzzleHttp\Client;
//use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;


/**
 *  
 *  1620.meekn.com  
 *  5994.meekn.com
 *  6285.meekn.com
 *  6482.meekn.com ?
 *  1932.meekn.com
 *  http://2150.meekn.com/

 *  * git push -u origin master
 */
class Security
{

	public function process($strDomain, $strItem, $strFramework, $strVersion)
	{
		$strItem = strtolower($strItem);

		$arrMap = SecurityMap::load();
		
		if(array_key_exists($strItem, $arrMap)) {
			return $this->$strItem($strDomain, $arrMap[$strItem], $strVersion);

		}elseif($strItem == "checkssl"){
			return $this->checkSSL($strDomain);
		}elseif($strItem == "checkadminurl"){
			return $this->checkAdminURL($strDomain, $strFramework);
		}else{
			return array('status'=>'fail','message'=>'invalid item number');
		}
	}

	public function checkAdminURL($strDomain, $strFramework) {
		if(!$strDomain){
			return array('status'=>'fail', 'message'=>"domain not valid");
		}

		$auditTitle = "Backend URL Protection";
		$detail = array('item' => $auditTitle);

		$url = Util::addhttp($strDomain);

		try {
			if ($strFramework == "1"){	
				$adminCode = Util::getUrlCode($url . "/admin");
				$downloaderCode = Util::getUrlCode($url . "/downloader");
				if (($adminCode == '404' ||  $adminCode == '403')
					&& ($downloaderCode == '404' || $downloaderCode == '403')) {
					$score = "safe";
					$id = "";
					$detail['score'] = $score;
					$detail['id'] = $id;
					$detail['content'] = "Backend URL and Downloader URL is Protected.";
				}else{
					$score = "unsafe";
					$id = "";
					$detail['score'] = $score;
					$detail['id'] = $id;
					$detail['content'] = "Backend URL or Downloader URL Not Protected.";
				}
				return array('status'=>'success',
					'message'=> 'success', 
					'detail' => $detail);
			}elseif($strFramework == "2"){
				$adminCode = Util::getUrlCode($url . "/admin");
				if ($adminCode == '404' ||  $adminCode == '403'){
					$score = "safe";
					$id = "";
					$detail['score'] = $score;
					$detail['id'] = $id;
					$detail['content'] = "Backend URL is Protected.";
				}else{
					$score = "unsafe";
					$id = "";
					$detail['score'] = $score;
					$detail['id'] = $id;
					$detail['content'] = "Backend URL Not Protected.";
				}
				return array('status'=>'success',
					'message'=> 'success', 
					'detail' => $detail);

			}else{
				return array('status'=>'fail','message'=>'No Magento 1 or 2 found');
			}
			
		}
		 catch (\Exception $e) {
    		return array('status'=>'fail','message'=>$e->getMessage());
		}

	}

	public function checkSSL($strDomain) {
		if(!$strDomain){
			return array('status'=>'fail', 'message'=>"domain not valid");
		}

		$auditTitle = "SSL Availability";
		$detail = array('item' => $auditTitle);

		try {
			$url = Util::addhttps($strDomain);
			$stream = stream_context_create (array("ssl" => array("capture_peer_cert" => true)));
			$read = fopen($url, "rb", false, $stream);
			$cont = stream_context_get_params($read);
			$var = ($cont["options"]["ssl"]["peer_certificate"]);
			$result = (!is_null($var)) ? true : false;

			//update later
			$score = "";
			$id = "";

			$detail['id'] = $id;
			if ($result){
				$score = "safe";
				$detail['score'] = $score;
				$detail['id'] = $id;
				$detail['content'] = "HTTPS Connection is OPEN on your site.";
			}else{
				$score = "unsafe";
				$detail['score'] = $score;
				$detail['id'] = $id;
				$detail['content'] = "We didn't detect HTTPS Connection on your site.";
			}
			

			return array('status'=>'success',
				'message'=> 'success', 
				'detail' => $detail);
		} catch (\Exception $e) {
    			//update later
			$score = "unsafe";
			$id = "";
			$detail['score'] = $score;
			$detail['id'] = $id;
			$detail['content'] = "We didn't detect HTTPS Connection on your site.";

			return array('status'=>'success',
				'message'=> 'success', 
				'detail' => $detail);
		}
	}
	
	/**
	 *  for bug/patch 5344
	 */
	public function item1($strDomain, $arrDetail, $strVer = '') 
	{
		$strAdmin = 'admin';
		//$strAdmin = 'supply3dadmin';
		//$strDomain = 'starlite-inc.net'; admin | warning
		$url = 'https://magento.com/security-patch-check/' . $strDomain . '/' . $strAdmin;
		
		$objClient = resolve(Client::class);
		$objResult = $objClient->get($url);
		
		$strBody = $objResult->getBody()->getContents();
		
		/*
			(NOTE: DON'T DELETE THIS COMMENTS)
			the api have 4 possible return
			error: unknow
			ok: safe
			warning: unsafe
			{"status":"error","message":"ERROR: I could not find an admin panel there. A hidden panel is slightly safer but no guarantee, as hackers can use brute force to find your panel."}
			{"status":"error","message":"ERROR: I could not connect to that server. Please double-check for typos."}
			{"status":"ok","message":"SAFE: This site appears to be safe."}
			{"status":"warning","message":"WARNING: This site appears to be vulnerable. Please patch it immediately!"}
		*/
	
		$arrBody = json_decode($strBody, true);
		
		$strResult = '';
		if($arrBody['status'] == 'ok') {
			$strResult = 'safe';
		} elseif($arrBody['status'] == 'warning') {
			$strResult = 'unsafe';
		} else {
			$strResult = 'unknown';
		}
		
		$arrDetail['score'] = $strResult;
		
		return array('status'=>'success', 'detail'=>$arrDetail);

	}
	
	/**
	 *  check patch for 6482
	 */	
	public function item2($strDomain, $arrDetail, $strVer = '') 
	{
		$url = 'http://' . $strDomain . '/index.php/api/soap/';
		
		$objClient = resolve(Client::class);
		
		try{
			$objResult = $objClient->post($url, array(
				'headers' => array(
					'Authorization' => 'Basic bG9jYWxob3N0LzpjaGVja3B3ZA==',
					'Accept-Encoding' => 'gzip, deflate, sdch',
					'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp;q=0.8',
					'Content-Type' => 'application/x-www-form-urlencoded',					
			)));
			$strCode = $objResult->getStatusCode();
			$strBody = $objResult->getBody->getContents();

		} catch (RequestException $e) {
			$objResponse = $e->getResponse();
			$strCode = $objResponse->getStatusCode();
			$strBody = $objResponse->getBody()->getContents();
		}
		
		$strBody = Util::strip($strBody);
		$strBody = strtolower($strBody);
	
		if($strCode == 500){
			if(preg_match("/localhost/", $strBody)){
				$strResult = "unsafe";
			} else {
				$strResult = "safe";
			}
		} else {
			$strResult = "unknown";
		}

		$arrDetail['score'] = $strResult;
		
		return array('status'=>'success', 'detail'=>$arrDetail);
	}
	
	/**
	 *  this function get the magento version
	 */
	public function item3($strDomain, $arrDetail, $strVer = '') 
	{
		$url = 'http://' . $strDomain . '/downloader/';
		
		$objClient = resolve(Client::class);
		$objResult = $objClient->get($url);
		
		$strBody = $objResult->getBody()->read(9024);
		
		$strVer = Util::getVersion($strBody);
		
		if($strVer === false)$strVer = 'unknown';
		
		$arrDetail['score'] = $strVer;
		
		return array('status'=>'success', 'detail'=>$arrDetail);

	}
	
	
	/**
	 *  check patch for 5994
	 */
	public function item4($strDomain, $arrDetail, $strVer = '') 
	{

		$url = 'http://' . $strDomain . '/index.php/xmlconnect/adminhtml_mobile/';
		
		$strCode = Util::getUrlCode($url);
		
		$strResult = '';
		if($strCode == 200) {
			$strResult = 'unsafe';
		} elseif($strCode == 404) {
			$strResult = 'safe';
		} else {
			$strResult = 'unknown';
		}

		$arrDetail['score'] = $strResult;
		
		return array('status'=>'success', 'detail'=>$arrDetail);
		
	}
	
	/**
	 *  check patch for 6285
	 */
	public function item5($strDomain, $arrDetail, $strVer = '') 
	{
		
		$url = 'http://' . $strDomain . '/index.php/rss/order/NEW/new';
				
		$strCode = Util::getUrlCode($url);
				
		$strResult = '';
		if($strCode == 401) {
			$strResult = 'safe';
		} elseif($strCode == 200) {
			$strResult = 'unsafe';
		} else {
			$strResult = 'unknown';
		}
		
		$arrDetail['score'] = $strResult;
		
		return array('status'=>'success', 'detail'=>$arrDetail);
		
	}	
	
	/**
	 *  check patch for 6788
	 */
	public function item6($strDomain, $arrDetail, $strVer = '') 
	{
		
		$url1 = 'http://' . $strDomain . '/dev/tests/functional/etc/config.xml';
		$url2 = 'http://' . $strDomain . '/customer/account/changeforgotten/';
		$url3 = 'http://' . $strDomain . '/dev/tests/functional/composer.json';
		
		$strCode1 = Util::getUrlCode($url1);
		$strCode2 = Util::getUrlCode($url2);
		$strCode3 = Util::getUrlCode($url3);
		
		if ($strCode1 == 403 && $strCode2 == 402 && $strCode3 == 403) {
			$strResult = 'safe';
		} else {
			$strResult = 'unsafe';
		}

		$arrDetail['score'] = $strResult;
		
		return array('status'=>'success', 'detail'=>$arrDetail);
		
	}	
	
	/**
	 *  check patch for 8788
	 */
	public function item7($strDomain, $arrDetail, $strVer = '') 
	{
		
		$url1 = 'http://' . $strDomain . '/skin/adminhtml/default/default/media/uploader.swf';
		$url2 = 'http://' . $strDomain . '/index.php/rss/catalog/notifystock/';
		$url3 = 'http://' . $strDomain . '/index.php/rss/order/NEW/new';
		
		$strCode1 = Util::getUrlCode($url1);
		$strCode2 = Util::getUrlCode($url2);
		$strCode3 = Util::getUrlCode($url3);
		
		// echo $strCode1 . "|" . $strCode2 . "|" . $strCode3; exit;
		
		if ($strCode1 == 404 && $strCode2 == 404 && $strCode3 == 404) {
			$strResult = 'safe';
		} else {
			$strResult = 'unsafe';
		}
		
		$arrDetail['score'] = $strResult;
		
		return array('status'=>'success', 'detail'=>$arrDetail);
		
	}	

	/**
	 *  share function that check patch base on version value
	 */
	private function checkVersionSafe($strId, $strVersion)
	{
		$arrVerSafe = SecurityMap::getVersionMap();
		$arrVer = $arrVerSafe[$strId];
		
		$strTemp = preg_replace("/\s+/", "##", $strVersion);
		$arrTemp = explode("##", $strTemp);
		if(!is_array($arrTemp)){
			return 'unknown';
		}
		
		$strType = strtoupper($arrTemp[0]);
		if($strType != 'EE'){
			$strType = 'CE';
		}
		
		$strVer = $arrVer[$strType];
		if(version_compare($arrTemp[1], $strVer)>=0){
			return 'safe';
		}else{
			return 'unsafe';
		}
	}
	
	public function item8($strDomain, $arrDetail, $strVer = '')
	{
		$strId = $arrDetail['id'];
		$strResult = $this->checkVersionSafe($strId, $strVer);
		
		$arrDetail['score'] = $strResult;
		
		return array('status'=>'success', 'detail'=>$arrDetail);		
	}
	
	
	public function item9($strDomain, $arrDetail, $strVer = '')
	{
		$strId = $arrDetail['id'];
		$strResult = $this->checkVersionSafe($strId, $strVer);
		
		$arrDetail['score'] = $strResult;
		
		return array('status'=>'success', 'detail'=>$arrDetail);		
	}

	public function item10($strDomain, $arrDetail, $strVer = '')
	{
		$strId = $arrDetail['id'];
		$strResult = $this->checkVersionSafe($strId, $strVer);
		
		$arrDetail['score'] = $strResult;
		
		return array('status'=>'success', 'detail'=>$arrDetail);		
	}
		
}