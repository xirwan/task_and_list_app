<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\UserTaskList;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Task::with('list')
        ->whereHas('list', function($query) {
            $query->where('user_id', Auth::user()->id);
        })->orderBy('created_at', 'desc');

        if(request()->has('search'))
        {
            $search = request('search');
            $query->where(function($q) use ($search)
            {
                $q->where('title', 'like', "%{$search}")
                ->Where('description', 'like', "%{$search}");
            });
        }

        if(request()->has('filter') && request('filter') !== 'all')
        {
            $query->where('is_completed', request('filter') === 'completed');
        }

        $tasks = $query->paginate(10);
        $lists = UserTaskList::where('user_id', Auth::user()->id)->get();

        return inertia::render('tasks/index', [
            'tasks' => $tasks,
            'lists' => $lists,
            'filters'=> [
                'search' => request('search', ''),
                'filter' => request('filter', ''),
            ],
            'flash' => [
                'success' => session('success'),
                'error' => session('error')
            ]
        ]);

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
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'list_id' => 'required|exists:lists,id',
            'is_completed' => 'boolean'
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'list_id' => $request->list_id,
            'is_completed' => $request->is_completed
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'list_id' => 'required|exists:lists,id',
            'is_completed' => 'boolean'
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'list_id' => $request->list_id,
            'is_completed' => $request->is_completed
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');

    }
}
