<?php

namespace App\Models;

use App\Models\Priority;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'priority_id',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function priority(){
        return $this->belongsTo(Priority::class);
    }

    public function board(){
        return $this->belongsTo(Board::class);
    }

 
}
