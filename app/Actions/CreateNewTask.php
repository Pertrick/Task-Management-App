<?php

namespace App\Actions;

use App\Models\Task;
use App\Models\Priority;

class CreateNewTask
{
    public function execute(array $data)
    {
        $task = Task::where('id', $data['id'])
                    ->where('user_id', auth()->user()->id)
                    ->first();

        if ($task !== null) {
            return tap($task)
                ->update([
                    'name' => $data['task'],
                    'priority_id' => $data['priority_id']
                ])->load('priority.board');
        }
       return auth()->user()->tasks()->create([
            'name' => $data['task'],
            'priority_id' => $data['priority_id'],
        ])->load('priority.board');


    }
}
