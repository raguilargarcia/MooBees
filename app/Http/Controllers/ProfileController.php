<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $watchlists = $user->watchlists;
        return view('profile.show', compact(
            'user',
            'watchlists'
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('profile_photo_path')) {
            $imageName = time() . '.' . $request->profile_photo_path->extension();
            $request->profile_photo_path->move(public_path('images/profile'), $imageName);

            // Eliminar la imagen anterior si existe
            if ($user->profile_photo_path) {
                File::delete(public_path('images/profile/' . $user->profile_photo_path));
            }

            $user->profile_photo_path = $imageName;
        }

        $user->name = $request->name;
        $user->username = $request->username;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Perfil actualizado correctamente.');
    }

    // Otros métodos...

    public function follow(User $user)
    {
        $currentUser = Auth::user();
        $currentUser->followings()->attach($user->id);
        return redirect()->back()->with('success', 'Usuario seguido.');
    }

    public function unfollow(User $user)
    {
        $currentUser = Auth::user();
        $currentUser->followings()->detach($user->id);
        return redirect()->back()->with('success', 'Usuario dejado de seguir.');
    }

    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required|string|max:255',
        ]);

        $searchTerm = $request->input('search');
        $user = User::where('username', 'LIKE', '%' . $searchTerm . '%')->first();

        if ($user) {
            return redirect()->route('profile.view', ['user' => $user->id]);
        } else {
            return redirect()->route('profile.show')->with('error', 'Usuario no encontrado.');
        }
    }

    public function showFollowers(User $user)
    {
        $followers = $user->followers;
        return view('profile.followers', compact('user', 'followers'));
    }

    public function showFollowings(User $user)
    {
        $followings = $user->followings;
        return view('profile.followings', compact('user', 'followings'));
    }

    public function view(User $user)
    {
        return view('profile.view', compact('user'));
    }
}
