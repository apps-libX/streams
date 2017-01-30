<?php

namespace RAD\Streams;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use RAD\Streams\Facades\Streams as StreamsFacade;
use RAD\Streams\Http\Middleware\StreamsAdminMiddleware;
use RAD\Streams\Models\Menu;
use RAD\Streams\Models\User;

class StreamsServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->register(\Intervention\Image\ImageServiceProvider::class);

        $loader = AliasLoader::getInstance();
        $loader->alias('Menu', Menu::class);
        $loader->alias('Streams', StreamsFacade::class);

        $this->app->singleton('streams', function () {
            return Streams::getInstance();
        });

        if ($this->app->runningInConsole()) {
            $this->registerPublishableResources();
            $this->registerConsoleCommands();
        } else {
            $this->registerAppCommands();
        }
    }

    /**
     * Bootstrap the application services.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router)
    {
        if (config('streams.user.add_default_role_on_register')) {
            $app_user = config('streams.user.namespace');
            $app_user::created(function ($user) {
                if (is_null($user->role_id)) {
                    User::findOrFail($user->id)->setRole(config('streams.user.default_role'))
                        ->save();
                }
            });
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'streams');

        $router->middleware('admin.user', StreamsAdminMiddleware::class);
    }

    /**
     * Register the publishable files.
     */
    private function registerPublishableResources()
    {
        $basePath = dirname(__DIR__);
        $publishable = [
            'streams_assets' => [
                "$basePath/publishable/assets" => public_path('vendor/rad/streams/assets'),
            ],
            'migrations' => [
                "$basePath/publishable/database/migrations/" => database_path('migrations'),
            ],
            'seeds' => [
                "$basePath/publishable/database/seeds/" => database_path('seeds'),
            ],
            'demo_content' => [
                "$basePath/publishable/demo_content/" => storage_path('app/public'),
            ],
            'config' => [
                "$basePath/publishable/config/streams.php" => config_path('streams.php'),
            ],
            'views' => [
                "$basePath/publishable/views/" => resource_path('views/vendor/streams'),
            ],
        ];

        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }

    /**
     * Register the commands accessible from the Console.
     */
    private function registerConsoleCommands()
    {
        $this->commands(Commands\InstallCommand::class);
        $this->commands(Commands\ControllersCommand::class);
        $this->commands(Commands\AdminCommand::class);
    }

    /**
     * Register the commands accessible from the App.
     */
    private function registerAppCommands()
    {
        $this->commands(Commands\MakeModelCommand::class);
    }
}
