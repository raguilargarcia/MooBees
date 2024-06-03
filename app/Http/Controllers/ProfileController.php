<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            $imageName = time().'.'.$request->profile_picture->extension();
            $request->profile_picture->move(public_path('images/profile'), $imageName);
            $user->profile_picture = $imageName;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Perfil actualizado correctamente.');
    }

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

    public function view(User $user)
    {
        return view('profile.view', compact('user'));
    }
}
