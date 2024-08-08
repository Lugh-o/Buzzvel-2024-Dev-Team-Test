<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'holiday_plans_id'];
    public $timestamps = false;

    public function holiday_plan()
    {
        return $this->belongsTo(HolidayPlan::class);
    }
}
