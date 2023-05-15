<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Task Management App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-success">
        <div class="container-fluid">
          <a class="navbar-brand text-light" href="{{route('tasks.index')}}">Task Managemnet App</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav text-right">
              <a class="nav-link active text-light" aria-current="page" href="#">{{auth()->user()->name}}</a>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" class="btn btn-light text-light ">
                    @csrf

                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </div>
          </div>
        </div>
      </nav>
    <section class="container-fluid mx-auto">

        <div class="border border-success mt-5 py-5">
            <h3 class="text-success text-center">MY TASK BOARD</h3>
            <div id="success-msg" class="mx-auto w-75 text-center"></div>
            <div class="float-start m-2">
                <b>Priority Legend: </b>
                <span ondblclick="changeColor()" class="bg-success text-center rounded p-1">High</span>
                <span ondblclick="changeColor()" class="bg-warning text-center rounded p-1">Medium</span>
                <span ondblclick="changeColor()" class="bg-danger text-center rounded p-1">low</span>

            </div>
            <div class="float-end mx-3">
                <button class="btn  btn-sm btn-outline-success" onclick="addTask();" >Add Task +</button>
            </div>

            <div class="row mx-auto w-70 " style="" id="row-id">
                <div class="border col-md-3 dropable-high" id="high-id">
                    @foreach ($tasks as $task)
                        @if ($task->priority == 'high')
                            <span class="list-group-item bg-success text-light text-center p-1 draggable-high rounded m-2"
                                id="{{ $task->id }}">
                                {{ $task->name }}
                                <p class="float-end text-dark" style="cursor: pointer"
                                    onclick="removeTask('{{ $task->id }}')"> x </p>

                                <svg onclick="editTask('{{ $task->id }}', '{{ $task->name }}', '{{ $task->priority }}')"
                                    class="float-end" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path
                                        d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                </svg>

                            </span>
                        @endif
                    @endforeach
                </div>
                <div class="border col-md-3 dropable-medium" id="medium-id">
                    @foreach ($tasks as $task)
                        @if ($task->priority == 'medium')
                            <span class="list-group-item bg-warning text-light text-center draggable-medium p-1 rounded m-2"
                                id="{{ $task->id }}">
                                {{ $task->name }}
                                <p class="float-end text-dark" style="cursor: pointer"
                                    onclick="removeTask('{{ $task->id }}')"> x </p>
                                <svg onclick="editTask('{{ $task->id }}', '{{ $task->name }}', '{{ $task->priority }}')"
                                    class="float-end" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path
                                        d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                </svg>
                            </span>
                        @endif
                    @endforeach
                </div>
                <div class="border col-md-3 dropable-low" id="low-id">
                    @foreach ($tasks as $task)
                        @if ($task->priority == 'low')
                            <span class="list-group-item bg-danger text-light text-center draggable-low rounded p-1 m-2"
                                id="{{ $task->id }}">
                                {{ $task->name }}
                                <p class="float-end text-dark" style="cursor: pointer"
                                    onclick="removeTask('{{ $task->id }}')"> x </p>
                                <svg onclick="editTask('{{ $task->id }}', '{{ $task->name }}', '{{ $task->priority }}')"
                                    class="float-end" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path
                                        d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                </svg>
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div id="add-task" class="modal animated rubberBand delete-modal " role="dialog" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class=" float-end btn btn-dark" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <form id="add-new-task">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-center">
                                            <span class="" id="task-header-id"> Add New Task</span>
                                        </div>
                                    </div>

                                    <ul class="d-none" id="error-ul"></ul>
                                    <div class="col-md-12 m-1" id="task-div">
                                        <label for="name" class="float-left">Task</label>
                                        <textarea name="task" id="task-id" cols="20" rows="10" placeholder="Enter Task.."
                                            class="form-control @error('task') is-invalid @enderror"></textarea>
                                    </div>
                                        @error('task')
                                        <div class="error text-danger text-xs">{{ $message }}</div>
                                        @enderror
                                    <div class="col-md-12 m-1" id="priority-div">

                                        <label for="exampleInputEmail1" class="float-left">Priority</label>
                                        <select name="priority" id="select-priority" class="form-control @error('priority') is-invalid @enderror">
                                            <option value="">-- select priority --</option>
                                            <option value="high">High</option>
                                            <option value="medium">Medium</option>
                                            <option value="low">Low</option>
                                        </select>
                                        @error('priority')
                                        <div class="error text-danger text-xs">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <input type="submit" id="save_btn" class="btn btn-success float-end "
                                            value="Save" />
                                        <button type="submit" class="btn btn-danger float-start">Cancel</button>
                                    </div>

                                </div>


                            </div>
                            <!-- /.card-body -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            // alert();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#add-new-task').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "{{ route('tasks.store') }}",
                    data: $('form#add-new-task').serialize(),
                    cache: false,
                    beforeSend: () => {
                        $('#save_btn').attr('disabled', 'disabled');
                        $('#save_btn').val('Saving...');
                    },
                    success: function(element) {

                        $('#' + element.id).remove();
                        console.log(element);
                        var span = "";
                        //    var  encodedElement = JSON.stringify(element);
                        console.log(element);
                        if (element.priority == 'high') {
                            span +=
                                `<span  class="list-group-item bg-success text-light text-center p-1 draggable-high rounded m-2"
                                    id="${element.id}">
                                        ${element.name}
                                        <p class=" float-end text-dark" onclick="removeTask(${element.id })" style="cursor: pointer"> x </p>
                                        <svg onclick="editTask(${element.id}, '${element.name}', '${element.priority}')" class="float-end" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                  </svg>
                                        </span>`;
                            $('#high-id').prepend(span);
                            $(`#${element.id}`).draggable();
                        } else if (element.priority == 'medium') {
                            span +=
                                `<span class="list-group-item bg-warning text-light text-center draggable-medium p-1 rounded m-2" id="${element.id}">
                                        ${element.name}
                                        <p class=" float-end text-dark" onclick="removeTask(${element.id })" style="cursor: pointer"> x </p>
                                        <svg onclick="editTask(${element.id}, '${element.name}', '${element.priority}')" class="float-end" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                  </svg>
                                        </span>`;
                            $('#medium-id').prepend(span);
                            $(`#${element.id}`).draggable();
                        } else {
                            span +=
                                `<span  class="list-group-item bg-danger text-light text-center draggable-low rounded p-1 m-2" id="${element.id}">
                                        ${element.name}
                                        <p class=" float-end text-dark" onclick="removeTask(${element.id })" style="cursor: pointer"> x </p>
                                        <svg onclick="editTask(${element.id}, '${element.name}', '${element.priority}')" class="float-end" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                  </svg>
                                        </span>`
                            $('#low-id').prepend(span);
                            $(`#${element.id}`).draggable();
                        }


                        $('#save_btn').removeAttr('disabled');
                        $('#save_btn').val('Save');
                        $('#add-task').modal('toggle');

                        console.log($('input[name=id]').length);
                        if ($('input[name=id]').length != 0) {
                            $('#success-msg').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Task Updated Successfuly!</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`);
                        } else {
                            $('#success-msg').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Task Added Successfuly!</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`);
                        }
                    },
                    error: (data) => {
                        var priorityError = data.responseJSON.errors.priority;

                        if (priorityError) {
                            var priorityErrorDiv = `
                      <div class="error text-danger text-xs" id="priority-error" >
                     `;
                            priorityError.forEach(prior => {
                                priorityErrorDiv += `<li>${prior} </li>`
                            });

                            priorityErrorDiv += `</div>`;

                            $('#priority-error').remove();
                            $('#priority-div').append(priorityErrorDiv);
                        }

                        var taskError = data.responseJSON.errors.task;
                        console.log(!taskError);
                        if (taskError) {
                            var taskErrorDiv = `
                      <div class="error text-danger text-xs" id="task-error" >
                     `;

                            taskError.forEach(error => {
                                taskErrorDiv += `<li>${error} </li>`
                            });

                            taskErrorDiv += `</div>`;

                            $('#task-error').remove();
                            $('#task-div').append(taskErrorDiv);

                        }

                        $('#save_btn').removeAttr('disabled');
                        $('#save_btn').val('Save');
                    }
                });
            });
        });

        function addTask() {
            $('#task-header-id').text('Add Task');
            $('#task-id').val('');
            $('#select-priority').val('');
            $('input[name=id]').remove();
            $('#add-task').modal('toggle');
        };

        function editTask(id, name, priority) {
            // 
            $('#task-header-id').text('Edit Task');
            $('#task-id').val(name);
            $('#select-priority').val(priority);
            $('form#add-new-task').append(`<input type="hidden" value="${id}" name="id">`);
            $('#add-task').modal('toggle');

        }


        function removeTask(taskId) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "DELETE",
                url: "tasks/" + taskId,
                data: taskId,
                success: function(data) {
                    console.log('here');
                    $('#' + taskId).remove();
                    $('#success-msg').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Task deleted Successfully!</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`);
                },
                error: (data) => {}
            });
        }

        $(function() {
            $(".draggable-high").draggable({
                containment: '#content',
                cursor: 'move',
                snap: '#content',
                containment: $('#row-id'),
                stop: function(event, ui) {

                    console.log($(this.draggable)
                        .find("input")
                        .val($(ui.draggable).text()));
                },

                drag: function(event, ui) {}
            });

            $(".dropable-high").droppable({
                drop: function(event, ui) {
                    $(ui.draggable).detach().css({
                        top: 0,
                        left: 0,
                        right: 0,
                        bottom: 0
                    }).appendTo(this);
                    $(this).addClass("ui-state-highlight");
                    console.log($(ui.draggable).attr("id"));
                    console.log(event.target.id);

                    var draggedId = $(ui.draggable).attr("id");

                    $.ajax({
                        type: "PUT",
                        url: "tasks/" + draggedId,
                        data: {
                            'priority': 'high',
                            'id': draggedId
                        },
                        datatype: "json",
                        success: function(element) {
                            console.log(element);
                            var span = "";
                            console.log(element);
                            span +=
                                `<span  class="list-group-item bg-success text-center text-light rounded m-2  p-1 draggable-high"
                                    id="${element.id}">
                                        ${element.name}
                                        <p class=" float-end text-dark" onclick="removeTask(${element.id })" style="cursor: pointer"> x </p>
                                        <svg onclick="editTask(${element.id}, '${element.name}', '${element.priority}')" class="float-end" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                  </svg>

                                        </span>`;
                            $(`#${element.id}`).remove();
                            $('#high-id').prepend(span);
                            $(`#${element.id}`).draggable();
                        },
                        error: (data) => {}
                    });


                    setTimeout(() => {
                        $(this).removeClass("ui-state-highlight");
                    }, 1000);
                }
            });


            $(".draggable-medium").draggable({
                containment: '#content',
                cursor: 'move',
                snap: '#content',
                containment: $('#row-id'),
                stop: function(event, ui) {
                    // console.log($(this));
                }
            });

            $(".dropable-medium").droppable({
                drop: function(event, ui) {
                    $(ui.draggable).detach().css({
                        top: 0,
                        left: 0,
                        right: 0,
                        bottom: 0
                    }).appendTo(this);
                    $(this).addClass("ui-state-highlight");

                    console.log($(ui.draggable).attr("id"));
                    console.log(event.target.id);

                    var draggedId = $(ui.draggable).attr("id");

                    $.ajax({
                        type: "PUT",
                        url: "tasks/" + draggedId,
                        data: {
                            'priority': 'medium',
                            'id': draggedId
                        },
                        datatype: "json",
                        success: function(element) {
                            console.log(element);
                            var span = "";
                            console.log(element);
                            span +=
                                `<span  class="list-group-item bg-warning text-center text-light rounded m-2 p-1  draggable-medium" 
                                    id="${element.id}">
                                        ${element.name}
                                        <p class=" float-end text-dark" onclick="removeTask(${element.id })" style="cursor: pointer"> x </p>
                                        <svg onclick="editTask(${element.id}, '${element.name}', '${element.priority}')" class="float-end" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                  </svg>
                                        </span>`;
                            $(`#${element.id}`).remove();
                            $('#medium-id').prepend(span);
                            $(`#${element.id}`).draggable();
                        },
                        error: (data) => {}
                    });


                    setTimeout(() => {
                        $(this).removeClass("ui-state-highlight");
                    }, 1000);
                }
            });

            $(".draggable-low").draggable({
                containment: '#content',
                cursor: 'move',
                snap: '#content',
                containment: $('#row-id'),
                stop: function(event, ui) {
                    // console.log($(this));
                }
            });

            $(".dropable-low").droppable({

                drop: function(event, ui) {
                    $(ui.draggable).detach().css({
                        top: 0,
                        left: 0,
                        right: 0,
                        bottom: 0
                    }).appendTo(this);
                    $(this).addClass("ui-state-highlight");

                    console.log($(ui.draggable).attr("id"));
                    console.log(event.target.id);

                    var draggedId = $(ui.draggable).attr("id");

                    $.ajax({
                        type: "PUT",
                        url: "tasks/" + draggedId,
                        data: {
                            'priority': 'low',
                            'id': draggedId
                        },
                        datatype: "json",
                        success: function(element) {
                            console.log(element);
                            var span = "";
                            console.log(element);
                            span +=
                                `<span  class="list-group-item bg-danger text-center text-light rounded m-2 p-1 draggable-low"
                                    id="${element.id}">
                                        ${element.name}
                                        <p class=" float-end text-dark" onclick="removeTask(${element.id })" style="cursor: pointer"> x </p>
                                        <svg onclick="editTask(${element.id}, '${element.name}', '${element.priority}')" class="float-end" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                  </svg>
                                        </span>`;
                            $(`#${element.id}`).remove();
                            $('#low-id').prepend(span);
                            $(`#${element.id}`).draggable();
                        },
                        error: (data) => {}
                    });


                    setTimeout(() => {
                        $(this).removeClass("ui-state-highlight");
                    }, 1000);
                }
            });

        });
    </script>
</body>

</html>
