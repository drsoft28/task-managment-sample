<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::orderBy('priority')->get();
        return view('tasks.index',compact('tasks'));
    }

    public function store(){
        $rules = [
            'task'=>'required|unique:tasks,name'
        ];
        $validator=\Validator(request()->all(),$rules);
        if($validator->fails() ) return back()->withErrors($validator->errors());

        $task= new Task();
        $task->name = request('task');
        $task->priority = intVal(Task::max('priority'))+1;
        $task->save();
        session()->flash('success','The task has been added');
        return back();
    }

    public function update($id){
        $task = Task::find($id);
        if(!$task) {
            return response()->json(['success'=>false,'data'=>'The task not found']);
        }
        $rules = [
            'task'=>'required|unique:tasks,name,'.$task->id.',id'
        ];
        $validator=\Validator(request()->all(),$rules);
        if($validator->fails() ) return response()->json(['success'=>false,'type'=>"validation",'data'=>$validator->errors()]);

        
        $task->name = request('task');
        $task->save();
  
        return response()->json(['success'=>true,'data'=>'The task has been added']);
      
    }
    public function reorder(){
        $id = request('id');
        $newIndex = request('newIndex');;
        $task = Task::find($id);
        if(!$task) {
            return response()->json(['success'=>false,'data'=>'The task not found']);
        }
        
        $priority =$task->priority;
        if($priority==$newIndex){
            return response()->json(['success'=>false,'data'=>'Nothing change!']);
        }
        try {
            \DB::beginTransaction();
            $task->priority= $newIndex;
            $task->save();
            if($newIndex<$priority){
                Task::whereRaw('priority between ? and ?  and id!=? ',[$newIndex, $priority,$task->id])
                ->update(['priority' => \DB::raw('priority+1')]);
            }else{
                Task::whereRaw('priority between ? and ?  and id!=? ',[$newIndex, $priority,$task->id])
                ->update(['priority' => \DB::raw('priority-1')]);
            }
           
            \DB::commit();
            return response()->json(['success'=>true,'data'=>"The tasks has been reordered successful"]);
        } catch (\Throwable $th) {

            \DB::rollback();
            return response()->json(['success'=>false,'data'=>$th->getMessage()]);

        }
    }
    public function destroy($id){

        $task = Task::find($id);
        if(!$task) {
            session()->flash('error','The task not found');
            return back();
        }
       $priority =$task->priority;
      
       try {
        \DB::beginTransaction();
        $task->delete();
        Task::where('priority', '>=', $priority)
        ->update(['priority' => \DB::raw('priority-1')]);
        \DB::commit();
       } catch (\Throwable $th) {
        //throw $th;
        \DB::rollback();
        session()->flash('error',$th->getMessage());
        return back();
       }
       
        session()->flash('success','The task has been deleted');
        return back();
    }
}
