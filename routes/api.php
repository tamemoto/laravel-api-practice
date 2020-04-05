<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Task;
use App\User;
use App\UserToken;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/user", function () {
    $user = new App\User();
    $user->name = request()->input("name");
    $user->email = request()->input("email");
    $user->password = Hash::make(request()->input("password"));
    $user->save();
    return [];
});

Route::post("/login", function () {
    $email = request()->input('email');
    $password = request()->input('password');

    $user = App\User::where('email', $email)->first();
    if($user) {
        if(Hash::check($password, $user->password)) {
            $token = new App\UserToken();
            $token->user_id = $user->id;
            $token->token = \Illuminate\Support\Str::random();
            $token->save();
            return [
                "token" => $token->token
            ];
        }
    }
    abort(401);
});

Route::middleware("auth:api")->get("/profile", function () {
    return [
        "user" => Auth::guard("api")->user()
    ];
});

Route::post("/task", function () {
    $task = new App\Task();
    $task->name = request()->get("name");
    $task->save();
    return [
        "status" => "ok"
    ];
});

Route::get("/tasks", function () {
    $tasks =  App\Task::all();
    $res = [];
    foreach ($tasks as $task) {
        $res[] = [
            "name" => $task->name
        ];
    }
    return [
        "tasks" => $res
    ];
} );

Route::delete("/task/{id}", function ($id) {
    $task = App\Task::find($id);
    if($task) {
        $task->delete();
    }
    return [];
});


