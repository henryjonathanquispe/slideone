<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use ProcessMaker\Models\Process;
use ProcessMaker\Models\User;
use Tests\TestCase;

class PackageSlideshowControllerTest extends TestCase
{
    // Before tests
    protected function setUp(): void
    {
        //
    }

    /**
     * Test the index method
     */
    public function testIndex()
    {
        $process = Process::factory()->create();

        $response = $this->getJson(route('api.package.slideshow.index', ['process' => $process->id]));

        $response->assertStatus(200);
    }

    /**
     * Test the store method
     */
    public function testStore()
    {
        $process = Process::factory()->create();

        $response = $this->getJson(route('api.package.slideshow.store', ['process' => $process->id]));

        $response->assertStatus(201);
    }

    /**
     * Test the destroy method
     */
    public function testDestroy()
    {
        $process = Process::factory()->create();

        $response = $this->getJson(route('api.package.slideshow.destroy', ['process' => $process->id]));

        $response->assertStatus(204);
    }
}
