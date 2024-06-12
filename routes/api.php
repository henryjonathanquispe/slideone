<?php

use ProcessMaker\Package\PackageSlideshow\Http\Controllers\PackageSlideshowController;

Route::middleware('auth:api', 'bindings')
->prefix('slideshow')
->name('api.package.')
->group(function () {
    // Slideshow
    Route::get('slideshow/{process}', [PackageSlideshowController::class, 'index'])->name('slideshow.index')->middleware('can:edit-processes,process');
    Route::put('slideshow/{process}', [PackageSlideshowController::class, 'store'])->name('slideshow.store')->middleware('can:edit-processes,process');
    Route::delete('slideshow/{process}', [PackageSlideshowController::class, 'destroy'])->name('slideshow.destroy')->middleware('can:edit-processes,process');
});
