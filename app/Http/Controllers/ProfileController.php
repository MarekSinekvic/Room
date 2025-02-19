<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\Upvote;
use App\Models\User;
use App\Models\Writing;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
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

    public function dashboard(Request $request):Response {
        $user = User::where('id','=',Auth::id())->first();
        // Log::info($user);

        $writings = $user->getWritings()->paginate(16,'*','writings_page');
        $comments = $user->getComments()->paginate(16,'*','comments_page');
        $bookmarks = $user->getBookmarks()->paginate(16,'*','bookmarks_page');
        $upvotedWritings = $user->getUpvotedWritings()->paginate(16,'*','upvotes_page');
        $upvotedComments = $user->getUpvotedComments()->paginate(16,'*','upvotes_page');
        
        return Inertia::render('Profile/Dashboard',[
            'writings'=>$writings->items(),
            'comments'=>$comments->items(),
            'bookmarks'=>$bookmarks->items(),
            'upvotes'=>[$upvotedWritings->items(),$upvotedComments->items()],
            'pages'=>[[$writings->currentPage(),$writings->lastPage()],[$comments->currentPage(),$comments->lastPage()],[$bookmarks->currentPage(),$bookmarks->lastPage()],[$upvotedWritings->currentPage(),$upvotedWritings->lastPage()]]
        ]);
    }
}
