<?php

namespace Nurdaulet\FluxAuth\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'user_id',
        'phone',
        'last_online',
        'online',
        'ratings_count',
        'graphic_works',
        'avg_rating',
        'city_id',
    ];

    protected $casts = [
        'graphic_works' => 'json',
        'address' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function employees()
    {
        return $this->belongsToMany(User::class, 'model_has_roles', 'store_id', 'model_id', 'id')
            ->using(UserRole::class)
            ->wherePivotNull('deleted_at')
            ->withPivot('model_id', 'role_id', 'store_id', 'username');
    }

    public function getGraphicWorksAttribute($value)
    {
        if (empty($value)) {
            return [[
                'day' => 1,
                'start_hour' => '08:00',
                'end_hour' => '20:00',
                'is_closed' => false
            ],
                [
                    'day' => 2,
                    'start_hour' => '08:00',
                    'end_hour' => '20:00',
                    'is_closed' => false
                ],
                [
                    'day' => 3,
                    'start_hour' => '08:00',
                    'end_hour' => '20:00',
                    'is_closed' => false
                ],
                [
                    'day' => 4,
                    'start_hour' => '08:00',
                    'end_hour' => '20:00',
                    'is_closed' => false
                ],
                [
                    'day' => 5,
                    'start_hour' => '08:00',
                    'end_hour' => '20:00',
                    'is_closed' => false
                ],
                [
                    'day' => 6,
                    'start_hour' => '08:00',
                    'end_hour' => '20:00',
                    'is_closed' => true
                ],
                [
                    'day' => 0,
                    'start_hour' => '08:00',
                    'end_hour' => '20:00',
                    'is_closed' => true
                ]];
        }
        return json_decode($value, true);
    }
}
