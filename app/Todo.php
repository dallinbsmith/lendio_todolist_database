<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Session\Store;

class Todo extends Model
{
    protected $fillable = ['title', 'content', 'creator_id'];


    //     public function getTodos(Store $session, Request $request)
    // {
    //     $todos = Todo::all();
    //     foreach ($todos as $todo) {
    //         // $data = $request->session()->get('user')->id;
    //             if ($todo->id === $request->session()->get('user')->id){
    //             $todosArr[] = ['title' => $todo->title, 'content' => $todo->content, 'id' => $todo->id];
    //         }
    //     }
    //     return response()->json($todosArr, 201);
    //     return $session->get('todos');

    //     if (!$session->has('todos')) {
    //         $this->firstLogon($session);
    //     }
    //     return $session->get('todos');

    //     $todos = Todo::all();
    //     foreach ($todos as $todo) {
    //         $todosArr[] = ['title' => $todo->title, 'content' => $todo->content, 'id' => $todo->id];
    //     }
    //     // return response()->json($response, 201);
    //     return view('todolist.index', ['todos' => $todosArr]);

    // }

    // public function getTodo($session, $id)
    // {
    //     if (!$session->has('todos')) {
    //         $this->firstLogon();
    //     }
    //     return $session->get('todos')[$id];
    // }

    // public function addTodo($session, $title, $content)
    // {
    //     if (!$session->has('todos')) {
    //         $this->firstLogon();
    //     }
    //     $todos = $session->get('todos');
    //     array_push($todos, ['title' => $title, 'content' => $content]);
    //     $session->put('todos', $todos);
    // }

    // public function editTodo($session, $id, $title, $content)
    // {
    //      $todos = $session->get('todos');
    //     $todos[$id] = ['title' => $title, 'content' => $content];
    //     $session->put('todos', $todos);
    // }

    // public function deleteTodo($session, $id)
    // {
    //      $todos = $session->get('todos');
    //     unset($todos[$id]);
    //     $session->put('todos', $todos);
    // }

    // private function firstLogon($session)
    // {
    //     $todos = [
    //         [
    //             'title' => 'Enter your first Todo',
    //             'content' => 'Click on the plus button below. Or the "New Todo" button in the Navigation to get started!',
    //         ]
    //     ];
    //     $session->put('todos', $todos);
    // }

    public function users(){
        return $this->belongsToMany('App\User');
    }
}
