<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Board;
use App\Actions\CreateNewTask;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $boards = Board::with('priority','tasks.priority')
            ->where('user_id', auth()->user()->id)
            ->get();

            // dd($tasks);

        return view('tasks.index', compact('boards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreTaskRequest $request,
        CreateNewTask $createNewTask
    ) {
        return $createNewTask->execute($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $priorityId = Board::where("id", $request->boardId)->pluck('priority_id')->first();
        return tap($task)->update(['priority_id' => $priorityId])->load('priority.board');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return true;
    }
}
