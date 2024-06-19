<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Report; // Add this line to import the Report class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $watchlists = $user->watchlists;
        $reportCount = Report::count();

        return view('profile.show', compact(
            'user',
            'watchlists',
            'reportCount'
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        Log::info('Iniciando actualización del perfil para el usuario: ' . $user->id);

        // Validación de los datos del perfil
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Subida y actualización de la foto de perfil
        if ($request->hasFile('profile_photo_path')) {
            Log::info('Nueva foto de perfil subida.');
            $imageName = time() . '.' . $request->profile_photo_path->extension();
            $request->profile_photo_path->move(public_path('images/profile'), $imageName);

            // Eliminar la imagen anterior si existe
            if ($user->profile_photo_path) {
                Log::info('Eliminando la foto de perfil anterior: ' . $user->profile_photo_path);
                File::delete(public_path('images/profile/' . $user->profile_photo_path));
            }

            $user->profile_photo_path = $imageName;
        }

        // Actualización de otros datos del perfil
        $user->name = $request->name;
        $user->username = $request->username;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        Log::info('Perfil actualizado correctamente para el usuario: ' . $user->id);

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
