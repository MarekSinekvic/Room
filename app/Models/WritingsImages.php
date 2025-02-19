<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WritingsImages extends Model
{
    use HasFactory;
    protected $dates = ['created_at'];
    public $timestamps = true;
    protected $fillable = ['url','writing_id'];
    public function writing():BelongsTo {
        return $this->belongsTo(Writing::class);
    }

}
