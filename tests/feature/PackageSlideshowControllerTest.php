<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use ProcessMaker\Models\Media;
use ProcessMaker\Models\Process;
use ProcessMaker\Models\User;
use ProcessMaker\Package\PackageSlideshow\Models\ProcessSlideshow;
use Tests\TestCase;

class PackageSlideshowControllerTest extends TestCase
{
    private ?User $admin = null;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'is_administrator' => false,
        ]);
        $this->actingAs($this->admin, 'api');
    }

    /**
     * Test the show method
     */
    public function testShow()
    {
        $process = Process::factory()->create();

        $response = $this->getJson(route('api.package.slideshow.images.show', [$process]));

        $response->assertStatus(200);
    }

    /**
     * Test the show method
     */
    public function testShowWithData()
    {
        $process = Process::factory()->create();
        // Create the image
        $image = UploadedFile::fake()->image('image.png', 600, 600)->size(100);
        $customProperties = [
            'node_id' => 'node 34',
            'media_type' => 'slideshow',
            'type' => 'image',
        ];
        $process->addMedia($image)
            ->withCustomProperties($customProperties)
            ->toMediaCollection('images_slideshow');
        // Call the api
        $response = $this->getJson(route('api.package.slideshow.images.show', [$process]));
        // Responses
        $response->assertStatus(200);
        $response->assertNotEmpty($response);
    }

    /**
     * Test the store method
     */
    public function testStore()
    {
        $process = Process::factory()->create();

        $response = $this->getJson(route('api.package.slideshow.images.store', [$process]));

        $response->assertStatus(201);
    }

    /**
     * Test the destroy method
     */
    public function testDestroy()
    {
        $media = Media::factory()->create();

        $response = $this->getJson(route('api.package.slideshow.images.destroy', ['uuid' => $media->uuid]));

        $response->assertStatus(204);
    }

    /**
     * Test store settings
     */
    public function storeSettings()
    {
        $process = Process::factory()->create();

        $response = $this->getJson(route('api.package.slideshow.settings.store', [$process]));

        $response->assertStatus(204);
    }

    /**
     * Test index settings
     */
    public function indexSettings()
    {
        $slideshow = ProcessSlideshow::factory()->create();

        $response = $this->getJson(route('api.package.slideshow.settings.show', [$slideshow]));

        $response->assertStatus(204);
    }

    /**
     * Test update settings
     */
    public function updateSettings()
    {
        $slideshow = ProcessSlideshow::factory()->create();

        $response = $this->getJson(route('api.package.slideshow.settings.update', [$slideshow]));

        $response->assertStatus(204);
    }
}
