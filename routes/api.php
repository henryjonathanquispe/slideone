<?php

use ProcessMaker\Package\PackageSlideshow\Http\Controllers\PackageSlideshowController;

Route::group(['middleware' => ['auth:api', 'bindings']], function () {
    // Slideshow
    Route::get('slideshow/{process}', [PackageSlideshowController::class, 'index'])->name('slideshow.index')->middleware('can:edit-processes,process');
    Route::put('slideshow/{process}', [PackageSlideshowController::class, 'store'])->name('slideshow.store')->middleware('can:edit-processes,process');
    Route::delete('slideshow/{process}', [PackageSlideshowController::class, 'delete'])->name('slideshow.delete')->middleware('can:edit-processes,process');
});
