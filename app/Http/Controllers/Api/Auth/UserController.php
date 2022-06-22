<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user=User::included()
        ->filter()
        ->sort()
        ->paginate();
        return UserResource::make($user);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed' 
        ]);
        /*Crea al usuario*/
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return UserResource::make($user);
    }

    public function show($id){
        $user = User::included()->findOrFail($id);
        return UserResource::make($user);
    }
    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);
        /*Crea al usuario*/
        $user = User::findOrFail($id);;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return response($user, 200);
    }
    public function destroy($id){
        $user = User::findOrFail($id);
        $user->delete();
        return $user;
    }
}