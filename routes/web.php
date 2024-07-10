<?php

use ProcessMaker\Package\PackageSlideshow\Http\Controllers\PackageSlideshowController;

Route::group([
    'middleware' => ['web', 'auth:web,anon', 'sanitize', 'bindings'],
], function () {
    Route::get('slideshow/main/{process}', [PackageSlideshowController::class, 'index'])->name('package.slideshow.index');
});
