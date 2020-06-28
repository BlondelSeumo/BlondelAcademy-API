<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $casts = [
        'birth_date' => 'date'
    ];

    protected $with = ['currentClass'];

    public $fillable = [
        'first_name', 'last_name', 'class_id', 'birth_date'
    ];

    public function currentClass() {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }
}
