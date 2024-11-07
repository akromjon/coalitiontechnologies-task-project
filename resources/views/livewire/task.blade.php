<div>
    <div class="content">
        <h1>Tasks:</h1>
        <form wire:submit="save">
            <input wire:model="name" placeholder="add a new task..." type="text" name="name">
            <button type="submit">add a task</button>
        </form>
        @error('name') <span class="error">{{ $message }}</span> @enderror
        <ul id="sortable-list" class="sortable-list">
            @foreach($tasks as $key => $task)
            <li task-id="{{$task->id}}">{{$task->priority}} - {{$task->name}} | <a
                    wire:click="edit({{$task->id}})">edit</a>
                <a wire:click="delete({{$task}})">delete</a>
            </li>
            @endforeach
        </ul>
    </div>
</div>
