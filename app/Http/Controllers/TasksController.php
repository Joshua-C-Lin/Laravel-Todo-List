<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;

class TasksController extends Controller
{
    public function index() {
        // Retrieve all of the tasks when we visit the homepage
        $tasks = Task::orderBy('completed_at')
            ->orderBy('id', 'DESC')
            ->get();

        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }

    public function create() {
        return view('tasks.create');
    }

    public function store(Request $request) {
        $request->validate([
            'description' => 'required|max:30',
        ]);

        $task = Task::create([
            'description' => $request->description,
        ]);

        return redirect('/');
    }




    public function update($id) {
        $task = Task::where('id', $id)->first();

        $task->completed_at = now();
        $task->save();

        return redirect('/');
    }

    public function delete($id) {
        $task = Task::where('id', $id)->first();

        $task->delete();

        return redirect('/');
    }

    // Todo add EDIT function
    public function edit($id) {
        $task = Task::where('id', $id) ->first();

        $task->edit_at = now();

        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    public function updateDescription(Request $request, $id) {
        $validator = Validator::make(
            ['description' => $request->input('description')],
            ['description' => 'required|max:30']
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $task = Task::findOrFail($id);
            $task->description = $request->input('description');
            $task->save();

            return redirect('/');
        }
    }
}
