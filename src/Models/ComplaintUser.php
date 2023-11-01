<?php

namespace Nurdaulet\FluxAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplaintUser extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'complaint_reason_id',
        'user_id',
        'who_complaint_user_id',
        'status',
        'comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function whoComplaintUser()
    {
        return $this->belongsTo(User::class, 'who_complaint_user_id');
    }

    public function compReason()
    {
        return $this->belongsTo(ComplaintReason::class, 'complaint_reason_id');
    }

}
