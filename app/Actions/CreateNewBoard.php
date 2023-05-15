<?php

namespace App\Actions;

use App\Models\Board;
use App\Models\Priority;

class CreateNewBoard
{
    public function execute(array $data)
    {

        $board = Board::where('id', $data['id'])
                    ->where('user_id', auth()->user()->id)
                    ->first();

        if ($board !== null) {
            return tap($board)
                ->update([
                    'priority_id' => $data['priority_id']
                ]);
        }

       return auth()->user()->boards()->create([
            'priority_id' => $data['priority_id']
        ]);


    }
}
