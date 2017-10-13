<?php


namespace App\Component; 

use App\Component\SecurityMap;
use App\Component\Util;
use GuzzleHttp\Client;


/**
 *   * 
 *  * 
 *  * git push -u origin master
 */
class Seo
{
	protected $semrushKey;
  	protected $semrushURL;

  	protected $excludedCompetitors = array("amazon.com", 
  		"facebook.com", 
  		"ebay.com",
  		"yelp.com",
  		"google.com",
  		"youtube.com",
  		"yellowpages.com");



	function __construct() {

		$this->semrushKey = env('SEMRUSH_KEY');
		$this->semrushURL = env('SEMRUSH_URL');
	}

	public function topOrganicKeywords($domain, 
		$requestLimit = 5, $auditRegion = "us")
	{
		if(!$domain){
			return array('status'=>'fail','message'=>'invalid domain while pulling keywords.');
		}

		try {
			$auditTitle = "Top " . strval($requestLimit) . " Organic Keywords";
			$auditType = 'domain_organic';

			//append key
			$requestURL  = $this->semrushURL . "?key=" . $this->semrushKey;

			//append audit type parameter
			$requestURL .= "&type=" . $auditType;

			//apend region parameter 
			$requestURL .= "&database=" . $auditRegion;

			//apend audit website parameter 
			$requestURL .= "&domain=" . $domain;

			$requestURL .= "&display_limit=" . $requestLimit;

			$keyword_array = explode("\n", file_get_contents($requestURL));


			$detail = array('item' => $auditTitle);

			$content = "";

			foreach(array_slice($keyword_array, 1, $requestLimit) as $keyword) { //foreach element
			    $keyword_data = explode ( ";" , $keyword);
			    	$content .= "<div class='row'><div class='col-xs-4'>" . 
			    	$keyword_data[0] . "</div><div class='col-xs-4'> Position: " .
			    	$keyword_data[1] . "</div><div class='col-xs-4'> Traffic (%) : " .
			    	$keyword_data[4] . "</div></div>";
			}

			if (!$content){
				$content = "N/A";
			}
			//update later
			$score = "";
			$id = "";

			$detail['id'] = $id;
			$detail['content'] = $content;
			$detail['score'] = $score;


			return array('status'=>'success',
				'message'=> 'success', 
				'detail' => $detail);
		} catch (\Exception $e) {
    		return array('status'=>'fail','message'=>'An error has occurred while pulling keywords.');
		}
	}

	public function competitorsInOrganic ($domain, 
		$requestLimit = 12, $returnLimit = 5, $auditRegion = "us") {

		if(!$domain){
			return array('status'=>'fail','message'=>'invalid domain while pulling competitors.');
		}

		try {
			$auditTitle = "Top Organic Competitors";
			$auditType = 'domain_organic_organic';


			//append key
			$requestURL  = $this->semrushURL . "?key=" . $this->semrushKey;

			//append audit type parameter
			$requestURL .= "&type=" . $auditType;

			//apend region parameter 
			$requestURL .= "&database=" . $auditRegion;

			//apend audit website parameter 
			$requestURL .= "&domain=" . $domain;

			$requestURL .= "&display_limit=" . $requestLimit;

			$requestURL .=  "&export_columns=Dn,Cr,Np,Or,Ot,Oc,Ad";

			$competitor_array = explode("\n", file_get_contents($requestURL));

			$detail = array('item' => $auditTitle);

			$content = "";

			$count = 0;
			foreach(array_slice($competitor_array, 1, $requestLimit) as $competitor) { //foreach element
			    $competitor_data = explode ( ";" , $competitor);
			    if (! in_array($competitor_data[0], $this->excludedCompetitors)
			    	&& $count < $returnLimit ){
				    	$content .=  "<div class='row'><div class='col-xs-3'>" . 
				    	$competitor_data[0] . 
				    	"</div><div class='col-xs-3'>" .
				    	" common keywords: " . $competitor_data[2] . 
				    	"</div><div class='col-xs-3'>" .
				    	" organic keywords: " . $competitor_data[3] .
				    	"</div><div class='col-xs-3'>" .
				    	" organic traffic: " . $competitor_data[4] . 
				    	"</div></div>";
				    	$count += 1;
			    }
			}

			if (!$content){
				$content = "N/A";
			}

			//update later
			$score = "";
			$id = "";

			$detail['id'] = $id;
			$detail['content'] = $content;
			$detail['score'] = $score;


			return array('status'=>'success',
				'message'=> 'success', 
				'detail' => $detail);
		} catch (\Exception $e) {
    		return array('status'=>'fail','message'=>'An error has occurred while pulling competitors.');
		}

	}

	public function checkMixedContent($strDomain){
		if(!$strDomain){
			return array('status'=>'fail', 'message'=>"domain not valid");
		}

		$auditTitle = "HTTP/HTTPS Mixed Content on Pages";
		$detail = array('item' => $auditTitle);

		try {
			$url = Util::addhttp($strDomain);
			$strHtml = Util::http_get($url);
			$arr_links = Util::getPageHref($strHtml);;
			$numHttp = 0;
			$numHttps = 0;
			foreach($arr_links as $link){
				if (substr($link, 0, 5) === "https"){
					$numHttps += 1;
				}elseif (substr($link, 0, 4) === "http") {
					$numHttp += 1;
				}	
			}
			if ($numHttp == 0 || $numHttps == 0){
				$score = "";
				$id = "";
				$detail['score'] = $score;
				$detail['id'] = $id;
				$detail['content'] = "NO HTTP/HTTPS Mixed Content on Pages";
				return array('status'=>'success',
					'message'=> 'success', 
					'detail' => $detail);
			}else{
				$score = "";
				$id = "";
				$detail['score'] = $score;
				$detail['id'] = $id;
				$detail['content'] = $detail['content'] = "NO HTTP/HTTPS Mixed Content on Pages (HTTP:" . 
				$numHttp. " | HTTPS:". $numHttps .")";
				return array('status'=>'success',
						'message'=> 'success', 
						'detail' => $detail);
			}
		}catch (\Exception $e) {
    		return array('status'=>'fail','message'=>$e->getMessage());
		}

	}
		
}