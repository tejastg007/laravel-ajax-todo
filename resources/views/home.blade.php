<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>laravel todo</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css'
        integrity='sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw=='
        crossorigin='anonymous' />
    <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">
    </script>
    <style>
        * {
            padding: 0px;
            margin: 0px;
            box-sizing: border-box
        }

        .tasks {
            max-height: 600px;
            overflow-y: auto;
        }

        i {
            font-size: 20px;
        }

        .displaynone {
            display: none !important;
        }

        .displayblock {
            display: block !important;
        }

    </style>
</head>

<body>

    <div class="container  p-3 mt-3">
        <p class="my-4 h4 text-center">AJAX TODO</p>
        <div class="row">
            <div class="col-12 col-lg-6  p-2 mb-5 m-lg-0 text-capitalize">
                <form action="" method="post" onsubmit="addtask(event)">
                    @csrf
                    <div class="form-group">
                        <label for="">Your todo task:</label>
                        <textarea type="text" class="form-control" name="task" id="task" style="resize:none" rows="2"
                            required></textarea>
                    </div>

                    <div class="alert alert-success alert-dismissible fade show task-success" role="alert"
                        style="display: none !important">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <strong>task added successfully!</strong>
                    </div>
                    <input type="submit" class="btn btn-primary  text-capitalize" value="add task">
                </form>
            </div>
            <div class="col-12 col-lg-6 tasks">
                <h4>Your Tasks:</h4>
                @foreach ($tasks as $task)
                    <div class="border-bottom row px-1 mb-1" @if ($task->status == 1) style="background-color:#cfffdd" @endif>
                        <div class="col-1 d-flex ">
                            <h4>{{ $loop->iteration }}</h4>
                        </div>
                        {{-- enables on edit action --}}
                        <textarea rows="3" style="display: none !important; resize:none"
                            class="col-8 p-0 task-edit row-{{ $task->id }}">{{ $task->task }}</textarea>
                        <a style="display: none !important" href="javascript:void(0)"
                            class="col-3 d-flex align-items-center justify-content-between task-edit-save row-{{ $task->id }}"
                            onclick="taskeditaction({{ $task->id }})">
                            <i class="fas fa-check-double text-success"></i>
                        </a>
                        {{-- enables on edit action end --}}


                        <div class="col-8 p-0 task-content row-{{ $task->id }}">{{ $task->task }}
                            <p class="text-right m-0" style="font-size: 12px">task created at :
                                {{ $task->created_at }} </p>
                            <p class="text-right m-0" style="font-size: 12px">task updated at :
                                {{ $task->updated_at }}</p>
                        </div>
                        <div class="col-3 d-flex align-items-center justify-content-between task-actions row-{{ $task->id }}"
                            id="{{ $task->id }}">
                            <a href="javascript:void(0)" onclick="taskcomplete({{ $task->id }},event)">
                                <i class="fas fa-check-double text-success"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="taskedit({{ $task->id }})">
                                <i class="fas fa-edit text-info "></i>
                            </a>
                            <a href="javascript:void(0)" onclick="taskdelete({{ $task->id }})">
                                <i class="fa fa-window-close text-danger" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function loaddata() {
            $.ajax({
                method: "post",
                url: '{{ url('/loaddata') }}',
                // processData: false,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    $(".tasks").html(data.data);
                }
            });
            // return false;
        }

        $(function() {
            $.ajaxSetup({
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            addtask = function(e) {
                e.preventDefault();
                var task = $("#task").val();
                $.ajax({
                    method: "post",
                    url: '{{ url('/addtask') }}',
                    data: {
                        task: task,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        loaddata();
                        $(".task-success").css("display", "block");
                        $("#task").val("");
                    }
                });
            }


            taskcomplete = function(id, e) {
                $.ajax({
                    method: "post",
                    url: '{{ url('/taskcomplete') }}',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        loaddata();
                    },
                });
            }

            taskedit = function(id) {
                $(".task-actions.row-" + id).addClass("displaynone");
                $(".task-content.row-" + id).addClass("displaynone");
                $(".task-edit-save.row-" + id).css("display", "block");
                $(".task-edit.row-" + id).css("display", "block");
            }

            taskeditaction = function(id, e) {
                var task = $(".task-edit.row-" + id).val();
                $.ajax({
                    method: "post",
                    url: '{{ url('/taskedit') }}',
                    data: {
                        id: id,
                        task: task,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        // alert(data.message)
                        loaddata();
                    }
                });
                return false;
            }

            taskdelete = function(id) {

                if (confirm("are you confirm to delete?")) {
                    $.ajax({
                        method: "post",
                        url: '{{ url('/taskdelete') }}',
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            loaddata();
                        }
                    });
                } else {
                    // alert("cancel")
                }
            }


        });
    </script>
</body>

</html>
