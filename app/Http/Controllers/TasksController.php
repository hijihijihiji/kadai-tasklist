<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {

        $tasks = \Auth::user()->tasks;
        
        return view('tasks.index', [
            'tasks' => $tasks,
            ]);
            
        }else {
            
            return view('welcome');
            
        }
    }
    
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::check()) {
            
        $task = new Task;
        
        return view('tasks.create', [
            'task' => $task,
            ]);
            
        }else {
            
            return redirect('/');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::check()) {
        
        $this->validate($request, [
            'status' => 'required|max:10',
            ]);
        
        $task = new Task;
        $user = \Auth::user();
        $task->user_id = $user->id;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        
        return redirect('/');
        
        }else {
            return view('welcome');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id); 
        if (\Auth::user()->id == $task->user_id) {
        
            return view('tasks.show', [
                'task' => $task,
                ]);
        }else {
            return redirect('/'); 
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);
        if (\Auth::user()->id == $task->user_id) {
            return view('tasks.edit', [
                'task' => $task,
                ]);
        }else {
            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required|max:10',
            ]);
        $task = Task::find($id);
        if (\Auth::user()->id == $task->user_id) {
            $task->status = $request->status;
            $task->content = $request->content;
            $task->save();
        }
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        if (\Auth::user()->id == $task->user_id) {
            $task->delete();
        }
        return redirect('/');
    }
}
