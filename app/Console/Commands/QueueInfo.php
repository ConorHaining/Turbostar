<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class QueueInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View the current queue stats';

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
        $headers = ['Queue Name', 'Items in Queue'];

        $jobs = DB::table('jobs')
            ->selectRaw('DISTINCT(queue), COUNT(queue)')
            ->groupBy('queue')
            ->get()
            ->toArray();
        
        $rows = [];

        foreach ($jobs as $value) {
            $row = [$value->queue, $value->{'COUNT(queue)'}];
            array_push($rows, $row);
        }

        $this->table($headers, $rows);
    }
}