<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Association;
use App\Models\Favorite;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{

    private Favorite $favorisModel;

    /**
     * Display the user's profile form.
     */
    function __construct()
    {
        $this->favorisModel = new Favorite();
    }


    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function makeFavoris(Association $association){
        if(!Auth::check()){
            return back()->with("error","vous devez etre connecter");
        }

        $userId=Auth::id();

        $favoris = new Favorite();
        $favoris->association_id = $association->id;
        $favoris->user_id = $userId;
        $favoris->save();

        return back()->with('success',"ajout reussis");

    }

    public function removeFavoris(Association $association){
        if(!Auth::check()){
            return back()->with("error","vous devez etre connecter");
        }

        $userId=Auth::id();

         $favoris = Favorite::where('user_id', Auth::id())
                ->with('association') 
                ->delete();
        
        return back()->with('success',"suppresion reussis");
    }
}
