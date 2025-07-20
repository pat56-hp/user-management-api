<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $perPage = 25;

    protected $fillable = [
        'user_id','ip','navigator','action','pays','codepays','url','created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user() {

        return $this->belongsTo(User::class);
    }
}
