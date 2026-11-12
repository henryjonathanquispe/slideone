<?php

namespace ProcessMaker\Package\PackageSlideone;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ProcessMaker\Package\Packages\Events\PackageEvent;
use ProcessMaker\Package\PackageSlideone\Listeners\PackageListener;
use ProcessMaker\Traits\PluginServiceProviderTrait;

class PackageServiceProvider extends ServiceProvider
{
    use PluginServiceProviderTrait;

    protected $namespace = '\ProcessMaker\Package\PackageSlideone\Http\Controllers';

    public function register()
    {
    }

    public function boot()
    {
        $this->commands([
            Console\Commands\Install::class,
            Console\Commands\Uninstall::class,
        ]);

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'package-slideone');

        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/processmaker/packages/package-slideone'),
        ], 'package-slideone');

        $this->app['events']->listen(PackageEvent::class, PackageListener::class);

        $this->registerModelerScript(
            'js/modeler-slideone-menu.js',
            'vendor/processmaker/packages/package-slideone'
        );
        $this->completePluginBoot();
        $this->loadJsonTranslationsFrom(__DIR__ . '/../resources/lang');
    }
}
