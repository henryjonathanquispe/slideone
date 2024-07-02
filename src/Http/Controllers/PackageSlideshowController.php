<?php

namespace ProcessMaker\Package\PackageSlideshow\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use ProcessMaker\Http\Resources\ApiCollection;
use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Http\Resources\ApiResource;
use ProcessMaker\Models\Media;
use ProcessMaker\Models\Process;
use ProcessMaker\Models\ProcessCategory;
use ProcessMaker\Models\ProcessRequest;
use ProcessMaker\Package\PackageSlideshow\Exceptions\UnavailableException;
use ProcessMaker\Package\PackageSlideshow\Models\ProcessSlideshow;
use ProcessMaker\Package\PackageABTesting\Models\Alternative;
use ProcessMaker\Traits\ProcessMapTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use RBAC;
use URL;

class PackageSlideshowController extends Controller
{
    use InteractsWithMedia;
    use ProcessMapTrait;

    /**
     * @param Process $process The Process instance to be displayed.
     * @return \Illuminate\View\View The view for the modeler interface.
     */
    public function index(Request $request, Process $process)
    {
        try {
            // Check if the process status
            $this->checkProcessIsActive($process);
            // Check if the process is enable slideshow sharing
            // Login with the Anonymous user
            // Show the view
            return view('package-slideshow::index', ['process' => $process]);
        } catch (UnavailableException $e) {
            // Manejar la excepción y mostrar la vista de error
            return response()->view('package-slideshow::unavailable', ['message' => $e->getMessage()], 403);
        }
    }

    /**
     * Get the index
     */
    public function show(Request $request, Process $process)
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
                $imageSlides = $process->getMedia($mediaCollectionName, ['node_id' => $content['node_id']]);
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
        } catch (\Exception $e) {
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

        $slideshow = new ProcessSlideshow();
        $slideshow->process_id = $process->getKey();
        $slideshow->user_id = Auth::user()->id;
        $slideshow->fill($request->all());
        $slideshow->save();

        return response()->json($slideshow, Response::HTTP_CREATED);
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
     * Check if the process is active
     *
     * @param Process $process
     *
     * @throws UnavailableException
     */
    private function checkProcessIsActive(Process $process)
    {
        if ($process->status !== 'ACTIVE') {
            throw new UnavailableException();
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
            throw new UnavailableException();
        }
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
                $response[] = [
                    'process_version' => $alternative->process_version_a_id,
                    'published_version' => "A"
                ];
                if ($alternative->version_b_enabled) {
                    $response[] = [
                        'process_version' => $alternative->process_version_b_id,
                        'published_version' => "B"
                    ];
                }
            }
        }

        return response()->json($response, 200);
    }
}
