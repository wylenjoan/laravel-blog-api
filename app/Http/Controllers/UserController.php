<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
    }

    /**
     * Display the specified resource by username.
     *
     * @param  string  $username
     * @return \Illuminate\Http\Response
     */
    public function showByUsername(string $username)
    {
        return User::where('username', $username)->firstOrFail();
    }

    /**
     * Display the specified resource by username with stories relations.
     *
     * @param  string  $username
     * @return \Illuminate\Http\Response
     */
    public function showByUsernameWithStories(string $username)
    {
        return User::where('username', $username)->with('stories')->firstOrFail();
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
        $user = User::find($id);

        $attributes = $request->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'username' => ['required', 'min:3', 'max:255', Rule::unique('users', 'username')->ignore($id)],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
        ]);

        $user->update($attributes);

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        
        return Response(['message'=> 'User deleted'], Response::HTTP_OK);
    }
}