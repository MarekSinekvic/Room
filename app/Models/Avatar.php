<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Avatar extends Model
{
    use HasFactory;
    protected $fillable = ['url'];
    public $timestamps = true;
    public function user():HasOne {return $this->hasOne(User::class);}
}
