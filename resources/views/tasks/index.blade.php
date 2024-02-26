@extends('layouts.app')

@section('content')
    <h1>Joshua Task List</h1>

    @foreach($tasks as $task)
    <div class="card @if($task->isCompleted()) border-success @endif" style="margin-bottom: 20px;">
        <div class="card-body">
            <p>
                @if($task->isCompleted())
                    <span class="badge bg-success">Completed</span>
                @endif
                {{ $task->description }}
            </p>

            @if(!$task->isCompleted())
                <form action="/tasks/{{ $task->id }}" method="POST">
                    @method("PATCH")
                    @csrf
                    <button class="btn btn-secondary" input="submit">Complete</button>
                </form>
            @else
                <form action="/tasks/{{ $task->id }}" method="POST">
                    @method("DELETE")
                    @csrf
                    <button class="btn btn-danger" input="submit">Delete</button>
                </form>
            @endif

            <a href="/tasks/edit/{{ $task->id }}" class="btn btn-info btn-block">Edit Task</a>
        </div>
    </div>
    @endforeach

    <a href="/tasks/create" class="btn btn-primary btn-block">New Task</a>
@endsection
