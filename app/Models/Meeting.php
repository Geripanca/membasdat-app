<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $table = 'meeting'; 
    protected $fillable = ['judul', 'deskripsi'];

    public function steps()
    {
        return $this->hasMany(StepMeeting::class, 'id_pertemuan');
    }
}
