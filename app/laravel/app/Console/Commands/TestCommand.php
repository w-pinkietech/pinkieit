<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test
                            {--without-tty : Disable output to TTY}
                            {--coverage : Indicates whether code coverage information should be collected}
                            {--min= : Indicates the minimum threshold enforcement for code coverage}
                            {--filter= : Filter which tests to run}
                            {--testsuite= : Specify which test suite to run}
                            {--stop-on-failure : Stop execution upon first error or failure}
                            {--stop-on-error : Stop execution upon first error}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the application tests';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $options = [];

        if ($this->option('coverage')) {
            $options[] = '--coverage-html=coverage';
        }

        if ($this->option('min')) {
            $options[] = '--coverage-clover=coverage.xml';
        }

        if ($this->option('filter')) {
            $options[] = '--filter=' . $this->option('filter');
        }

        if ($this->option('testsuite')) {
            $options[] = '--testsuite=' . $this->option('testsuite');
        }

        if ($this->option('stop-on-failure')) {
            $options[] = '--stop-on-failure';
        }

        if ($this->option('stop-on-error')) {
            $options[] = '--stop-on-error';
        }

        $options = implode(' ', $options);

        $command = "php vendor/bin/phpunit {$options}";

        if ($this->option('without-tty')) {
            $result = shell_exec($command);
            $this->output->write($result);
            return 0;
        }

        passthru($command, $result);

        return $result;
    }
}