<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class testTrain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:train';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $bar = $this->output->createProgressBar(100);
        $bar->setProgressCharacter("\xF0\x9F\x9A\x82");
        $bar->start();

        for ($i=0; $i < 100; $i++) { 
            usleep(100000);
            $bar->advance();
        }

        $bar->finish();
        
    }
}
