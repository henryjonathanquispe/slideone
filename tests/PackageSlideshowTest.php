<?php
namespace Tests\PackageSlideshow;

use \Mockery;
use Illuminate\Support\Facades\Artisan;
use ProcessMaker\Models\Permission;
use ProcessMaker\Models\Process;
use ProcessMaker\Package\PackageABTesting\Models\Alternative;
use ProcessMaker\Package\PackageSlideshow\Http\Controllers\PackageSlideshowController;
use ProcessMaker\Package\PackageSlideshow\Models\ProcessSlideshow;
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
        $response = $packageSlideshowController->listAlternatives($process)->original;
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
        Alternative::createForProcess($process);

        // get reponse array
        $response = $packageSlideshowController->listAlternatives($process)->original;
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
        Alternative::factory()->create([
            'process_id' => $process->id,
            'process_version_a_id' => 3,
            'process_version_b_id' => 3,
            'version_b_enabled' => true,

        ]);

        // get reponse array
        $response = $packageSlideshowController->listAlternatives($process)->original;
        $this->assertEquals(2, count($response));
        $this->assertEquals("A", $response[0]['published_version']);
        $this->assertEquals("B", $response[1]['published_version']);
    }

    /**
     * Test return of empty array
     */
    public function testGetAlternativesEmpty()
    {
        $packageSlideshowController = new PackageSlideshowController();
        $process = Process::factory()->create();

        // get reponse array
        $response = $packageSlideshowController->getAlternatives($process)->original;
        $this->assertEquals(0, count($response));
    }
    /**
     * Test return of one alternative
     */
    public function testGetAlternativesOneALternativeSelected()
    {
        $packageSlideshowController = new PackageSlideshowController();

        //Alternative A
        $process1 = Process::factory()->withTemplate('SingleTask.bpmn')->create();
        $processSlideshow1 = ProcessSlideshow::factory()->create([
            'process_id' => $process1->id
        ]);

        // get reponse array
        $response = $packageSlideshowController->getAlternatives($process1)->original;
        $this->assertEquals(1, count($response));
        $this->assertEquals($processSlideshow1->published_version, $response[0]['published_version']);
        $this->assertEquals($processSlideshow1->process_version_a_id, $response[0]['process_version']);


        //Alternative B
        $process2 = Process::factory()->create();
        $processSlideshow2 = ProcessSlideshow::factory()->create([
            'process_id' => $process2->id,
            'published_version' => "B",
            'process_version_b_id' => $process2->id
        ]);

        // get reponse array
        $response = $packageSlideshowController->getAlternatives($process2)->original;
        $this->assertEquals(1, count($response));
        $this->assertEquals($processSlideshow2->published_version, $response[0]['published_version']);
        $this->assertEquals($processSlideshow2->process_version_b_id, $response[0]['process_version']);
    }


    /**
     * Test return of two alternatives
     */
    public function testGetAlternativesTwoAlternatives()
    {
        $packageSlideshowController = new PackageSlideshowController();
        $process = Process::factory()->create();
        $processSlideshow = ProcessSlideshow::factory()->create([
            'process_id' => $process->id,
            'published_version' => "AB",
            'process_version_b_id' => $process->id
        ]);

        // get reponse array
        $response = $packageSlideshowController->getAlternatives($process)->original;
        $this->assertEquals(2, count($response));
        $this->assertEquals("A", $response[0]['published_version']);
        $this->assertEquals($processSlideshow->process_version_a_id, $response[0]['process_version']);
        $this->assertEquals("B", $response[1]['published_version']);
        $this->assertEquals($processSlideshow->process_version_b_id, $response[1]['process_version']);
    }
}
