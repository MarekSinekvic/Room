<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Log;
use Storage;

class Writing extends Model
{
    use HasFactory;
    // use HasUuids;
    protected $dates = ['created_at'];
    protected $fillable = [
        'id',
        'title',
        'content',
        'user_id',
        'preview_image_id'
    ];
    public $timestamps = true;
    public function user():BelongsTo {
        $user = $this->belongsTo(User::class);
        return $user;
    }
    public function previewImage():BelongsTo {
        return $this->belongsTo(WritingsImages::class);
    }
    public function images():HasMany {
        return $this->hasMany(WritingsImages::class);
    }
    public function comments():HasMany {return $this->hasMany(Comment::class);}
    public function upvotes():HasMany {return $this->hasMany(Upvote::class);}
    public function views():HasMany {return $this->hasMany(Views::class);}
    public function bookmarks():HasMany {return $this->hasMany(Bookmark::class);}
    public function getComments() {
        $comments = Comment::getCommentsOfWriting($this->id);
        return $comments->get();
    }
    public function assertImages($images,$callback) {
        foreach ($images as $img) {
            $contentType = explode('/',$img->getMimeType())[0];
            if ($contentType == 'image') {
                $path = Storage::disk('public')->putFile("writings_images",$img);
                $image = WritingsImages::create(["url"=>$path,"writing_id"=>$this->id]);
                $callback($image);
            }
        }
    }
    public function setPreview($preview) {
        $this->assertImages($preview, function ($image):void {
            Writing::where('id','=',$this->id)->limit(1)->update(["preview_image_id"=>$image->id]);
        });
    }

    public static function getWriting(string $writing_id) {
        $writing = Writing::where('id','=',$writing_id)->limit(1)
            ->with('user')
            ->withSum('upvotes','value')
            ->withCount(['views','bookmarks','comments'])
            ->with(['upvotes'=>function($query) {$query->where('user_id','=',Auth::id())->select('value','writing_id');},
                                'bookmarks'=>function($query) {$query->where('user_id','=',Auth::id())->select('writing_id');}])
            ->first();
        if (Auth::check())
            Views::create(['user_id'=>Auth::id(), 'writing_id'=>$writing->id]);
        
        return $writing;
    }
    public static function listWritings():Builder {
        $writings = Writing::selectRaw('id,title,left(content,192) as content,created_at,user_id,preview_image_id')->limit(32)
            ->with(['user','previewImage'])
            ->withSum('upvotes','value')
            ->withCount(['views','bookmarks','comments']);
        
        if (Auth::check())
            $writings->with(['upvotes'=>function($query) {$query->where('user_id','=',Auth::id())->select('value','writing_id');},
                                        'bookmarks'=>function($query) {$query->where('user_id','=',Auth::id())->select('writing_id');}]);

        return $writings;
    }
    public static function createWriting(string $title, string $content, $images, $preview): Writing {
        $writing = Writing::create(['title'=>$title, 'content'=>$content, 'user_id'=>Auth::id()])->first(); //, 'user_id'=>$request->user()->id
        if ($images !== null && count($images) > 0)
            $writing->assertImages($images);
        if (count($preview) == 1)
            $writing->setPreview($preview);
        
        return $writing;
    }
}
