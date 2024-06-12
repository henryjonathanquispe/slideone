<?php

use ProcessMaker\Package\PackageSlideshow\Http\Controllers\PackageSlideshowController;

Route::middleware('auth:api', 'bindings')
->prefix('api/package/slideshow')
->name('api.package.slideshow.')
->group(function () {
    // Slideshow
    Route::get('images/{process}', [PackageSlideshowController::class, 'show'])->name('images.show')->middleware('can:edit-processes,process');
    Route::put('images/{process}', [PackageSlideshowController::class, 'store'])->name('images.store')->middleware('can:edit-processes,process');
    Route::delete('images/{process}', [PackageSlideshowController::class, 'destroy'])->name('images.destroy')->middleware('can:edit-processes,process');
});
