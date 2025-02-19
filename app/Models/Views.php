<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Views extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id','writing_id'
    ];
    protected $table = 'users_view_history';
    public $timestamps = true;
    public function writing():BelongsTo {
        return $this->belongsTo(Writing::class);
    }
}
