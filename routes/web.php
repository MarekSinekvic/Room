<?php

use App\Http\Controllers\WritingController;
use App\Http\Controllers\ProfileController;
use App\Models\Comment;
use App\Models\Upvote;
use App\Models\Writing;
use Illuminate\Foundation\Application;
use Illuminate\Http\Client\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [WritingController::class, 'list']);
Route::get('/write', [WritingController::class, 'write']);
Route::post('/write/upload', [WritingController::class, 'store'])->name('upload-writing');
Route::get('/writing/{id}', [WritingController::class, 'show']);
// Route::post('/write/upload-images', [WritingController::class, 'storeImage'])->name("upload-image");
Route::get('/dashboard', [ProfileController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/writing/{writing_id}/comments/responds/{comment_id}', function ($writing_id,$comment_id):JsonResponse {
    $comment = Comment::where(['id'=>$comment_id])->first();
    return response()->json($comment->getResponds()->get('*'));
});
Inertia::share('csrf_token',function () {return csrf_token();});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/writing/{id}/vote', [WritingController::class, 'vote'])->name('writing.vote');
    Route::post('/writing/{id}/bookmark', [WritingController::class, 'bookmark'])->name('writing.bookmark');
    Route::post('/writing/{id}/comment', [WritingController::class, 'comment'])->name('writing.comment');
});
Route::middleware('auth')->group(function () {
    Route::post('/comments/comment/{id}', function (Request $request, string $id):Redirect {
        $request->validate(['comment'=>'required']);
        Comment::create(['user_id'=>Auth::id(),'comment_id'=>$id,'comment'=>$request->comment]);
        return response()->make();
    });
    Route::post('/comments/vote/{id}', function (\Illuminate\Http\Request $request, string $id):Response {
        $request->validate(['state'=>'required']);
        $nvalue = $request->state == 'upvote' ? 1 : -1;
        Upvote::updateOrInsert(['user_id'=>Auth::id(),'comment_id'=>$id],['value'=>$nvalue]);

        return response()->make();
    });
});

require __DIR__.'/auth.php';
