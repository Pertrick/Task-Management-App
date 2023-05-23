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
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand text-light" href="{{ route('tasks.index') }}">Task Managemnet App</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <a class="nav-link active text-light" aria-current="page" href="#">{{ auth()->user()->name }}</a>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" class="btn btn-sm btn-light text-light ">
                    @csrf

                    <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </div>
        </div>
    </nav>
    <section class="container-fluid" id="content">

        <div class="mt-3 py-3">
            <h3 class="text-dark text-center">MY TASK BOARD</h3>
            <div id="success-msg" class="mx-auto w-75 text-center"></div>
            <div class="float-start m-2 priorityClass">
                <b>Priority Legend: </b>
                @foreach ($boards as $board)
                    <span ondblclick="changeColor()" id="{{ $board->priority->id }}" class="text-center rounded p-1"
                        style="background-color: {{ $board->priority->color }}">{{ $board->priority->name }}</span>
                @endforeach

            </div>
            <div class="float-end mx-3">
                <button class="btn  btn-sm btn-outline-warning mr-1" onclick="addBoard();">Add Board +</button>
                <button class="btn  btn-sm btn-outline-primary" {{ $boards->isEmpty() ? 'disabled' : '' }}
                    onclick="addTask();" id="add-task-id">Add Task +</button>
            </div>

            {{-- <div style="float:none; clear:right"></div> --}}
            <div class="row mx-auto w-100 text-center" id="row-id">
                @foreach ($boards as $board)
                    <div class="border col-md-4 dropable" id="{{ $board->id }}">
                        <span class="float-end text-light btn btn-sm btn-dark" style="cursor: pointer;"
                            onclick="removeBoard('{{ $board->id }}', '{{ $board->priority->id }}')"> x </span>
                        @foreach ($board->priority->tasks as $task)
                            @if ($board->priority->id == $task->priority->id)
                                <span class="list-group-item text-light text-center p-1 draggable rounded my-1 me-5"
                                    id="{{ $task->id }}" style="background-color: {{ $task->priority->color }}">
                                    {{ $task->name }}
                                    <p class="float-end text-dark" style="cursor: pointer"
                                        onclick="removeTask('{{ $task->id }}')"> x </p>

                                    <svg onclick="editTask('{{ $task->id }}', '{{ $task->name }}', '{{ $task->priority }}')"
                                        class="float-end" xmlns="http://www.w3.org/2000/svg" width="16"
                                        height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path
                                            d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                    </svg>

                                </span>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>

        </div>

        <!--Add Board --- --->

        <div id="add-board" class="modal animated rubberBand delete-modal " role="dialog" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class=" float-end btn btn-dark" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <form id="add-new-board">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-center">
                                            <span class="" id="task-header-id"> Add New Board</span>
                                        </div>
                                    </div>

                                    <div class="col-md-12 m-1" id="priority-div">
                                        <label for="exampleInputEmail1" class="float-left">Priority</label>
                                        <input type="text" class="form-control" name="priorityName"
                                            id="priority-name-id">
                                        @error('priorityName')
                                            <div class="error text-danger text-xs">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    <div class="col-md-12 m-1" id="priority-div">
                                        <label for="exampleInputEmail1" class="float-left">Priority Color</label>
                                        <input type="color" class="form-control" name="color" id="color-id">

                                        @error('color')
                                            <div class="error text-danger text-xs">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <input type="submit" id="save_board_btn" class="btn btn-success float-end "
                                            value="Save" />
                                        <button type="button" class="btn btn-danger float-start cancel"
                                            data-dismiss="modal">Cancel</button>
                                    </div>

                                </div>
                            </div>
                            <!-- /.card-body -->
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- end Add Board -->

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
                                    <div class="col-md-12 m-1" id="select-priority-div">

                                        <label for="exampleInputEmail1" class="float-left">Priority</label>
                                        <select name="priority_id" id="select-priority"
                                            class="form-control @error('priority') is-invalid @enderror">
                                            <option value="">-- select priority --</option>
                                            {{-- @foreach ($boards as $board)
                                                <option value="{{ $board->priority->id }}">
                                                    {{ $board->priority->name }}</option>
                                            @endforeach --}}
                                        </select>
                                        @error('priority')
                                            <div class="error text-danger text-xs">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <input type="submit" id="save_btn" class="btn btn-success float-end "
                                            value="Save" />
                                        <button type="button" class="btn btn-danger float-start cancel"
                                            data-dismiss="modal">Cancel</button>
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
        var boardCount = {{ count($boards) }};

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
                    success: function(task) {

                        $('#' + task.id).remove();
                        console.log(task);
                        var span = `
                                <span
                                    class="list-group-item text-light text-center p-1 draggable rounded my-1 me-5"
                                    id="${ task.id }"  style="background-color: ${ task.priority.color }">
                                      ${task.name}
                                    <p class="float-end text-dark" style="cursor: pointer"
                                        onclick="removeTask('${ task.id }')"> x </p>

                                    <svg onclick="editTask('${ task.id }', '${ task.name}', ${task.priority})"
                                        class="float-end" xmlns="http://www.w3.org/2000/svg" width="16"
                                        height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path
                                            d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                    </svg>

                                </span> `;
                        $(`#${task.priority.board.id}`).append(span);
                        $(`#${task.id}`).draggable();


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
                        var priorityError = data.responseJSON.errors.priority_id;

                        if (priorityError) {
                            var priorityErrorDiv =
                                `<div class="error text-danger text-xs" id="priority-error" >`;
                            priorityError.forEach(prior => {
                                priorityErrorDiv += `<li>${prior} </li>`
                            });

                            priorityErrorDiv += `</div>`;

                            console.log(priorityError);
                            console.log(priorityErrorDiv);

                            $('#priority-error').remove();
                            $('#select-priority-div').append(priorityErrorDiv);
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


        //Add Board

        function addBoard() {
            $('#add-board').modal('toggle');
        }


        $('#add-new-board').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('priorities.store') }}",
                data: $('form#add-new-board').serialize(),
                beforeSend: () => {
                    $('#save_board_btn').attr('disabled', 'disabled').val('Saving...');
                },
                success: function(priority) {
                    $('#priority-name-id').val('');
                    $('#color-id').val('');
                    console.log(priority);

                    var board = `<div class="border col-md-4 dropable" id="${priority.board.id}">
                                    <span class="float-end text-light btn btn-sm btn-dark" id='${priority.id}' style="cursor: pointer;"
                                        onclick="removeBoard('${priority.board.id}', '${priority.id}')"> x </span>
                                </div>`;

                    var span = `<span ondblclick="changeColor()" id="${priority.id}" class="text-center rounded p-1"
                        style="background-color: ${priority.color}">${priority.name }</span>`;


                    addDroppable();
                    $('#row-id').append(board);
                    $('.priorityClass').append(span);


                    console.log(priority);

                    incrementBoardCount();
                    fetchPriority();

                    $('#save_board_btn').removeAttr('disabled').val('Save');
                    $('#add-board').modal('toggle');
                },
                error: function(data) {

                    console.log(data.responseJSON.errors);
                    var priorityError = data.responseJSON.errors.priorityName;

                    if (priorityError) {
                        var priorityErrorDiv = `
                            <div class="error text-danger text-xs" id="priority-error" >`;
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
                    $('#save_board_btn').removeAttr('disabled').val('Save');
                    console.log(element);
                }
            });
        });


        function addTask() {
            $('#task-header-id').text('Add Task');
            $('#task-id').val('');
            $('#select-priority').val('');
            $('input[name=id]').remove();
            fetchPriority();
            $('#add-task').modal('toggle');
        };

        function editTask(id, name, priority) {
            // 
            $('#task-header-id').text('Edit Task');
            $('#task-id').val(name);
            $('#select-priority').val(priority);
            $('form#add-new-task').append(`<input type="hidden" value="${id}" name="id">`);
            console.log(priority);
            fetchPriority(priority);
            $('#add-task').modal('toggle');

        }

        function removeBoard(boardId, priorityId) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "DELETE",
                url: "boards/" + boardId,
                data: boardId,
                success: function(data) {
                    console.log('here');
                    $('#' + boardId).remove();
                    $('#' + priorityId).remove();
                    fetchPriority();
                    decrementBoardCount();
                },
                error: (data) => {}
            });
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

        addDroppable();

        function addDroppable() {

            $(function() {
                $(".draggable").draggable({
                    containment: '#content',
                    cursor: 'move',
                    snap: '#content',
                    revert: "invalid",
                    // containment: $('#row-id'),
                    stop: function(event, ui) {

                        console.log($(this.draggable)
                            .find("input")
                            .val($(ui.draggable).text()));
                    },

                    drag: function(event, ui) {}
                });


                $(".dropable").droppable({
                    drop: function(event, ui) {
                        $(ui.draggable).detach().css({
                            top: 0,
                            left: 0,
                            right: 0,
                            bottom: 0
                        }).appendTo(this);
                        $(this).addClass("ui-state-highlight");
                        console.log($(ui.draggable).attr("id"));
                        const boardId = event.target.id;
                        console.log("target: " + event.target.id);

                        var draggedId = $(ui.draggable).attr("id");

                        console.log("board: " + draggedId);

                        $.ajax({
                            type: "PUT",
                            url: "tasks/" + draggedId,
                            data: {
                                'boardId': boardId,
                                'id': draggedId
                            },
                            datatype: "json",
                            success: function(task) {
                                console.log(task);

                                var priority = JSON.stringify(task.priority);
                                console.log(priority);
                                var span = `
                                <span
                                    class="list-group-item text-light text-center p-1 draggable rounded my-1 me-5"
                                    id="${ task.id }"  style="background-color: ${ task.priority.color }">
                                      ${task.name}
                                    <p class="float-end text-dark" style="cursor: pointer"
                                        onclick="removeTask('${ task.id }')"> x </p>

                                    <svg onclick="editTask('${ task.id }','${ task.name}',${task.priority})"
                                        class="float-end" xmlns="http://www.w3.org/2000/svg" width="16"
                                        height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path
                                            d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                    </svg>

                                </span> `;
                                $(`#${task.priority.board.id}`).append(span);
                                $(`#${task.id}`).remove();
                                $(`#${task.id}`).draggable();

                                console.log(task.priority.board.id);
                                // $('#high-id').prepend(span);
                                // $(`#${element.id}`).draggable();
                            },
                            error: (data) => {}
                        });


                        setTimeout(() => {
                            $(this).removeClass("ui-state-highlight");
                        }, 1000);
                    }
                });

            });
        }



        function incrementBoardCount() {
            boardCount++;
            if (boardCount > 0) {
                $('#add-task-id').prop('disabled', false);
            }

        }

        function decrementBoardCount() {
            boardCount--;
            console.log("decre: " + boardCount);
            if (boardCount == 0) {
                $('#add-task-id').prop('disabled', true);
            }
        }

        function fetchPriority(priority = "") {

            priority = (isJson(priority)) ? JSON.parse(priority) : priority;
            // var priority  = JSON.parse(priorityObject);
            // var priority;
            // console.log(typeof(priority));
            // if(priorityObject == 'undefined'){
            //     priority = ""; 
            // }else{

            // }

            $.get('{{ route('priorities.index') }}', function(data, status) {
                // console.log(priorityId);
                var options = (priority) ?
                    `<option value="" > -select priority - </option>` :
                    `<option value="" selected> -select priority - </option> `;
                $.each(data, function(key, value) {
                    console.log(priority.id);


                    if (priority.id == value.id) {
                        options += `<option value="${value.id}" selected>${value.name}</option>`;
                    } else {
                        options += `<option value="${value.id}">${value.name}</option>`;
                    }

                })

                console.log(options);
                $('#select-priority').html(options);
            });
        }

        $('button.cancel').on('click', function() {
            $('#add-task').modal('hide');
            $('#add-board').modal('hide');
        });


        function isJson(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }
    </script>
</body>

</html>
