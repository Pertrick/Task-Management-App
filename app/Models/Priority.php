<?php

namespace App\Models;

use App\Models\Task;
use App\Models\Board;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Priority extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color'
    ];

    public function tasks(){
        return $this->hasMany(Task::class);
    }


    public function board(){
        return $this->hasOne(Board::class);
    }
}
