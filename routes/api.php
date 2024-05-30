<?php

use ProcessMaker\Package\PackageSlideshow\Http\Controllers\PackageSlideshowController;

Route::group(['middleware' => ['auth:api', 'bindings']], function () {
    Route::get('admin/package-slideshow/fetch', [PackageSlideshowController::class, 'fetch'])->name('package.skeleton.fetch');
    Route::apiResource('admin/package-slideshow', PackageSlideshowController::class);
});
