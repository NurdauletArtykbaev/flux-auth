<?php

namespace Nurdaulet\FluxAuth\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Spatie\Permission\Models\Role as OriginalRole;
use Spatie\Translatable\HasTranslations;

class Role extends OriginalRole
{
    use HasFactory, SoftDeletes,EagerLoadPivotTrait;

    private $guard_name = 'web';
    protected $guarded = ['id'];

    public function scopeIsVerified($query)
    {
//        return $query->where('')
    }

//    protected $casts = [
//        'description' => 'array',
//    ];
//    public $translatable = ['description'];
}
