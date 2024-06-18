<?php

namespace ProcessMaker\Package\PackageSlideshow\Models;

use ProcessMaker\Models\ProcessMakerModel;

class Sample extends ProcessMakerModel
{
    protected $table = 'sample_skeleton';

    protected $fillable = [
        'id', 'name', 'status',
    ];
}
