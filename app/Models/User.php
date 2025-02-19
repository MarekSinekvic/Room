<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Auth;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Log;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    // use HasUuids;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function writings():HasMany {return $this->hasMany(Writing::class);}
    
    public function views():HasMany {return $this->hasMany(Views::class);}
    public function upvotes():HasMany {return $this->hasMany(Upvote::class);}
    public function bookmarks():HasMany {return $this->hasMany(Bookmark::class);}
    public function avatar():BelongsTo {return $this->belongsTo(Avatar::class);}
    public function comments():HasMany {return $this->hasMany(Comment::class);}

    public function getWritings() {
        $writings = Writing::select(['id','title','created_at','preview_image_id'])->where(['user_id'=>$this->id])
            ->with('previewImage')
            ->withSum('upvotes','value')->withCount(['views','bookmarks','comments'])
            ->orderBy('created_at','desc');
        
        if (Auth::check())
            $writings->with(['upvotes'=>function($query) {$query->where('user_id','=',$this->id)->select('value','writing_id');},
                                        'bookmarks'=>function($query) {$query->where('user_id','=',$this->id)->select('writing_id');}]);
        
        return $writings;
    }
    public function getComments() {
        $comments = Comment::where(['user_id'=>$this->id])->with(['user','writing'])->withSum('upvotes','value')->orderBy('created_at','desc');
        $comments->with(['upvotes' => function ($query) {$query->where('user_id','=',$this->id)->select(['value','comment_id']);}]);
        return $comments;
    }
    public function getBookmarks() {
        $bookmarks = Writing::listWritings()->whereHas('bookmarks',function ($query) {$query->where('user_id','=',Auth::id())->select('writing_id');});
        return $bookmarks->orderBy('created_at','desc');
    }
    public function getUpvotedWritings() {
        $upvotes = Writing::listWritings()->whereHas('upvotes',function ($query) {$query->where(['user_id'=>Auth::id()])->whereNotNull('writing_id')->select('writing_id','value');});
        return $upvotes->orderBy('created_at','desc');
    }
    public function getUpvotedComments() {
        $upvotes = $this->getComments()->whereHas('upvotes',function ($query) {$query->where('user_id','=',Auth::id())->whereNotNull('comment_id')->select('comment_id','value');});
        // Log::info($upvotes);
        return $upvotes->orderBy('created_at','desc');
    }
}
