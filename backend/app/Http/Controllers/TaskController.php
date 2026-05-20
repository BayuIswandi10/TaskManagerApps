<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->tasks();

        if ($request->has('sort_by')) {
            $sortBy = $request->sort_by; // date, description, priority
            if ($sortBy === 'date') {
                $query->orderBy('due_date');
            } elseif ($sortBy === 'description') {
                $query->orderBy('description');
            } elseif ($sortBy === 'priority') {
                $query->orderByRaw("CASE priority WHEN 'High' THEN 1 WHEN 'Medium' THEN 2 WHEN 'Low' THEN 3 ELSE 4 END");
            }
        } else {
            $query->orderBy('due_date', 'asc');
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'in:Low,Medium,High',
        ]);

        $task = $request->user()->tasks()->create($request->only('title', 'description', 'due_date', 'priority'));

        return response()->json($task, 201);
    }

    public function show(Request $request, $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);
        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);

        $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'date',
            'priority' => 'in:Low,Medium,High',
            'is_completed' => 'boolean',
        ]);

        $task->update($request->all());

        return response()->json($task);
    }

    public function destroy(Request $request, $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted']);
    }

    public function markAsCompleted(Request $request, $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);
        $task->update(['is_completed' => true]);

        return response()->json($task);
    }
}
