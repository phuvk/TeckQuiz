@extends('layouts.app')
@section('title', 'Manage class - TeckQuiz')
@section('content')
    <style>
        body {
            padding-top: 70px;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link" id="v-pills-class" data-toggle="pill" href="#class-tab" role="tab" aria-controls="v-pills-class"
                            aria-expanded="true">Class</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="v-pills-class" data-toggle="pill" href="#quiz-tab" role="tab" aria-controls="v-pills-quizzes"
                            aria-expanded="true">Quizzes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" id="v-pills-students" data-toggle="pill" href="#students-tab" role="tab" aria-controls="v-pills-students"
                            aria-expanded="true">Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="v-pills-settings" data-toggle="pill" href="#settings-tab" role="tab" aria-controls="v-pills-settings"
                            aria-expanded="true">Settings</a>
                    </li>
                </ul>
            </nav>

            <main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">             
                <h3>{{ $quiz_class->subject_code }}: {{ $quiz_class->subject_desc }}</h3>
                <h5><span class="badge badge-primary">{{ $quiz_class->course_sec }}</span></h5>
                <hr>

                <div class="tab-content col" id="v-pills-tabContent">
                    <div class="tab-pane fade" id="class-tab" role="tabpanel" aria-labelledby="class-tab">
                        <h4>Statistics</h4>
                    </div>

                    <div class="tab-pane fade" id="quiz-tab" role="tabpanel" aria-lavelledby="quiz-tab">
                        <script>
                            $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                            });

                            function ChangeQuizStatus(quiz_event_id, quiz_status){
                                $.post("/quiz/changestatus", {quiz_event_id, quiz_status}, function(data){
                                    var response = jQuery.parseJSON(data)
                                    var qid = quiz_event_id
                                    if (response.status == 0){
                                        if (quiz_status == 0){//Disables the quiz
                                            $("#status" + qid).html("Disabled");
                                            $("#buttonPanel" + qid).html("<button href=\"\" onclick=\"javascript:ChangeQuizStatus(" + quiz_event_id + ", 1)\" class=\"btn btn-sm btn-primary\">Enable Quiz</button> <button class=\"btn btn-sm btn-primary\">Manage Quiz</button>");
                                        }else if(quiz_status == 1){//Enables the quiz
                                            $("#status" + qid).html("Enabled");
                                            $("#buttonPanel" + qid).html("<button href=\"\" onclick=\"javascript:ChangeQuizStatus(" + quiz_event_id + ", 0)\" class=\"btn btn-sm btn-primary\">Disable Quiz</button> <button href=\"\" onclick=\"javascript:ChangeQuizStatus(" + quiz_event_id + ", 2)\" class=\"btn btn-sm btn-primary\">End Quiz</button> <button href=\"\" class=\"btn btn-sm btn-primary\">Manage Quiz</button>");
                                        }else if(quiz_status == 2){//Ends the quiz
                                            $("#status" + qid).html("Ended");
                                            $("#buttonPanel" + qid).html("<button href=\"\" onclick=\"javascript:SeeQuizResults(" + quiz_event_id + ")\" class=\"btn btn-sm btn-primary\">Results</button> <button class=\"btn btn-sm btn-primary\">Manage Quiz</button>");
                                        }
                                    }else{
                                        alert("Something happened! Quiz not started!");
                                    }
                                });
                            }
                            
                        </script>

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Topic</th>
                                    <th>Subject</th>
                                    <th>Class</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quiz_events as $qe)
                                    <tr id="quiz_entry{{ $qe->quiz_event_id }}">
                                        <td>{{ $qe->quiz_event_name }}</td>
                                        <td>{{ $qe->subject_desc }}</td>
                                        
                                        @if($qe->quiz_event_status == 0)
                                            <td id="status{{ $qe->quiz_event_id }}">Disabled</td>
                                            <td id="buttonPanel{{ $qe->quiz_event_id }}">
                                                <button href="" onclick="javascript:ChangeQuizStatus({{ $qe->quiz_event_id }}, 1)" class="btn btn-sm btn-primary">Enable Quiz</button>
                                                <button class="btn btn-sm btn-primary">Manage Quiz</button>
                                            </td>
                                        @elseif($qe->quiz_event_status == 1)
                                            <td id="status{{ $qe->quiz_event_id }}">Started</td>
                                            <td id="buttonPanel{{ $qe->quiz_event_id }}">
                                                <button href="" onclick="javascript:ChangeQuizStatus({{ $qe->quiz_event_id }}, 0)" class="btn btn-sm btn-primary">Disable Quiz</button>
                                                <button href="" onclick="javascript:ChangeQuizStatus({{ $qe->quiz_event_id }}, 2)" class="btn btn-sm btn-primary">End Quiz</button>
                                                <button href="" class="btn btn-sm btn-primary">Manage Quiz</button>
                                            </td>
                                        @else
                                            <td id="status{{ $qe->quiz_event_id }}">Ended</td>
                                            <td id="buttonPanel{{ $qe->quiz_event_id }}">
                                                <button href="" onclick="javascript:alert('Soon!')" class="btn btn-sm btn-primary">Results</button>
                                                <button href="" class="btn btn-sm btn-primary">Manage Quiz</button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> 

                    <div class="tab-pane fade show active row" id="students-tab" role="tabpanel" aria-labelledby="students-tab">
                        <div class="col-8">
                            <h2 class="text-left">Classlist</h2>
                            <table class="table table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>Name</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $s)
                                    <tr>
                                        <td>{{ $s->family_name }}, {{ $s->given_name }} {{ $s->ext_name }} {{ $s->middle_name }}
                                            </td>
                                        <td>
                                            <button 
                                                type="button"
                                                class="btn btn-primary btn-sm"
                                                data-toggle="modal"
                                                data-target="#StudentProfileModal"
                                                data-usrid="{{ $s->usr_id }}"
                                                data-gname="{{ $s->given_name }}"
                                                data-fname="{{ $s->family_name }}"
                                                data-miname="{{ $s->middle_name }}"
                                                data-nename="{{ $s->ext_name }}">
                                                Edit
                                            </button>
                                            <button class="btn btn-primary btn-sm btn-danger" href="#">Delete</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <button 
                            class="btn btn-primary"
                            data-toggle="modal"
                            data-target="#AddNewStudentModal"
                            disabled>
                            Add new student
                        </button>
                    </div>

                    <div class="tab-pane fade" id="settings-tab" role="tabpanel" aria-labelledby="settings-tab">
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptatum, dignissimos.</p>
                        <h4>Advanced Settings</h4>
                        <div class="card" style="width: 40rem;">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <button class="btn btn-warning" style="float: right">Disable this class</button>
                                    <strong>Disable this class</strong>
                                    <p>If your class has move up to another grade, you can disable the class here. Disabling will make the class read-only.</p>
                                </li>
                                <li class="list-group-item">
                                    <button class="btn btn-danger" style="float: right">Delete this class</button>
                                    <strong>Delete this class</strong>
                                    <p>Once you delete this class, there is no turning back.</p>
                                </li>
                            </ul>
                        </div>
                    </div>     

                </div>
            </main>
   
            <div class="modal fade" id="StudentProfileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Student Profile</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form">
                                <input type="hidden" id="usrid" value="-1">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control m-1" placeholder="Family Name" id="f-name" style="width:12rem">
                                        <input type="text" class="form-control m-1" placeholder="Given Name" id="g-name" style="width:12rem">
                                        <input type="text" class="form-control m-1" placeholder="M.I." id="mi-name" style="width:4rem">
                                        <input type="text" class="form-control m-1" placeholder="Extension Name" id="ne-name" style="width:12rem">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="UpdateProfile">Update Profile</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="modal fade" id="AddNewStudentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add New Student</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form">
                                <input type="hidden" id="usrid" value="-1">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control m-1" placeholder="Family Name" id="f-name" style="width:12rem">
                                        <input type="text" class="form-control m-1" placeholder="Given Name" id="g-name" style="width:12rem">
                                        <input type="text" class="form-control m-1" placeholder="M.I." id="mi-name" style="width:4rem">
                                        <input type="text" class="form-control m-1" placeholder="Extension Name" id="ne-name" style="width:12rem">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="UpdateProfile">Update Profile</button>
                        </div>
                    </div>
                </div>
            </div> -->

            <script>
                $('#StudentProfileModal').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget) // Button that triggered the modal
                    var sid = button.data('usrid')
                    var gname = button.data('gname')
                    var fname = button.data('fname')
                    var miname = button.data('miname')
                    var nename = button.data('nename')
                    var act = button.data('act')

                    var modal = $(this)
                    modal.find('#g-name').val(gname)
                    modal.find('#f-name').val(fname)
                    modal.find('#mi-name').val(miname)
                    modal.find('#ne-name').val(nename)
                    modal.find('#usrid').val(sid)
                });

                $('#UpdateProfile').click(function (){
                    var modal = $(this)
                    var g = $('#g-name').val()
                    var f = $('#f-name').val()
                    var mi = $('#mi-name').val()
                    var ne = $('#ne-name').val()
                    var sid = $('#usrid').val()
                    var act = $('#act').val()
                    
                    $.ajax({
                        url: '/student/update',
                        type: 'POST',
                        data: {g, f, mi, ne, sid, act},
                        success: function(result) {
                            location.reload(true);
                        }
                    });
                });
            </script>
        </div>
    </div>
@endsection