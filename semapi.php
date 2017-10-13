<?php 

		$domain = 'https://www.refausa.com';
		
		$semrushKey = 'c7d90d0a2ffccb83776f71e53eae15fd';

		$semrushURL = 'http://api.semrush.com/';

		$auditType = 'domain_ranks';

		$auditRegion = 'us';

		//append key
		$requestURL  = $semrushURL . "?key=" . $semrushKey;

		//append audit type parameter
		$requestURL .= "&type=" . $auditType;

		//apend region parameter 
		$requestURL .= "&database=" . $auditRegion;

		//apend audit website parameter 
		$requestURL .= "&domain=" . $domain;

		echo $requestURL;
		echo file_get_contents($requestURL);
	
		//http://api.semrush.com/?key=c7d90d0a2ffccb83776f71e53eae15fd&type=domain_ranks&export_columns=Db,Dn,Rk,Or,Ot,Oc,Ad,At,Ac&domain=seobook.com&database=us

?>