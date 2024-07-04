<?php

namespace ProcessMaker\Package\PackageSlideshow\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use ProcessMaker\Models\User;
use ProcessMaker\Models\Process;
use ProcessMaker\Models\ProcessMakerModel;
use ProcessMaker\Traits\HasUuids;

class ProcessSlideshowFactory extends ProcessMakerModel
{
    use HasFactory;
    use HasUuids;

    protected $connection = 'processmaker';

    protected $table = 'process_slideshow';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'uuid',
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'process_id' => null,
        'user_id' => null,
        'published_version' => '',
        'process_version_a_id' => null,
        'process_version_b_id' => null,
        'version_b_enabled' => false,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'process_id',
        'user_id',
        'published_version',
        'process_version_a_id',
        'process_version_b_id',
        'is_enable',
        'properties',
    ];

    public static function rules(): array
    {
        return [
            'user_id' => 'required',
            'process_id' => 'required',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    /**
     * Get the Slideshow related
     */
    public static function getSlideshow($show, $proId)
    {
        $response = null;
        if ($show) {
            $response = self::where('process_id', $proId)->first();
        }

        return $response;
    }
}
