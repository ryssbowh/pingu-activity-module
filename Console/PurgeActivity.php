<?php

namespace Pingu\Activity\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Activity;

class PurgeActivity extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'activity:purge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all entries in activities table.';

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
        Activity::purge();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
        ];
    }
}
