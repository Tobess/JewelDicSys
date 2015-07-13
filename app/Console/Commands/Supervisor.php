<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Supervisor extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'supervisor';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check supervisor service is on, or not to up it.';

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
	public function fire()
	{
        passthru('ps -A | grep -v grep | grep supervisord', $result);
        if (!strstr($result, 'supervisord')) {
            //passthru('supervisord', $result);
        }

        $this->info($result);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			//['example', InputArgument::REQUIRED, 'An example argument.'],
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
			//['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}
