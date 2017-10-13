<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Component\ScanSecurity;

class Scan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:website {domain} {patchkey}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command used to scan/test website vulnerability';

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
		
		$strPath = storage_path();
		
		$objScan = new ScanSecurity();
		
		$strDomain = $this->argument('domain');
		$strPatch = $this->argument('patchkey');
		
		//echo date("Y-m-d H:i:s") . "\n";
		$objScan->process($strDomain, $strPatch);
		
		
		
    }
}
