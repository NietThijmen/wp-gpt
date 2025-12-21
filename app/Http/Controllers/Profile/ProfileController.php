<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return inertia('Account/Profile');
    }


    public function update(Request $request, User $profile)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $profile->id,
        ]);

        $profile->update($data);


        return redirect()->route('account.profile.index')->with('success', 'Profile updated successfully.');
    }

    public function destroy(User $profile)
    {
        $profile->delete();

        return redirect('/')->with('success', 'Profile deleted successfully.'); // Redirect the user to the homepage after deletion

    }
}
