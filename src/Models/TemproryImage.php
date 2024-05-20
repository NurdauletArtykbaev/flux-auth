<?php

namespace Nurdaulet\FluxAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemproryImage extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'temprory_images';
    protected $guarded = ['id'];

    protected $appends = array('full_url', 'webp_full_url');

    public function getFullUrlAttribute()
    {
        return $this->image ? (config('filesystems.disks.s3.url').'/'.$this->image) : null;
    }

}
