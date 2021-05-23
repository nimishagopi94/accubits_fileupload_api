<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'modules';

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = ['Module_code','Module_name','Module_term']; 

    
}
