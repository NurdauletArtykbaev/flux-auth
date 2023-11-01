<?php

namespace Nurdaulet\FluxAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class TypeOrganization extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $guarded = ['id'];
    public $translatable = ['name'];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
