<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\Upvote;
use App\Models\Views;
use App\Models\Writing;
use App\Models\WritingsImages;
use App\Services\CommentService;
use App\Services\WritingService;
use Auth;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Log;
use Route;
use Storage;
use WritingRepository;
use CommentRepository;

class WritingController extends Controller
{
    public function list(Request $request):Response {
        $page = $request->has('page') ? $page = $request->page : 1;
        $max_page = ceil(Writing::count()/32);
        $writings = Writing::listWritings()->paginate(16)->items();
        

        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'writings' => $writings,
            'page' => $page, 'max_page' => $max_page
        ]);
    }
    public function show(Request $request,string $id):Response {
        $writing = Writing::getWriting($id);

        if ($writing) {
            return Inertia::Render('Writing',[
                'writing' => $writing,
                'user'=>Auth::user(),
                'comments'=>$writing->getComments()
            ]);
        }
        return Inertia::render(404);
    }
    public function store(Request $request):RedirectResponse {
        $request->validate([ 'preview'=>'required',
                                    'title'=>'required', 'content'=>'required',]);
                                    
        Writing::createWriting($request->title,$request->content,$request->images, $request->preview);

        return redirect('/');
    }
    public function write(Request $request):Response {
        return Inertia::render("CreateWriting",['user'=>$request->user()]);
    }
    public function vote(Request $request,string $id):\Illuminate\Http\Response {
        $request->validate(['state'=>'required']);
        $nvalue = $request->state == 'upvote' ? 1 : -1;
        Upvote::updateOrInsert(['user_id'=>Auth::id(),'writing_id'=>$id],['value'=>$nvalue]);
            

        
        return response()->make();
    }
    public function bookmark(Request $request,string $id):\Illuminate\Http\Response {
        $attr = ['user_id'=>Auth::id(),'writing_id'=>$id];
        $bookmark = Bookmark::where($attr);
        if (!$bookmark->exists())
            $bookmark->create($attr);
        else
            $bookmark->delete();
        
        return response()->make();
    }
    public function comment(Request $request,string $id):\Illuminate\Http\Response {
        $request->validate(['comment'=>'required']);
        Comment::create(['user_id'=>Auth::id(),'writing_id'=>$id,'comment'=>$request->comment]);
        return response()->make();
    }
}
