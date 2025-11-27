<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StepMeeting extends Model
{
    use HasFactory;

    protected $table = 'step_meeting';
    protected $fillable = ['id_pertemuan', 'judul', 'deskripsi', 'id_materis', 'id_quiz','id_tugas'];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class, 'id_pertemuan');
    }
    public function quiz()
    {
        return $this->belongsTo(\App\Models\Quiz::class, 'id_quiz');
    }
    public function tugas()
    {
        return $this->belongsTo(\App\Models\Tugas::class, 'id_tugas', 'id_tugas');
    }
    public function materis()
    {
        return $this->belongsToMany(Materi::class, 'pivot_meeting', 'step_meeting_id', 'materi_id');
    }

}
