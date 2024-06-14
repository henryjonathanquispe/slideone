<?php

namespace ProcessMaker\Package\PackageSlideshow\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use ProcessMaker\Http\Resources\ApiCollection;
use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Http\Resources\ApiResource;
use ProcessMaker\Models\Media;
use ProcessMaker\Models\Process;
use ProcessMaker\Models\ProcessCategory;
use ProcessMaker\Models\ProcessRequest;
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
     * Store the image
     */
    public function store(Request $request, Process $process)
    {
        $validator = Validator::make($request->all(), [
            'imageSlideshow' => 'required|array',
            'imageSlideshow.url' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'imageSlideshow.node_id' => 'required|integer',
        ]);
        // Return the validations
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Store the Slideshow images
            $content = $request->input('imageSlideshow');
            if (!empty($content)) {
                $mediaCollectionName = Media::COLLECTION_SLIDESHOW;
                // Review if exist an image related to node_id
                $imageSlides = $this->getMedia($mediaCollectionName, ['node_id' => $content['node_id']]);
                if (!$imageSlides->isEmpty()) {
                    return response()->json(['error' => 'An image already exists for this node_id'], 409);
                }
                // Save th new image
                $media = new Media();
                $media->saveProcessMedia($process, $content, 'uuid', 'slideshow');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return new ApiResource([
            'success' => true,
            'message' => 'File uploaded successfully',
        ], 201);
    }

    /**
     * Delete the image
     */
    public function destroy(Request $request, Process $process)
    {
        // Get UUID
        $uuid = $request->input('uuid');
        // Review if exist
        $media = Media::where('uuid', $uuid)
            ->where('model_id', $process->id)
            ->first();
        // Delete
        if ($media) {
            $media->delete();
        }
    }
}
