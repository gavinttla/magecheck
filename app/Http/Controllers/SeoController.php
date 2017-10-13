<?php

namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Component\Seo;
use App\Component\SeoBacklinks;
use App\Component\Magento;

class SeoController extends Controller
{
	public function process(Request $request)
	{
			
        $validator = Validator::make($request->all(), [
			'domain' => 'required',
			'item' => 'required',
        ]);

       if($validator->fails()){
			return json_encode(array('status'=>'fail','message'=>'invalid parameters.'));
		}
		if(request('item') == "keywords"){
			$objSeo = new Seo();
			$arrResult = $objSeo->topOrganicKeywords(request('domain'));
		}elseif(request('item') == "backlinks"){
			$objSeo = new SeoBacklinks();
			$arrResult = $objSeo->getBacklinkCount(request('domain'));

		}elseif(request('item') == "refdomains"){
			$objSeo = new SeoBacklinks();
			$arrResult = $objSeo->getReferringDomains(request('domain'));

		}elseif(request('item') == "organic_competitors"){
			$objSeo = new Seo();
			$arrResult = $objSeo->competitorsInOrganic(request('domain'));

		}elseif(request('item') == "mixed_content"){
			$objSeo = new Seo();
			return $objSeo->checkMixedContent(request('domain'));
		}
		else{
			$arrResult =array('status'=>'fail','message'=>'invalid item parameter.');
		}

		return json_encode($arrResult);

	}

	

}


