<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function scopeSearching($query, $keyword)
    {
        $query->when($keyword, function ($query, $keyword) {
            return $query->where('title', 'like', '%' . $keyword . '%');
        });
    }

    public function steps()
    {
        return $this->belongsToMany(StepMeeting::class, 'pivot_meeting', 'materi_id', 'step_meeting_id');
    }

}
