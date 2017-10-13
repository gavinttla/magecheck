<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScanPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:post {domain1} {domain2}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send post request to 2 domains, and compare with respond code';

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
		$domain1 = $this->argument('domain1');
		$domain2 = $this->argument('domain2');
		
		echo base64_decode('bG9jYWxob3N0LzpNYWdlUmVwb3J0IFNVUEVFIDY0ODIgY2hlY2s=');

    }
	
	
	public function test1($domain1)
	{
		
		
		$objClient = resolve(Client::class);
		//$objClient->post
		
	}
}
