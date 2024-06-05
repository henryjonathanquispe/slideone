<?php

use ProcessMaker\Package\PackageSlideshow\Http\Controllers\PackageSlideshowController;

Route::group(['middleware' => ['auth']], function () {
    Route::get('slideshow/main', [PackageSlideshowController::class, 'index'])->name('package.slideshow.index');
});
