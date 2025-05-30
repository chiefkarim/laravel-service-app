<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceRequestFactory> */
    use HasFactory;

    protected $fillable = ['name', 'email', 'details', 'service_id', 'status', 'reply'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
