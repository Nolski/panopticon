<?php

namespace App\Console\Commands;

use App\Sync\MatchScoresSync;
use Illuminate\Console\Command;

class SyncMatchScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:match_scores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Match Scores';

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
        try {
            app(MatchScoresSync::class)->make($this);

            $this->info('Sync Match Scores');
        } catch (\Throwable $e) {
            $this->error($e->getMessage() . ', Line: ' . $e->getLine() . ', File: ' . $e->getFile());
        }
    }
}
