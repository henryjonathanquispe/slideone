<?php

namespace ProcessMaker\Package\PackageSlideshow\Console\Commands;

use Artisan;
use ProcessMaker\Console\PackageInstallCommand;

class Install extends PackageInstallCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package-slideshow:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Package Slideshow Package';

    public const DEFAULT_EMAIL_SLIDESHOW_NOTIFICATION = 'DEFAULT_EMAIL_SLIDESHOW_NOTIFICATION';

    /**
     * Publish assets
     * @return void
     */
    public function publishAssets()
    {
        $this->info('Publishing assets');
        Artisan::call('vendor:publish', [
            '--tag' => 'package-slideshow',
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
        // Create a default email screen
        $this->info('Creating Default Email Screen...');
        $jsonFilePath = __DIR__ . '/../../../resources/json/default_email_slideshow_notification.json';
        $created = $this->updateOrCreateDefaultEmailScreen($jsonFilePath);
        $message = $created
            ? 'Default Email Screen has been created or updated.'
            : 'Default Email Screen has not been created or updated.';
        $this->info($message);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        parent::handle();
        $this->info('Package Slideshow has been installed');
    }

    /**
     * Update or create default screen
     * @return void
     */
    public function updateOrCreateDefaultEmailScreen($jsonFilePath)
    {
        $updated = false;

        if (file_exists($jsonFilePath)) {
            $templateJson = file_get_contents($jsonFilePath);

            Screen::updateOrCreate([
                'title' => self::DEFAULT_EMAIL_SLIDESHOW_NOTIFICATION,
            ], [
                'screen_category_id' => 1,
                'description' => 'Template for Default Email Slideshow Notification',
                'type' => 'EMAIL',
                'config' => json_decode($templateJson, true),
            ]);

            $updated = true;
        }

        return $updated;
    }
}
