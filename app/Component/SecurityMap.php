<?php

namespace App\Component; 

class SecurityMap
{
	public static function getVersionMap(){
		return array(
			"9767" => array("CE" => "1.9.3.3", "EE" => "1.14.3.3"),
			"6482" => array("CE" => "1.9.2.1", "EE" => "1.14.2.1"),
			"7405" => array("CE" => "1.9.2.3", "EE" => "1.14.2.3")
			);
	}
		
	public static function load()
	{
			
		return array(
		
			'item1' => array(
				'id' => '5344',
				'item' => 'Shoplift Vulnerability',
				'content' => "Shoplift is a bug in Magento that allows a hacker to take full control of a shop, including stealing customer records and tampering with payments. The leak is fixed by patch SUPEE-5344. <span class='release-date'>Released Feb 9th, 2015.</span>"
				
			),
			
			'item2' => array(
				'id' => '9652',
				'item' => 'Code Execution Vulnerability',
				'content' => "Patch SUPEE-9652 prevents attackers from executing PHP code through a bug in the Zend Framework\'s Sendmail adapter.  This patch cannot be detected from the outside, without hacking your shop. <span class='release-date'>Released Feb 6th, 2017.</span>"
			),
		
			'item3' => array(
				'id' => 'version',
				'item' => 'Magento Version',
				'content' => 'This shows your current Magento version. Magento releases security fixes periodically in all newer versions, after 1.4.0 (Community) and 1.10 (Enterprise).</span>',
			),
			
			'item4' => array(
				'id' => '5994',
				'item' => 'Admin Disclosure',
				'content' => "This shows your current Magento version. Magento releases security fixes periodically in all newer versions, after 1.4.0 (Community) and 1.10 (Enterprise). Released <span class='release-date'>May 14th 2015.</span>",
			),

			'item5' => array(
				'id' => '6285',
				'item' => 'XSS, RSS Attack',
				'content' => "Patch SUPEE-6285 fixes a leak where hackers can take over customer's sessions and download lists of your shop's order details through the RSS feature. Released <span class='release-date'>July 7th, 2015.</span>",
			),
			
			'item6' => array(
				'id' => '6788',
				'item' => 'secrets leak',
				'content' => "Patch SUPEE-6788 fixes multiple issues: in some cases hackers can steal your passwords and customer data. Released <span class='release-date'>October 27th, 2015</span>",
			),

			'item7' => array(
				'id' => '8788',
				'item' => 'High Security Bug',
				'content' => "Patch SUPEE-8788 prevents attackers to create an admin account and execute code. Released <span class='release-date'>Oct 12th, 2016.</span>",
			),

			'item8' => array(
				'id' => '9767',
				'item' => 'Remote Code Execution',
				'content' => "SUPEE-9767 fixes several security issues: remote code execution, information leaking and cross-site scripting. <span class='release-date'>May 31th, 2017.</span>",
			),

			'item9' => array(
				'id' => '6482',
				'item' => "Customer's Session leak",
				'content' => "Patch SUPEE-6285 fixes a leak where hackers can take over customer's sessions and download lists of your shop's order details through the RSS feature. <span class='release-date'>Released July 7th, 2015.</span>",
			),
			'item10' => array(
				'id' => '7405',
				'item' => "Admin Account leak",
				'content' => "Patch SUPEE-7405 resolves several security fixes, but most importantly fixes a leak that allows hackers to take over your admin (backend) account and gain access to your Magento shop. Released <span class='release-date'>Jan 21th, 2016.</span>",
			),

		
		);
	}
		
		
		
}

