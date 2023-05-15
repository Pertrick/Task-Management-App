<?php

namespace App\Models;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Board extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'priority_id'];

    public function priority(){
        return $this->belongsTo(Priority::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }


    public function tasks(){
        return $this->hasMany(Task::class);
    }


    protected static function boot() {
        parent::boot();
    
        static::deleting(function($board) { 
            $board->priority->delete();
        });
    }

}
