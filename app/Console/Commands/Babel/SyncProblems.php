<?php

namespace App\Console\Commands\Babel;

use Illuminate\Console\Command;
use App\Babel\Babel;
use Exception;
use function GuzzleHttp\json_decode;

class SyncProblems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'babel:syncpro {extension : The package name of the extension} {--vcid= : The target contest of the Crawler} {--gid= : The holding group} {--cid= : The contest in NOJ}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl contests for a given Babel Extension to NOJ';

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
        $extension = $this->argument('extension');
        $vcid = $this->option('vcid');
        $gid = $this->option('gid');
        $cid = $this->option('cid');
        $className = "App\\Babel\\Extension\\$extension\\Synchronizer";
        $all_data = [
            'oj'=>$extension,
            'vcid'=>$vcid,
            'gid'=>$gid,
            'cid'=>$cid,
        ];
        $Sync = new $className($all_data);
        $Sync->scheduleCrawl();
    }
}
