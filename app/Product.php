<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    
    protected $table='pizza';

    protected $primaryKey='pizzaId';
    public $timestamps = false;
    protected $fillable = [
        'name', 'description','image','price','stock','minimum_stock', 'creationDate','status'
    ];
    
}