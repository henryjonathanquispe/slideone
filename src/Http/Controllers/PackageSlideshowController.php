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
use ProcessMaker\Package\PackageSlideshow\Models\ProcessSlideshow;
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
    public function index(Process $process)
    {
        return view('package-slideshow::index', ['process' => $process]);
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
            'imageSlideshow.node_id' => 'required|integer',
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
    public function destroy(Request $request)
    {
        $request->validate([
            'uuid' => 'required|string',
        ]);

        // Get UUID
        $uuid = $request->input('uuid');
        // Review if exist
        $media = Media::where('uuid', $uuid)
            ->first();
        // Delete
        if ($media) {
            $media->delete();
        }
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
}
