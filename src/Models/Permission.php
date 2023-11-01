<?php

namespace Nurdaulet\FluxAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Spatie\Permission\Models\Permission as OriginalPermission;

class Permission extends OriginalPermission
{
    use HasFactory;

    public $guard_name = 'web';

    protected $guarded = ['id'];

}
