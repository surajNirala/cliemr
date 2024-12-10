<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'users'; // Ensure this matches your table name


    protected static function booted()
    {
        static::addGlobalScope('id', function (Builder $builder) {
            $builder->whereNotIn('id', [1,2]);
        });
    }

}
