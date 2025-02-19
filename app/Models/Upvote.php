<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Upvote extends Model
{
    use HasFactory;
    protected $fillable = [
        'id','value',
        'user_id','writing_id','comment_id'
    ];
    protected $table = 'users_upvotes';
    public $timestamps = true;
    public function user():BelongsTo {return $this->belongsTo(User::class);}
    public function writing():BelongsTo {return $this->belongsTo(Writing::class)
        ->with(['user','previewImage'])
        ->withSum('upvotes','value')
        ->withCount(['views','bookmarks','comments']);}
    public function comment():BelongsTo {return $this->belongsTo(Comment::class);}
}
