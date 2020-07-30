<?php

namespace BreakingBad\Console\Commands;

use Illuminate\Console\Command;
use Lifecycle\CharacterLifecycle;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:characters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync new characters from breaking bad';

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
     * @return int
     */
    public function handle()
    {
        CharacterLifecycle::sync();
        return 0;
    }
}
