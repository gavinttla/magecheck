<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;


class ScanVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:version {domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'scan magento version';

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
        //
		$objClient = resolve(Client::class);
		
		$url = 'https://1620.meekn.com/';
		//$url = 'https://www.oodda.com/';
		
		try{
			$objResponce = $objClient->get($url);	
		} catch (ClientException $e) {
			$objResponse = $e->getResponse();
			$strCode = $objResponse->getStatusCode();
		}
		
		
		var_dump($objResponce);
		
		
    }
}
