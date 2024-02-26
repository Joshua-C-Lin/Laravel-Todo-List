@extends('layouts.app')

@section('content')
    <h1>Edit Task</h1>
    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/tasks/update/{{ $task->id }}">
        @csrf
        @method('POST')
        <input type="text" id="descriptionTxt" name="description" value="{{ $task->description }}" style="margin-bottom: 20px;">

        <input type="hidden" name="id" value="{{ $task->id }}" />

        <div class="form-group" style="margin-bottom: 40px;">
            <button type="submit" class="btn btn-info">Update Task</button>
        </div>

        <div class="form-group">
            <button onclick="testInput()" type="button" class="btn btn-info">Get Length</button>
        </div>
    </form>

    <script>
        function testInput() {
            var description = document.getElementById('descriptionTxt').value;
            console.log(description.length, description);
        }
    </script>

@endsection

