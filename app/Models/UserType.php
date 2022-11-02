<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class UserType extends Eloquent
{
    use HasFactory;

    protected $collection = 'user_types';

    protected $fillable = ['id','role'];
}
