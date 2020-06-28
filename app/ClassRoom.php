<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    public static $ClassStatuses = [
        'OPENED' => 'opened',
        'CLOSED' => 'closed'
    ];

    protected $fillable = ['name', 'status', 'code', 'description', 'maximum_students'];

    public function students() {
        return $this->hasMany(Student::class, 'class_id');
    }
}
