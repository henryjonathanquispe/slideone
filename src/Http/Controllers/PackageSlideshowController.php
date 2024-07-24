<?php

namespace ProcessMaker\Package\PackageSlideshow\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use ProcessMaker\Events\ScreenBuilderStarting;
use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Http\Resources\ApiResource;
use ProcessMaker\Managers\ScreenBuilderManager;
use ProcessMaker\Models\Media;
use ProcessMaker\Models\Process;
use ProcessMaker\Package\PackageABTesting\Models\Alternative;
use ProcessMaker\Package\PackageSlideshow\Exceptions\UnavailableException;
use ProcessMaker\Package\PackageSlideshow\Models\ProcessSlideshow;
use ProcessMaker\Traits\ProcessMapTrait;
use Spatie\MediaLibrary\InteractsWithMedia;

class PackageSlideshowController extends Controller
{
    use InteractsWithMedia;
    use ProcessMapTrait;

    private static $accessDeniedView = 'package-slideshow::denied';

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

    /**
     * @param Process $process The Process instance to be displayed.
     * @return \Illuminate\View\View The view for the modeler interface.
     */
    public function index(Request $request, Process $process)
    {
        try {
            // Login with the Anonymous user
            // Check if the process status

            $manager = app(ScreenBuilderManager::class);
            event(new ScreenBuilderStarting($manager, 'FORM'));

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

            return view('package-slideshow::index', [
                'manager' => $manager,
                'process' => $process,
                'bpmnA'=> $processAlternativeA->bpmn ?? $process->bpmn,
                'bpmnB'=> $processAlternativeB->bpmn ?? null,
                'published_version'=> $processSlideshow->published_version ?? null,
            ]);
        } catch (Exception $e) {
            // Manejar la excepción y mostrar la vista de error
            return view(self::$accessDeniedView, []);
        }
    }

    /**
     * Get the index
     */
    public function showImages(Request $request, Process $process)
    {
        // Get the slideshow images configuration
        // Get the images related
        $processesImages = Process::with(['media' => function ($query) {
            $query->orderBy('order_column', 'asc');
            $query->where('collection_name', '=', Media::COLLECTION_SLIDESHOW);
        }])
            ->where('id', $process->id)
            ->get()
            ->toArray();

        return new ApiResource($processesImages);
    }

    /**
     * Validate the request data
     */
    protected function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'imageSlideshow' => 'required|array',
            'imageSlideshow.url' => 'required',
            'imageSlideshow.node_id' => 'required|string',
        ]);

        // Return the validations
        if ($validator->fails()) {
            return ['status' => false, 'data' => response()->json(['errors' => $validator->errors()], 422)];
        }

        return ['status' => true];
    }

    /**
     * Store the image
     */
    public function store(Request $request, Process $process)
    {
        // Validate the request data
        $validationResponse = $this->validateRequest($request);
        if (!$validationResponse['status']) {
            return $validationResponse['data'];
        }

        try {
            // Store the Slideshow images
            $content = $request->input('imageSlideshow');
            if (!empty($content)) {
                $mediaCollectionName = Media::COLLECTION_SLIDESHOW;
                // Review if exist an image related to node_id
                $imageSlides = $process->getMedia(
                    $mediaCollectionName,
                    ['node_id' => $content['node_id'], 'alternative'=> $content['alternative']]
                );
                if (!$imageSlides->isEmpty()) {
                    return response()->json(['error' => 'An image already exists for this node_id'], 409);
                }
                // Save th new image
                $media = new Media();
                $media->saveProcessMedia($process, $content, 'uuid', 'slideshow');
            }

            $response = new ApiResource([
                'success' => true,
                'message' => 'File uploaded successfully',
            ], 201);
        } catch (Exception $e) {
            $response = response()->json(['error' => $e->getMessage()], 400);
        }

        return $response;
    }

    /**
     * Delete the image
     */
    public function destroy($uuid)
    {
        // Review if exist
        $media = Media::where('uuid', $uuid)
            ->first();
        // Delete
        if ($media) {
            $media->delete();
        }

        return response()->json(null, 204);
    }

    /**
     * Get index settings
     */
    public function indexSettings(ProcessSlideshow $slideshow)
    {
        return response()->json($slideshow);
    }

    /**
     * Store the new settings
     */
    public function storeSettings(Request $request, Process $process)
    {
        $request->validate([
            'published_version' => 'required|string|max:2',
            'is_enable' => 'required|boolean',
        ]);

        $attributes = [
            'process_id' => $process->getKey(),
            'user_id' => Auth::user()->id,
        ];
        $values = $request->all();
        // Update the Slideshow
        $slideshow = ProcessSlideshow::updateOrCreate($attributes, $values);

        return response()->json($slideshow, Response::HTTP_CREATED);
    }

    /**
     * Get index settings
     */
    public function getSettings(Request $request, Process $process)
    {
        $slideshow = ProcessSlideshow::where('process_id', $process->getKey())->first();

        return response()->json($slideshow);
    }

    /**
     * Update settings
     */
    public function updateSettings(Request $request, ProcessSlideshow $slideshow)
    {
        $request->validate([
            'published_version' => 'required|string|max:2',
            'is_enable' => 'required|boolean',
        ]);

        $slideshow->update($request->all());

        return response()->json($slideshow);
    }

    /**
     * Check if the process have alternatives
     *
     * @param Process $process
     *
     * @return Response
     */
    public function listAlternatives(Process $process)
    {
        $hasAbTesting = class_exists('ProcessMaker\Package\PackageABTesting\Models\Alternative');
        $response = [];
        if ($hasAbTesting) {
            $alternative = Alternative::forProcess($process)->first();
            if ($alternative) {
                if (str_contains($alternative->published_version, 'A')) {
                    $response[] = [
                        'process_version' => $alternative->process_version_a_id,
                        'published_version' => 'A',
                    ];
                }
                if (str_contains($alternative->published_version, 'B')) {
                    $response[] = [
                        'process_version' => $alternative->process_version_b_id,
                        'published_version' => 'B',
                    ];
                }
            }
        }

        return response()->json($response, 200);
    }

    /**
     * Check if the process is active
     *
     * @param Process $process
     *
     * @return Response
     */
    public function getAlternatives(Process $process)
    {
        $processSlideshow = ProcessSlideshow::where('process_id', $process->id)->first();
        $response = [];
        if ($processSlideshow) {
            if (str_contains($processSlideshow->published_version, 'A')) {
                $response[] = [
                    'process_version' => $processSlideshow->process_version_a_id,
                    'published_version' => 'A',
                ];
            }
            if (str_contains($processSlideshow->published_version, 'B')) {
                $response[] = [
                    'process_version' => $processSlideshow->process_version_b_id,
                    'published_version' => 'B',
                ];
            }
        }

        return response()->json($response, 200);
    }
}
