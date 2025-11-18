<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $parents = \App\Models\ParentModel::latest()->paginate(5);
        $children = \App\Models\Child::latest()->paginate(5);

        return view('admin.dashboard', compact('parents', 'children'));
    }

    public function showCompleteProfile()
    {
        $user = Auth::user();
        return view('profile.complete', compact('user'));
    }

    public function completeProfile(Request $request)
    {
        $request->validate([
            'first_name'=>'required|string',
            'last_name'=>'required|string',
            'profile_image'=>'nullable|image|max:2048'
        ]);
        $user = Auth::user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles','public');
            $user->profile_image = $path;
        }
        $user->profile_completed = true;
        $user->save();
        return redirect()->route('dashboard')->with('success','Profile completed.');
    }
}
