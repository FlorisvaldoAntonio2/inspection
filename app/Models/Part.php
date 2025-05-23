<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Part extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        'code',
        'description',
        'status',
        'inspection_id',
    ];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}
