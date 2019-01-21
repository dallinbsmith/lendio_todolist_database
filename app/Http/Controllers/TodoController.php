<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Session\Store;

use App\Http\Requests;

use App\Todo;
use JWTAuth;

class TodoController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt.auth', ['only' => [
            'update', 'store'
        ]]);
    }


    public function index(Store $session, Request $request)
    {   
        // Index recieve all todos in initial database

        $todos = Todo::all();
        foreach ($todos as $todo) {
            if($todo->creator_id === $request->session()->get('user')->id){
                $todosArr[] = ['title' => $todo->title, 'content' => $todo->content, 'id' => $todo->id];
            }
        }
        if(empty($todosArr)){
            $todosArr[] = ['title' => "Mega Ultra Todolist", 'content' => "Please login or enter first todo", 'id' => '0'];
        }
        return view('todolist.index', ['todos' => $todosArr]);
    } 



    public function store(Request $request)
    {
        // Saves New Todo to Database
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
        ]);

        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['msg' => 'User not found'], 404);
        }

        $title = $request->input('title');
        $content = $request->input('content');
        $user_id = $user->id;

        $todo = new Todo([
            'title' => $title,
            'content' => $content,
            'creator_id' => $user_id
        ]);

        if ($todo->save()) {

            $todo->view_todo = [
                'href' => 'api/v1/todo/' . $todo->id,
                'method' => 'GET'
            ];  
            $message = [
                'msg' => 'todo created',
                'todo' => $todo
            ];

            $todos = Todo::all();
            foreach ($todos as $todo) {
                if($todo->creator_id === $request->session()->get('user')->id){
                    $todosArr[] = ['title' => $todo->title, 'content' => $todo->content, 'id' => $todo->id];
                }
            }
            if(empty($todosArr)){
                $todosArr[] = ['title' => "Mega Ultra Todolist", 'content' => "Please login or enter first todo", 'id' => '0'];
            }
            return view('todolist.index', ['todos' => $todosArr]);
        }

        $response = [
            'msg' => 'Error during creation'
        ];

        return response()->json($response, 404);
    }


    public function show($id)
    {
        // Expands individual Todo
        $todo = Todo::with('users')->where('id', $id)->firstOrFail();

        return view('todolist.show', ['todo' => $todo]);
    }



    public function update(Request $request, $id)
    {
        // updates todo
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
        ]);

        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['msg' => 'User not found'], 404);
        }

        $title = $request->input('title');
        $content = $request->input('content');
        $user_id = $user->id;

        $todo = Todo::with('users')->findOrFail($id);

        // if (!$todo->users()->where('users.id', $user_id)->first()) {
        //     return response()->json(['msg' => 'user not registered for todo, update not successful'], 401);
        // };
        $todo->title = $title;
        $todo->content = $content;
        if (!$todo->update()) {
            return response()->json(['msg' => 'Error during updating'], 404);
        }

        $todos = Todo::all();
        foreach ($todos as $todo) {
            if($todo->creator_id === $request->session()->get('user')->id){
                $todosArr[] = ['title' => $todo->title, 'content' => $todo->content, 'id' => $todo->id];
            }
        }
        if(empty($todosArr)){
            $todosArr[] = ['title' => "Mega Ultra Todolist", 'content' => "Please login or enter first todo", 'id' => '0'];
        }
        return view('todolist.index', ['todos' => $todosArr]);
    }

    public function delete($id)
    {
        // Opens up 'Are You Sure?' message before deleting
        $todo = Todo::findOrFail($id);

        return view('todolist.delete', ['todo' => $todo]);
    }


    public function edit($id)
    {
        // Opens up Editing Container before submission
        $todo = Todo::findOrFail($id);

        return view('todolist.edit', ['todo' => $todo]);
    }

    public function destroy(Request $request, $id)
    {
        // Deletes Todo from Database
        $todo = Todo::findOrFail($id);

        $users = $todo->users;
        $todo->users()->detach();
        if (!$todo->delete()) {
            foreach ($users as $user) {
                $todo->users()->attach($user);
            }
            return response()->json(['msg' => 'deletion failed'], 404);
        }

        $todos = Todo::all();
        foreach ($todos as $todo) {
            if($todo->creator_id === $request->session()->get('user')->id){
                $todosArr[] = ['title' => $todo->title, 'content' => $todo->content, 'id' => $todo->id];
            }
        }
        if(empty($todosArr)){
            $todosArr[] = ['title' => "Mega Ultra Todolist", 'content' => "Please login or enter first todo", 'id' => '0'];
        }
        return view('todolist.index', ['todos' => $todosArr]);
    }
    
}