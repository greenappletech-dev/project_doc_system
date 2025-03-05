<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandNoticeTracker extends Model
{
    use HasFactory;
    protected $table = 'demand_notice_trackers';
    protected $fillable = ['name']; 
}
