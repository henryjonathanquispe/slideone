<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use ProcessMaker\Models\Media;
use ProcessMaker\Models\Process;
use ProcessMaker\Models\User;
use ProcessMaker\Package\PackageSlideshow\Models\ProcessSlideshow;
use Tests\Feature\Shared\RequestHelper;
use Tests\TestCase;

class PackageSlideshowControllerTest extends TestCase
{
    use RequestHelper;

    private ?User $admin = null;

    const API_IMAGES_URL = '/slideshow/images';

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
        // Call the api GET
        $response = $this->apiCall('GET',self::API_IMAGES_URL .'/'. $process->id);
        // Responses
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
        $response = $this->apiCall('GET', self::API_IMAGES_URL .'/'. $process->id);
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

        $response = $this->apiCall('PUT', self::API_IMAGES_URL .'/'. $process->id);

        $response->assertStatus(201);
    }

    /**
     * Test the destroy method
     */
    public function testDestroy()
    {
        $media = Media::factory()->create();

        $response = $this->apiCall('DELETE', 'images', ['uuid' => $media->uuid]);

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
