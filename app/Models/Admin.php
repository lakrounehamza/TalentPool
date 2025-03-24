<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Admin extends User
{
    protected  $fillable =[
        'id',
    ];
}
