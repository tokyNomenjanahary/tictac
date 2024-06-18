<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeEtat extends Model
{
    use HasFactory;

    protected $table = 'type_etats';
    protected $guarded = [];

}
