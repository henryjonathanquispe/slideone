<?php

namespace ProcessMaker\Package\PackageSlideone\Http\Controllers;

use ProcessMaker\Http\Controllers\Controller;

class SlideoneController extends Controller
{
    public function test()
    {
        return view('package-slideone::test', [
            'message' => 'SlideOne plugin test route',
        ]);
    }
}
