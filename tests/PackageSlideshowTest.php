<?php
namespace Tests\PackageSlideshow;

use \Mockery;
use Illuminate\Support\Facades\Artisan;
use ProcessMaker\Models\Permission;
use ProcessMaker\Models\Process;
use ProcessMaker\Package\PackageABTesting\Models\Alternative;
use ProcessMaker\Package\PackageSlideshow\Http\Controllers\PackageSlideshowController;
use Tests\TestCase;

class PackageSlideshowTest extends TestCase
{
    /**
     * Test return of empty array
     */
    public function testListAlternativesEmptyVersions()
    {
        $packageSlideshowController = new PackageSlideshowController();
        // Create a simple process
        $process = Process::factory()->withTemplate('SingleTask.bpmn')->create();

        // get reponse array
        $response = $packageSlideshowController->listAlternatives($process);
        $this->assertEquals(0, count($response));
    }

    /**
     * Test return with only alternatie A verison
     */
    public function testListAlternativesOneVersion()
    {
        $packageSlideshowController = new PackageSlideshowController();
        // Create a process with Alternative A
        $process = Process::factory()->withTemplate('SingleTask.bpmn')->create();
        $alternative = Alternative::createForProcess($process);

        // get reponse array
        $response = $packageSlideshowController->listAlternatives($process);
        $this->assertEquals(1, count($response));
        $this->assertEquals("A", $response[0]['published_version']);
    }

    /**
     * Test return with alternative A and B versions
     */
    public function testListAlternativesABVersions()
    {
        $packageSlideshowController = new PackageSlideshowController();
        // Create a process with two draft versions
        $process = Process::factory()->withTemplate('SingleTask.bpmn')->create();
        $alternative = Alternative::factory()->create([
            'process_id' => $process->id,
            'process_version_a_id' => 3,
            'process_version_b_id' => 3,
            'version_b_enabled' => true,

        ]);

        // get reponse array
        $response = $packageSlideshowController->listAlternatives($process);
        $this->assertEquals(2, count($response));
        $this->assertEquals("A", $response[0]['published_version']);
        $this->assertEquals("B", $response[1]['published_version']);
    }
}
