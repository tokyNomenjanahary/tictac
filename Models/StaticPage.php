<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaticPage extends Model {

    protected $table = 'static_pages';
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

}
