<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use ProcessMaker\Models\Media;
use ProcessMaker\Models\Process;
use ProcessMaker\Models\User;
use ProcessMaker\Package\PackageSlideshow\Models\ProcessSlideshow;
use Tests\Feature\Shared\RequestHelper;
use Tests\TestCase;

class PackageSlideshowControllerTest extends TestCase
{
    use RequestHelper;

    protected $anonymousUser;
    protected $user;

    const API_IMAGES_URL = '/slideshow/images';

    /**
     * Setup
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'password' => Hash::make('password'),
            'is_administrator' => true,
        ]);
        $this->actingAs($this->user);
    }

    /**
     * Test the show method
     */
    public function testShow()
    {
        $process = Process::factory()->create();
        // Call the api
        $response = $this->apiCall('GET', self::API_IMAGES_URL .'/'. $process->id);
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
    }

    /**
     * Test the store method
     */
    public function testStore()
    {
        $process = Process::factory()->create();
        // Call the api
        $response = $this->apiCall('PUT', self::API_IMAGES_URL .'/'. $process->id);
        // Responses
        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'imageSlideshow',
            'imageSlideshow.url',
            'imageSlideshow.node_id',
        ]);
    }

    /**
     * Test the destroy method
     */
    public function testDestroy()
    {
        $media = Media::factory()->create();
        // Call the api
        $response = $this->apiCall('DELETE', self::API_IMAGES_URL .'/'. $media->uuid);
        // Responses
        $response->assertStatus(204);
    }

    /**
     * Test store settings
     */
    public function storeSettings()
    {
        $process = Process::factory()->create();
        // Call the api
        $response = $this->getJson(route('api.package.slideshow.settings.store', [$process]));
        // Responses
        $response->assertStatus(204);
    }

    /**
     * Test index settings
     */
    public function indexSettings()
    {
        $slideshow = ProcessSlideshow::factory()->create();
        // Call the api
        $response = $this->getJson(route('api.package.slideshow.settings.show', [$slideshow]));
        // Responses
        $response->assertStatus(204);
    }

    /**
     * Test update settings
     */
    public function updateSettings()
    {
        $slideshow = ProcessSlideshow::factory()->create();
        // Call the api
        $response = $this->getJson(route('api.package.slideshow.settings.update', [$slideshow]));
        // Responses
        $response->assertStatus(204);
    }
}
