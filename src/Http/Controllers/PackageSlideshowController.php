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

class PackageSlideshowController extends Controller
{
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
            'imagesSlideshow' => 'required|array',
            'imagesSlideshow.url' => 'image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Store the Slideshow images
            $content = $request->input('imagesSlideshow');
            if (!empty($content)) {
                foreach ($content as $row) {
                    $media = new Media();
                    $media->saveProcessMedia($process, $row, 'uuid', 'slideshow');
                }
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
        // Get UUID in the table
        $uuid = $request->input('uuid');

        $media = Media::where('uuid', $uuid)
            ->where('model_id', $process->id)
            ->first();

        // Check if embed before delete
        if ($media) {
            $media->delete();
        }
    }
}
