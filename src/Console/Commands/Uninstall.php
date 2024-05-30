<?php

namespace ProcessMaker\Package\PackageSlideshow\Console\Commands;

use Illuminate\Console\Command;

class Uninstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package-slideshow:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall Package Slideshow Package';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Package Slideshow package Uninstalled');
    }
}
