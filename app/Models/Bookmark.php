<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bookmark extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id','writing_id'
    ];
    public $timestamps = true;
    protected $table = 'users_bookmarks';
    public function user():BelongsTo {return $this->belongsTo(User::class);}
    public function writing():BelongsTo {return $this->belongsTo(Writing::class)
        ->with(['user','previewImage'])
        ->withSum('upvotes','value')
        ->withCount(['views','bookmarks','comments']);}
}
