<?php

namespace Database\Factories\ProcessMaker\Package\PackageSlideshow;

use Illuminate\Database\Eloquent\Factories\Factory;
use ProcessMaker\Models\Process;
use ProcessMaker\Models\User;
use ProcessMaker\Package\PackageSlideshow\Models\ProcessSlideshow;

class ProcessSlideshowFactory extends Factory
{
    protected $model = ProcessSlideshow::class;

    public function definition()
    {
        $process = Process::factory()->create();
        $user = User::factory()->create();

        return [
            'process_id' => $process->getKey(),
            'user_id' => $user->getKey(),
            'published_version' => 'A',
            'process_version_a_id' => $process->getLatestVersion()->getKey(),
            'process_version_b_id' => null,
            'is_enable' => 1,
            'url' => 'slideshow/'. $process->getKey(),
        ];
    }
}
