<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'fullname',
        'email',
        'service_id',
        'details',
        'status',
        'reply',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
