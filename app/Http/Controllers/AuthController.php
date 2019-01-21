<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Session;

use Carbon\Carbon;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use App\User;
use App\Todo;

class AuthController extends Controller
{

    /**
     * Create user
     */
    public function signup(Store $session, Request $request)
    {
        // return response()->json($request);
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        session(['user' => $user]);

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
  
    /**
     * Login user and create token
     */
    public function login(Store $session, Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = request(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        session(['token' => $token]);
        $data = $request->session()->all();

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

    // Logout Not yet implemented
  
    // public function logout(Request $request)
    // {
    //     $request->session()->token()->revoke();
    //     return response()->json([
    //         'message' => 'Successfully logged out'
    //     ]);
    // }

}