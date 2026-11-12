<?php

use ProcessMaker\Package\PackageSlideone\Http\Controllers\SlideoneController;

Route::group([
    'middleware' => ['web', 'auth:web'],
], function () {
    Route::get('slideone/test', [SlideoneController::class, 'test'])->name('package.slideone.test');
});
