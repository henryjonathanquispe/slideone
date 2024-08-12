<?php

use ProcessMaker\Package\PackageSlideshow\Http\Controllers\PackageSlideshowController;

Route::group([
    'middleware' => ['web', 'auth:web,anon', 'sanitize', 'bindings'],
], function () {
    Route::get('slideshow/{process_uuid}', [PackageSlideshowController::class, 'index'])->name('package.slideshow.index');
});
