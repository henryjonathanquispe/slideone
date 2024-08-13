<?php

namespace Tests\Feature\Api;

use ProcessMaker\Models\Process;
use ProcessMaker\Package\PackageSlideshow\Models\ProcessSlideshow;
use Tests\Feature\Shared\RequestHelper;
use Tests\TestCase;

class PackageSlideshowControllerTest extends TestCase
{
    use RequestHelper;

    public function testIndexViewUuid()
    {
        $process = Process::factory()->create();
        ProcessSlideshow::factory()->create([
            'process_id' => $process->id,
            'published_version' => 'A',
            'is_enable' => 1,
        ]);

        // Our user now has permissions, so this should return 200.
        $response = $this->webCall('GET', 'slideshow/' . $process->uuid);
        $response->assertStatus(200);
        $response->assertViewIs('package-slideshow::index');
    }

    public function testIndexViewProcessNotExit()
    {
        $process = Process::factory()->create();
        ProcessSlideshow::factory()->create([
            'process_id' => $process->id,
            'published_version' => 'A',
            'is_enable' => 1,
        ]);

        // Our user now has permissions, so this should return 200.
        $response = $this->webCall('GET', 'slideshow/' . $process->id);
        $response->assertStatus(200);
        $response->assertViewIs('package-slideshow::denied');
    }
}
