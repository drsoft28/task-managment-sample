<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Task Management</title>
        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <!-- Styles -->
       <!-- jsDelivr :: Sortable :: Latest (https://www.jsdelivr.com/package/npm/sortablejs) -->
        <script>
            const Toast = Swal.mixin({
                            toast: true,
                            position: 'center',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                            })


            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });
            </script>
        <style>
            body {
                font-family: 'Nunito';
            }
            .mt-5{
                margin-top: 20px;
            }
            .flex{
                display: flex;
            }
            .flex-grow-1{
                flex-grow: 1
            }
            .flex-grow-0{
                flex-grow: 0
            }
            .form-inline{
                display: inline-block;
                
                
            }
            .glyphicon-trash{
                color: rgb(233, 101, 101)
            }
        </style>
    </head>
    <body class="antialiased">
    <nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Task Management</a>
    </div>
        </nav>
        <div class="container">
       <section>
        <h1>Tasks List</h1>
        @if(session()->has('success'))
        <div class="alert alert-success" role="alert">{{session()->get('success')}}</div>
        @endif
        @if(session()->has('error'))

        <div class="alert alert-danger" role="alert">
            {{session()->get('error')}}
        </div>
        @endif
@if(count($errors)>0)

<div class="alert alert-warning" role="alert">
    {{$errors->first('task')}}
</div>
@endif
        <div>
            <form method="post" action="{{route('tasks.store')}}">
                {{csrf_field()}}
            <div class="row">
    <div class="col-lg-6">
 
        <div class="col-lg-6">
            <div class="input-group">
            <input type="text" class="form-control" name="task" placeholder="New Task">
            <span class="input-group-btn">
                <input type="submit" class="btn btn-default" type="button"  value="Add"/>
            </span>
            </div>
        </div>
    </div>
        </form>
        </div>
        <ul id="tasks" class="list-group mt-5">
         @if($tasks->count()==0)
         <li class="list-group-item">No Task</li>
         @else

            @foreach($tasks as $task)
            <li class="list-group-item flex"  data-item="{{$task->id}}">
                <span class="flex-grow-1 flex">#<span data-priority="true">{{$task->priority}}</span>
            -<span  class="flex-grow-1" id="task-name-{{$task->id}}">{{$task->name}}</span> 
            <div style="display: none" id="form-update-{{$task->id}}" class="flex-grow-1 form-inline" >
                <div class="row">
                    <div class="col-lg-12">
                 
                      
                            <div class="input-group">
                            <input type="text" class="form-control"  name="task" placeholder="Edit Task">
                            <span class="input-group-btn">
                                <button class="btn btn-default" onclick="updateTask({{$task->id}},'{{route('tasks.update',$task->id)}}')" type="button"  >update</button>
                            </span>
                            <span class="input-group-btn">
                                <button  class="btn btn-default" onclick="hideInput({{$task->id}})" type="button"  >Cancel</button>
                            </span>
                         
                        </div>
                    </div>
                </div>
            </div>
            </span>
            <div class="flex-grow-0">
            <button onclick="showInput({{$task->id}})"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                <form class="form-inline" action="{{route('tasks.destroy',$task->id)}}" method="POST">@method('DELETE') <button><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                    {{csrf_field()}}
                </form>
                
                <div>
                </li>
            @endforeach
            @endif
        </ul>
        
        </section>
        </div>
        <script>
           
            $(function(){
                var el = document.getElementById('tasks');

              var sortable_tasks=  new Sortable(el, 
              {
                animation: 150,
                ghostClass: 'blue-background-class',
                onSort: async function (evt) {
                                var itemEl = evt.item; 
                               
                                    await $.ajax({url: "{{route('tasks.reorder',)}}",method:'POST',
                                    data:{
                                        id:$(itemEl).attr('data-item'),
                                        newIndex:evt.newIndex+1,
                                        oldIndex:evt.oldIndex+1
                                    },
                                    async: true,
                                    success: function(res){
                                            console.log(res)
                                                if(res.success){
                                                    Toast.fire({
                                                        icon: 'success',
                                                        title: res.data
                                                        });
                                                        $( "#tasks>li" ).each(function( index ) {
                                                            console.log(index,this)
                                                            console.log(index,$(this))
                                                            console.log(index,$(this).children("span:first-child").children("[data-priority]").length)
                                                                $(this).children("span:first-child").children("[data-priority]").html(index+1);
                                                                
                                                            });
                                                }else{
                                                   
                                                    Toast.fire({
                                                        icon: 'error',
                                                        title: res.data
                                                        })
                                                    reorder(evt)
                                                }
                                            },
                                    error:function(err){
                                        reorder(evt)
                                                }

                                    });

                }//onsort

        });

        function reorder(evt){
                var oldId = evt.oldIndex,
                newId = evt.newIndex,
                reArrange = sortable_tasks.toArray(),
                oldSort = sortable_tasks.toArray();

                if (oldId < newId) {
                    for (var i = oldId; i < newId; i++)
                        reArrange[i+1] = oldSort[i];
                } else {
                    for (var i = newId + 1; i <= oldId; i++)
                        reArrange[i-1] = oldSort[i];
                }

                reArrange[oldId] = oldSort[newId];
                sortable_tasks.sort(reArrange);
        }
            });//ready

            function showInput(id,){
                        $(`#task-name-${id}`).hide();
                        $(`#form-update-${id}`).css("display","inline-block");
                        $(`#form-update-${id} input[name="task"]`).val( $(`#task-name-${id}`).text());
                        
            }
            function hideInput(id){
                        $(`#task-name-${id}`).show();
                        $(`#form-update-${id}`).hide();
                        
                        
            }
            function updateTask(id,url){
                var value=$(`#form-update-${id} input[name="task"]`).val();
                 $.ajax({url: url,method:'PUT',
                                    data:{
                                        task: value
                                    },
                                    async: true,
                                    success: function(res){
                                        if(res.success){
                                                    Toast.fire({
                                                        icon: 'success',
                                                        title: res.data
                                                        });
                                                        $(`#task-name-${id}`).text(value)
                                                        hideInput(id)
                                                }else{
                                                    if(res.type=='validation'){
                                                    Toast.fire({
                                                        icon: 'error',
                                                        title: res.data.task[0]
                                                        })
                                                   }else
                                                    Toast.fire({
                                                        icon: 'error',
                                                        title: res.data
                                                        })
                                                  
                                                }
                                    },
                                    error:function(error){

                                    }
                                });
            }
           
            </script>
    </body>
</html>
