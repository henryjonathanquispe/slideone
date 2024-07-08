<?php

use ProcessMaker\Package\PackageSlideshow\Http\Controllers\MailController;
use ProcessMaker\Package\PackageSlideshow\Http\Controllers\PackageSlideshowController;

Route::middleware('auth:api', 'bindings')->prefix('slideshow')->name('api.package.slideshow.')->group(function () {
    // Slideshow Images
    Route::get('images/{process}', [PackageSlideshowController::class, 'showImages'])->name('images.show')->middleware('can:edit-processes,process');
    Route::put('images/{process}', [PackageSlideshowController::class, 'store'])->name('images.store')->middleware('can:edit-processes,process');
    Route::delete('images/{media_uuid}', [PackageSlideshowController::class, 'destroy'])->name('images.destroy')->middleware('can:edit-processes,process');
    // Slideshow Process
    Route::post('process/{process}/settings', [PackageSlideshowController::class, 'storeSettings'])->name('settings.store')->middleware('can:edit-processes,process');
    Route::get('process/{process}/settings', [PackageSlideshowController::class, 'getSettings'])->name('settings.get.store')->middleware('can:edit-processes,process');
    Route::get('settings/{slideshow}', [PackageSlideshowController::class, 'indexSettings'])->name('settings.show')->middleware('can:edit-processes,process');
    Route::put('settings/{slideshow}', [PackageSlideshowController::class, 'updateSettings'])->name('settings.update')->middleware('can:edit-processes,process');
    // Mail
    Route::post('send/{process}', [MailController::class, 'sendEmail'])->name('send-email')->middleware('can:edit-processes,process');
});
