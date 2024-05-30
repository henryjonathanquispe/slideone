<?php

use ProcessMaker\Package\PackageSlideshow\Http\Controllers\PackageSlideshowController;

Route::group(['middleware' => ['auth']], function () {
    Route::get('admin/package-slideshow', [PackageSlideshowController::class, 'index'])->name('package.skeleton.index');
    Route::get('package-slideshow', [PackageSlideshowController::class, 'index'])->name('package.skeleton.tab.index');
});
