<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'notice_type_id',
        'project_id',
        'beneficiary_id',
        'photo',
        'date_captured',
        'user_id'
    ];

    protected static function boot()
    {
        parent::boot();
    
        static::deleting(function ($delivery) {
            // Ensure the photo field is not null or empty
            if ($delivery->photo && File::exists(public_path($delivery->photo))) {
                File::delete(public_path($delivery->photo));
            }
        });
    }
    
}
