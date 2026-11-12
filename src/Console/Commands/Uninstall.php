<?php

namespace ProcessMaker\Package\PackageSlideone\Console\Commands;

use Illuminate\Console\Command;

class Uninstall extends Command
{
    protected $signature = 'package-slideone:uninstall';

    protected $description = 'Uninstall SlideOne package';

    public function handle()
    {
        $this->info('SlideOne package uninstalled');
    }
}
