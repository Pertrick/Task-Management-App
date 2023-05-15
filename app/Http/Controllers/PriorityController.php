<?php

namespace App\Http\Controllers;

use App\Actions\CreateNewPriority;
use App\Models\Priority;
use Illuminate\Http\Request;
use App\Http\Requests\StorePriorityRequest;
use App\Models\Board;

class PriorityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Priority::whereHas(
            'board.user',
            fn ($q) =>
            $q->where('id',  auth()->user()->id)
        )
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StorePriorityRequest $request,
        CreateNewPriority $createNewPriority
    ) {
        return $createNewPriority->execute($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Priority $priority)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Priority $priority)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Priority $priority)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Priority $priority)
    {
        //
    }
}
