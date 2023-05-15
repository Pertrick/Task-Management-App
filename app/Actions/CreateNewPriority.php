<?php

namespace App\Actions;

use App\Models\Board;
use App\Models\Priority;

class CreateNewPriority
{
    public function execute(array $data)
    {
        
        $priority = Priority::where('id', $data['id'])
                            ->first();

        if ($priority !== null) {
            return tap($priority)
                ->update([
                    'name' => $data['priorityName'],
                    'color' => $data['color']
                ]);
        }


       $priority =  Priority::create([
            'name' => $data['priorityName'],
            'color' => $data['color']
       ]);

        auth()->user()->boards()->create([
            'priority_id' => $priority->id
        ]);

       return $priority->load('board');

    }
}
