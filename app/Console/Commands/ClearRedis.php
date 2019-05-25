<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ClearRedis extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'redis:clear';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Clear keys of redis.';

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
        $keyName = $this->argument('key');

        $host = $this->input->getOption('host');

        $port = $this->input->getOption('port');

        passthru('redis-cli -h '.$host.' -p '.$port.' keys "'.$keyName.'" | xargs redis-cli -h '.$host.' -p '.$port.' del');

        $this->info('RedisServer keys cleared!');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
            ['key', InputArgument::OPTIONAL, 'The key name of the redis you would like to clear.', "*"]
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
        return array(
            array('host', null, InputOption::VALUE_OPTIONAL, 'The host address to redis server.', 'localhost'),

            array('port', null, InputOption::VALUE_OPTIONAL, 'The port to serve the application on.', 6379),
        );
	}

}
