<?php

namespace ProcessMaker\Package\PackageSlideshow\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use ProcessMaker\Events\ModelerStarting;
use ProcessMaker\Events\ScreenBuilderStarting;
use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Http\Controllers\Process\ModelerController;
use ProcessMaker\Managers\ModelerManager;
use ProcessMaker\Managers\ScreenBuilderManager;
use ProcessMaker\Models\Process;
use ProcessMaker\Package\PackageSlideshow\Models\ProcessSlideshow;

class PackageSlideshowController extends Controller
{
    private static $accessDeniedView = 'package-slideshow::denied';

    /**
     * @param Process $process The Process instance to be displayed.
     * @return \Illuminate\View\View The view for the modeler interface.
     */
    public function index(Request $request, string $pro_uuid)
    {
        try {
            $process = Process::where('uuid', $pro_uuid)->first();
            if (!$process) {
                throw new Exception('The process uuid does not exist');
            }

            // Load event ScreenBuilderStarting
            $manager = app(ScreenBuilderManager::class);
            event(new ScreenBuilderStarting($manager, 'FORM'));
            // Load event ModelerStarting
            $managerModeler = app(ModelerManager::class);
            event(new ModelerStarting($managerModeler));

            // Check if the process status
            $this->checkProcessIsActive($process);
            // Check if the process is enable slideshow sharing
            $this->ensureSlideshowIsEnabled($process);
            // Get the slideshow config related to the processs
            $processSlideshow = ProcessSlideshow::where('process_id', $process->id)->first();

            if (!isset($processSlideshow)) {
                throw new Exception('The process slideshow config is not published');
            }
            // Get the version of the process
            $processAlternativeA = $process->getDraftOrPublishedLatestVersion('A');
            $processAlternativeB = $process->getDraftOrPublishedLatestVersion('B');
            // Get all PM-Blocks
            $modelerController = new ModelerController();
            $pmBlockList = $modelerController->getPmBlockList();

            return view('package-slideshow::index', [
                'manager' => $manager,
                'managerModeler' => $managerModeler,
                'process' => $process,
                'bpmnA'=> $processAlternativeA->bpmn ?? $process->bpmn,
                'bpmnB'=> $processAlternativeB->bpmn ?? null,
                'published_version'=> $processSlideshow->published_version ?? null,
                'pmBlockList' => $pmBlockList,
            ]);
        } catch (Exception $e) {
            // Manejar la excepción y mostrar la vista de error
            return view(self::$accessDeniedView, []);
        }
    }

    /**
     * Check if the process is active
     *
     * @param Process $process
     *
     * @throws UnavailableException
     */
    private function checkProcessIsActive(Process $process)
    {
        if ($process->status !== 'ACTIVE') {
            throw new Exception('The process is not active');
        }
    }

    /**
     * Check if the process is active
     *
     * @param Process $process
     *
     * @throws UnavailableException
     */
    private function ensureSlideshowIsEnabled(Process $process)
    {
        $isSlideshowEnabled = ProcessSlideshow::where('process_id', $process->id)
            ->where('is_enable', 1)
            ->exists();

        if (!$isSlideshowEnabled) {
            throw new Exception('The process slideshow config is not published');
        }
    }
}
