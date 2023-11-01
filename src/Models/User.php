<?php

namespace Nurdaulet\FluxAuth\Models;

//use Illuminate\Database\Eloquent\Model;
//use Laravel\Sanctum\HasApiTokens;
//
//class User extends Model
//{
//    use HasApiTokens;
//
//    public function ratings()
//    {
//        return $this->hasMany(UserRating::class, 'receiver_id');
//    }
//}


namespace Nurdaulet\FluxAuth\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasRoles;


/**
 * @property int $id
 * @property string $contract
 */
class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use HasRoles {
        roles as protected originalRoles;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];
//    protected $fillable = [
//        'name',
//        'email',
//        'password',
//        'phone',
//        'surname',
//        'avatar',
//    ];

    const NOT_VERIFIED = 1;
    const ON_PROCESS = 2;
    const VERIFIED = 3;


    public function roles(): BelongsToMany
    {
        $relation = $this->morphToMany(
            Role::class,
            'model',
            UserRole::class,
            config('permission.column_names.model_morph_key'),
            PermissionRegistrar::$pivotRole
        );

        if (!PermissionRegistrar::$teams) {
            return $relation;
        }

        return $relation->wherePivot(PermissionRegistrar::$teamsKey, getPermissionsTeamId())
            ->where(function ($q) {
                $teamField = config('permission.table_names.roles') . '.' . PermissionRegistrar::$teamsKey;
                $q->whereNull($teamField)->orWhere($teamField, getPermissionsTeamId());
            })->wherePivot('is_verified', true)
            ->using(UserRole::class)
            ->withPivot('lord_id', 'store_id');
//        dd(config('permission.models.role'));
//        return     $this->morphToMany(
//            Role::class,
//            'model',
//            config('permission.table_names.model_has_roles'),
//            config('permission.column_names.model_morph_key'),
//            PermissionRegistrar::$pivotRole
//        )->using(UserRole::class);
//
//        if (! PermissionRegistrar::$teams) {
//            return $relation;
//        }
//
//        return $relation->wherePivot(PermissionRegistrar::$teamsKey, getPermissionsTeamId())
//            ->where(function ($q) {
//                $teamField = config('permission.table_names.roles').'.'.PermissionRegistrar::$teamsKey;
//                $q->whereNull($teamField)->orWhere($teamField, getPermissionsTeamId());
//            });
        return $this->originalRoles()->using(UserRole::class)->wherePivot('is_verified', true)->withPivot(['lord_id', 'store_id']);
    }

    public function callOriginalSomeMethod()
    {
        return $this->originalRoles();
    }
    public function canAccessFilament(): bool
    {
        return str_ends_with($this->email, '@naprocat.kz') && $this->hasVerifiedEmail();
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'code',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
//        'identify_status' => 'integer',
        'is_identified' => 'boolean',
        'is_banned' => 'boolean',
        'online' => 'boolean',
        'delivery_times' => 'json',
        'graphic_works' => 'json',
        'is_enabled_notification' => 'boolean',
        'email_verified_at' => 'datetime',
    ];
    protected $appends = ['avatar_url'];

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

    protected function deliveryTimes(): Attribute
    {
        return Attribute::make(
            get: function ($value) {

                if (empty($value)) {
                    return [
                        '11:00-14:00',
                        '14:00-17:00',
                        '17:00-20:00',
                    ];
                }
                $value = str_replace('"', '', $value);
                return explode(',', $value);
            },
            set: function ($value) {
                if (is_array($value)) {
                    return implode(',', $value);
                }
            },
        );
    }

    public function typeOrganization()
    {
        return $this->belongsTo(TypeOrganization::class, 'type_organization_id');
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function ratings()
    {
        return $this->hasMany(UserRating::class, 'receiver_id');
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? (config('filesystems.disks.s3.url') . '/' . $this->avatar) : null;
    }
//
//    public function balance()
//    {
//        return $this->hasOne(Balance::class, 'user_id', 'id');
//    }

    public function getIdImageUrlAttribute()
    {
        return $this->identify_front ? (config('filesystems.disks.s3.url') . '/' . $this->identify_front) : null;
    }

    public function getVerifyImageUrlAttribute()
    {
        return $this->verify_image ? (config('filesystems.disks.s3.url') . '/' . $this->verify_image) : null;
    }

    public function isBanned()
    {
        return $this->is_banned;
    }

    public function getContractUrlAttribute()
    {
        return $this->contract ? (config('filesystems.disks.s3.url') . '/' . $this->contract) : null;
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->surname;
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? (config('filesystems.disks.s3.url') . '/' . $this->logo) : null;
    }

    public function getLogoWebpUrlAttribute()
    {
        return $this->logo_webp ? (config('filesystems.disks.s3.url') . '/' . $this->logo_webp) : null;
    }

    public function getFullNameWithPhoneAttribute()
    {
        return $this->name . ' ' . $this->surname . '| ' . $this->phone;
    }

    public function getCompanyNameWithPhoneAttribute()
    {
        return $this->company_name . '| ' . $this->phone;
    }

    public function isPushEnabled()
    {

        return true;
//        return $this->deviceTokens()->exists();
    }

    public static function getVerifiedOptions()
    {
        return [
            self::NOT_VERIFIED => trans('admin.not_verified'),
            self::ON_PROCESS => trans('admin.on_process'),
            self::VERIFIED => trans('admin.verified'),
        ];
    }
}
