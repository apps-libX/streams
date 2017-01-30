<?php

namespace RAD\Streams\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageServiceProviderLaravel5;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;
use RAD\Streams\Traits\Seedable;
use RAD\Streams\StreamsServiceProvider;

class InstallCommand extends Command
{
    use Seedable;

    protected $seedersPath = __DIR__.'/../../publishable/database/seeds/';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'streams:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Streams Admin package';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function getOptions()
    {
        return [
            ['with-dummy', null, InputOption::VALUE_NONE, 'Install with dummy data', null],
        ];
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd().'/composer.phar')) {
            return '"'.PHP_BINARY.'" '.getcwd().'/composer.phar';
        }

        return 'composer';
    }

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return void
     */
    public function fire(\Illuminate\Filesystem\Filesystem $filesystem)
    {
        $this->info('Publishing the Streams assets, database, and config files');
        $this->call('vendor:publish', ['--provider' => StreamsServiceProvider::class]);
        $this->call('vendor:publish', ['--provider' => ImageServiceProviderLaravel5::class]);

        $this->info('Migrating the database tables into your application');
        $this->call('migrate');

        $this->info('Dumping the autoloaded files and reloading all new files');

        $composer = $this->findComposer();

        $process = new Process($composer.' dump-autoload');
        $process->setWorkingDirectory(base_path())->run();

        $this->info('Adding Streams routes to routes/web.php');
        $filesystem->append(base_path('routes/web.php'),
            "\n\nRoute::group(['prefix' => 'admin'], function () {\n    Streams::routes();\n});\n");

        $this->info('Seeding data into the database');
        $this->seed('StreamsDatabaseSeeder');

        if ($this->option('with-dummy')) {
            $this->seed('StreamsDummyDatabaseSeeder');
        }

        $this->info('Adding the storage symlink to your public folder');
        $this->call('storage:link');

        $this->info('Successfully installed Streams! Enjoy 🎉');
    }
}
