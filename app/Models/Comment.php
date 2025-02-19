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
use Number;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'id','comment',
        'writing_id','user_id','comment_id'
    ];
    public $timestamps = true;
    protected $table = 'users_comments';
    public function user():BelongsTo {return $this->belongsTo(User::class);}
    public function writing():BelongsTo {return $this->belongsTo(Writing::class);}
    public function comment():BelongsTo {return $this->belongsTo(Comment::class);}
    public function comments():HasMany {return $this->hasMany(Comment::class);}
    public function upvotes():HasMany {return $this->hasMany(Upvote::class);}

    
    public static function getCommentsOfWriting(string $writing_id):Builder {
        $comments = Comment::where(['writing_id'=>$writing_id])->whereNull('comment_id')->with('user')->withSum('upvotes','value');
        $comments->with(['upvotes' => function ($query) {$query->where('user_id','=',Auth::id())->select(['value','comment_id'])->first();}]);

        return $comments;
    }
    public function getResponds():Builder {
        $comments = Comment::where(['comment_id'=>$this->id])->with('user')->withSum('upvotes','value');
        $comments->with(['upvotes' => function ($query) {$query->where('user_id','=',Auth::id())->select(['value','comment_id'])->first();}]);

        return $comments;
    } 
}
