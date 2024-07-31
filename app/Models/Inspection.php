<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'inspection_start',
        'inspection_end',
        'attempts_per_operator',
        'quantity_pieces',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
