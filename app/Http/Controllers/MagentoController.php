<?php

namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Component\Seo;
use App\Component\SeoBacklinks;
use App\Component\Magento;
use App\Component\Util;

class MagentoController extends Controller
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
		if(request('item') == "version"){
			$objMagento = new Magento();
			$arrResult = $objMagento->getMagentoVersion(request('domain'));
			$this->storeRequest(request('domain'));
			return json_encode($arrResult);
		}
		else{
			$arrResult =array('status'=>'fail','message'=>'invalid item parameter.');
		}

		return json_encode($arrResult);

	}
	
	public function storeRequest($domain)
	{

		try{
			$result = DB::table('requests')->insert([
				['domain' => $domain, 'ipaddress'=>Util::getClientIp(), 'created_at'=>date('Y-m-d H:i:s')]
			]);
		} catch(QueryException $e) {
			//return json_encode(array('status'=>'fail','message'=>$e->getMessage()));
			// if store request fail do nothing
		}

		
	}



}


