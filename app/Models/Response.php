<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_id',
        'user_id',
        'inspection_id',
        'attempt',
        'user_opinion_status',
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }
}
