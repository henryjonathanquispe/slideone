<?php

namespace Tests\PackageSlideshow;

use ProcessMaker\Models\Process;
use ProcessMaker\Models\ProcessVersion;
use ProcessMaker\Package\PackageABTesting\Models\Alternative;
use ProcessMaker\Package\PackageSlideshow\Http\Controllers\Api\PackageSlideshowController;
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
        $this->assertEquals(1, count($response));
    }

    /**
     * Test return with only one verison
     */
    public function testListAlternativesOneVersion()
    {
        $packageSlideshowController = new PackageSlideshowController();

        // Create a process Alternative A
        $process1 = Process::factory()->withTemplate('SingleTask.bpmn')->create([
            'alternative' => 'A',
        ]);
        Alternative::createForProcess($process1);

        // Get reponse array
        $response = $packageSlideshowController->listAlternatives($process1)->original;
        $this->assertEquals(1, count($response));
        $this->assertEquals('A', $response[0]['published_version']);

        // Create a process Alternative B
        $process2 = Process::factory()->withTemplate('SingleTask.bpmn')->create([
            'alternative' => 'B',
        ]);
        Alternative::factory()->create([
            'process_id' => $process2->id,
            'process_version_b_id' => 3,
            'published_version' => 'B',

        ]);

        // Get reponse array
        $response = $packageSlideshowController->listAlternatives($process2)->original;
        $this->assertEquals(1, count($response));
        $this->assertEquals('B', $response[0]['published_version']);
    }

    /**
     * Test return with alternative A and B versions
     */
    public function testListAlternativesABVersions()
    {
        $packageSlideshowController = new PackageSlideshowController();
        // Create a process with two draft versions
        $process = Process::factory()->withTemplate('SingleTask.bpmn')->create([
            'alternative' => 'A',
        ]);
        ProcessVersion::factory()->create([
            'process_id' => $process->id,
            'alternative' => 'B',
        ]);
        Alternative::factory()->create([
            'process_id' => $process->id,
            'process_version_a_id' => 3,
            'process_version_b_id' => 3,
            'published_version' => 'AB',

        ]);

        // get reponse array
        $response = $packageSlideshowController->listAlternatives($process)->original;
        $this->assertEquals(2, count($response));
        $this->assertEquals('A', $response[0]['published_version']);
        $this->assertEquals('B', $response[1]['published_version']);
    }

    /**
     * Test get alternatives
     */
    public function testGetAlternatives()
    {
        $process = Process::factory()->create();
        $packageSlideshowController = new PackageSlideshowController();
        $response = $packageSlideshowController->getAlternatives($process);
        $this->assertIsObject($response);
        $this->assertEmpty($response->getData());

        ProcessSlideshow::factory()->create([
            'process_id' => $process->id,
        ]);
        $packageSlideshowController = new PackageSlideshowController();
        $response = $packageSlideshowController->getAlternatives($process);
        $this->assertIsObject($response);
        $this->assertNotEmpty($response->getData());
    }
}
