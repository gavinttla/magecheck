<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;


class ScanCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:code {domain1} {domain2}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'scan website and compare the http return code.';

	
	public $objClient = null;
	
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

		$domain1 = $this->argument('domain1');
		$domain2 = $this->argument('domain2');
		
		
		$this->objClient = resolve(Client::class);

		
		$arrResult = array();
		$arrUrl = $this->getRequestList();
		foreach($arrUrl as $id=>$strUrl) {
			$codeA = $this->getCode($domain1, $strUrl);
			$codeB = $this->getCode($domain2, $strUrl);
			
			if($codeA != $codeB) {
				echo "diff url: " . $strUrl . "\n";
			}
		}
		
		
		
    }
	
	
	public function getCode($domain, $url)
	{
		
		$newUrl = 'http://'.$domain . $url;
		try {
			$objResponse = $this->objClient->get($newUrl);				
			$strCode = $objResponse->getStatusCode();
			
		} catch (ClientException $e) {
			$objResponse = $e->getResponse();
			$strCode = $objResponse->getStatusCode();
		}
		
		return $strCode;
			
	}
	
	
	public function getRequestList()
	{
		$arrList = array(
			'/js/webforms/upload/index.php',
			'/js/mage/adminhtml/sales.js',
			'/skin/error.php',
			'/js/webforms/uploaderbro/index.php',
			'/js/prototype/prototype.js',
			'/skin/adminhtml/default/default/media/uploader.swf',
			'/js/lib/ccard.js',
			'/js/prototype/validation.js',
			'/get.php/media/css',
			'/js/scriptaculous/effects.js',
			'/var/resource_config.json',
			'/js/varien/js.js',
			'/index.phprss/catalog/notifystock/',
			'/index.php/xmlconnect/adminhtml_mobile/',
			'/index.php/rss/order/NEW/new',
			'/customer/account/login/',
			'/checkout/cart/',
			'/index.php/qquoteadv/download/downloadCustomOption/',
			'/index.php/ajaxproducts/index/index/',
			'/.git/config',
			'/amfeed/main/download/?file=../../../app/Mage.php',
			'/magmi/web/magmi.php',
			'/dev/tests/functional/etc/config.xml',
			'/skin/adminhtml/default/default/images/wysiwyg/skin_image.png',
			'/checkout/cart/delete',
			'/customer/account/changeforgotten/',
			'/magmi/conf/magmi.ini',
			'/dev/tests/functional/composer.json',
			'/amfeed/app/Mage.php',
			'/.hg/requires',
			'/checkout/cart/',
			'/customer/account/create/',
			'/includes/magmi/web/magmi.php',
			'/checkout/cart/aDD',
			'/checkout/cart/',
			'/checkout/cart/add',
			'/index.php/customer/account/create/',
			'/checkout/cart/'
		);
		
		return $arrList;
	}
	
	
	
}
