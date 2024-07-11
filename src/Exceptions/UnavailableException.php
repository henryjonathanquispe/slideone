<?php

namespace ProcessMaker\Package\PackageSlideshow\Exceptions;

class UnavailableException extends \Exception
{
    public function render($request)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => $this->getMessage()], 404);
        } else {
            return response()->view('package-slideshow::access_denied', [], 404);
        }
    }
}
