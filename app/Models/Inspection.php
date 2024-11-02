<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inspection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description',
        'inspection_start',
        'inspection_end',
        'attempts_per_operator',
        'enabled',
        'product',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function parts()
    {
        return $this->hasMany(Part::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}
