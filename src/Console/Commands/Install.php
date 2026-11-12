<?php

namespace ProcessMaker\Package\PackageSlideone\Console\Commands;

use Artisan;
use ProcessMaker\Console\PackageInstallCommand;

class Install extends PackageInstallCommand
{
    protected $signature = 'package-slideone:install';

    protected $description = 'Install SlideOne package';

    public function publishAssets()
    {
        $this->info('Publishing assets');
        Artisan::call('vendor:publish', [
            '--tag' => 'package-slideone',
            '--force' => true,
        ]);
    }

    public function preinstall()
    {
        $this->publishAssets();
    }

    public function install()
    {
    }

    public function postinstall()
    {
    }

    public function handle()
    {
        parent::handle();
        $this->info('SlideOne package has been installed');
    }
}
