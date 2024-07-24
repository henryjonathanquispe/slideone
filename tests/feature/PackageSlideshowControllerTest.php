<?php

namespace Tests\Feature\Api;

use Illuminate\Http\UploadedFile;
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

    const API_PROCESS_URL = '/slideshow/process';

    const API_SETTINGS_URL = '/slideshow/settings';

    public function testCreate()
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
        $response->assertViewIs('package-slideshow::index');
    }
}
