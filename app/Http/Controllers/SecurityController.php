<?php

namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Component\Security;
use App\Component\Util;

use Dompdf\Dompdf;

class SecurityController extends Controller
{
    //
	
	public function index()
	{
		//return 'here';
		return view('security.index');
	}

	
	public function show($domain)
	{
		
		return view('security.show');
	}
	
	/**
	 *  this is the main function entry to process security patch check
	 *  
	 */
	public function process(Request $request)
	{
			
        $validator = Validator::make($request->all(), [
			'domain' => 'required',
			'item' => 'required',
        ]);
		
		if($validator->fails()){
			return json_encode(array('status'=>'fail','message'=>'invalid parameters'));
		}
		
		$objSecurity = new Security();
		
		$arrResult = $objSecurity->process(request('domain'), 
			request('item'),
			request('framework'),
			request('version'));
		
		return json_encode($arrResult);
		
	}
	


	public function testdb()
	{

		// get all records from emails table
		//$arrRecords = DB::table('emails')->get();
		
		$path = storage_path();
		
		dd($path);

		// instantiate and use the dompdf class
		$dompdf = new Dompdf();
		$dompdf->loadHtml('hello world');
		
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'landscape');
		
		// Render the HTML as PDF
		$dompdf->render();
		
		// Output the generated PDF to Browser
		$dompdf->stream();
		
		
		return $arrRecords;

	}

	
	/**
	 *  put in respond and validation for form submission
	 */
	public function saveData(Request $request)
	{
		
        $validator = Validator::make($request->all(), [
			'domain' => 'required',
			'email' => 'required',
        ]);
		
		if($validator->fails()){
			return json_encode(array('status'=>'fail','message'=>'invalid parameters'));
		}
		
		$email = request('email');
		$domain = request('domain');
		
		try{
			$result = DB::table('emails')->insert([
				['email' => $email, 'domain' => $domain, 'ipaddress'=>Util::getClientIp(), 'created_at'=>date('Y-m-d H:i:s')]
			]);
		} catch(QueryException $e) {
			return json_encode(array('status'=>'fail','message'=>$e->getMessage()));
		}
		
		if($result) {
			return json_encode(array('status'=>'success'));
		}

	}

	/**
	 * generate a report
	 */
	public function createReport(Request $request)
	{
        $validator = Validator::make($request->all(), [
			'html' => 'required',
			'domain' => 'required',
        ]);
		
		if($validator->fails()){
			return json_encode(array('status'=>'fail','message'=>'invalid parameters'));
		}
		
		$html = request('html');
		$domain = request('domain');
		
		// need to save the html to file for future use.
		$fileName = date('Y-m-d_H:i:s') . '_' . $domain . ".html";
		$filepath = storage_path() . '/report/' . $fileName;
		file_put_contents($filepath, $html);
		
		// instantiate and use the dompdf class
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		
		// (Optional) Setup the paper size and orientation
		//$dompdf->setPaper('A4', 'landscape');
		
		// Render the HTML as PDF
		$dompdf->render();
		
		// Output the generated PDF to Browser
		$dompdf->stream();
		
		exit;	
	}
	
	
	
}


