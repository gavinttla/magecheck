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
class SeoBacklinks extends Seo
{ 

	function __construct() {
		Parent::__construct();
		$this->semrushURL = env('SEMRUSH_BACKLINKS_URL');
	}

	public function getReferringDomains($domain, $requestLimit=10) {
		if(!$domain){
			return array('status'=>'fail','message'=>'invalid parameters');
		}
		try {
			$auditTitle = "Referring Domains";
			$auditType = 'backlinks_refdomains';

			//append key
			$requestURL  = $this->semrushURL . "?key=" . $this->semrushKey;

			//append audit type parameter
			$requestURL .= "&type=" . $auditType;

			//need to look into it
			$requestURL .= "&target_type=root_domain";

			//apend audit website parameter 
			$requestURL .= "&target=" . $domain;

			$requestURL .= "&display_limit=" . $requestLimit;

			$backlink_array = explode("\n", file_get_contents($requestURL));

			$detail = array('item' => $auditTitle);

			$content = "";

			foreach(array_slice($backlink_array, 1, $requestLimit) as $backlink) { //foreach element
				if (!$backlink){
					break;
				}
			    $backlink_data = explode ( ";" , $backlink);
			    	$content .= "<div class='row'><div class='col-xs-6'>" . 
			    	$backlink_data[0] . "</div><div class='col-xs-6'>" . 
			    	$backlink_data[1] . " links </div></div>";
			}

			if (!$content){
				$content = "N/A";
			}

			//update later
			$score = "";
			$id = "";

			$detail["id"] = $id;
			$detail['content'] = $content;
			$detail['score'] = $score;

			return array('status'=>'success',
				'message'=> 'success', 
				'detail' => $detail);
		} catch (\Exception $e) {
    		return array('status'=>'fail','message'=>$e->getMessage());
		}

	}

	public function getBacklinkCount($domain) {
		if(!$domain){
			return array('status'=>'fail','message'=>'invalid parameters');
		}
		try {
			$auditTitle = "Backlinks Overall";
			$auditType = 'backlinks_overview';

			//append key
			$requestURL  = $this->semrushURL . "?key=" . $this->semrushKey;

			//append audit type parameter
			$requestURL .= "&type=" . $auditType;

			//need to look into it
			$requestURL .= "&target_type=root_domain";

			//apend audit website parameter 
			$requestURL .= "&target=" . $domain;

			$backlink = explode("\n", file_get_contents($requestURL));

			$backlink_data = explode ( ";" , $backlink[1]);

			$detail = array('item' => $auditTitle);

			$content = "";

			$content = "<div class='row'><div class='col-xs-6'>" . "Total Backlinks: " . $backlink_data[0] . "</div><div class='col-xs-6'>Follows: " . $backlink_data[3] . "</div></div>";

			if (!$content){
				$content = "N/A";
			}

			//update later
			$score = "";
			$id = "";

			$detail["id"] = $id;
			$detail['content'] = $content;
			$detail['score'] = $score;

			return array('status'=>'success',
				'message'=> 'success', 
				'detail' => $detail);
		} catch (\Exception $e) {
    		return array('status'=>'fail','message'=>'An error has occurred while pulling backlinks.');
		}
	}	
}