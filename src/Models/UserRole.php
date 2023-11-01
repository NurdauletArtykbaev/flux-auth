<?php

namespace Nurdaulet\FluxAuth\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class UserRole extends MorphPivot
{
    use HasFactory, SoftDeletes, EagerLoadPivotTrait;

    protected static function booted(): void
    {
        static::addGlobalScope('isNotDeleted', function (Builder $builder) {
            $builder->whereNull('deleted_at');
        });
    }

    protected $table = 'model_has_roles';
    protected $fillable = [
        'model_id',
        'role_id',
        'model_type',
        'lord_id',
        'store_id',
        'username',
        'code',
        'is_verified',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'model_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class,'store_id', 'id');
    }

    public function lord()
    {
        return $this->belongsTo(User::class, 'lord_id', 'id');
    }
    public function scopeIsVerified($query)
    {
        return $query->where('is_verified', 1);
    }
    public function scopeIsNotDeleted($query)
    {
        return $query->whereNull('deleted_at');
    }
    protected $casts = [
        'is_verified' => 'boolean'
    ];
}
