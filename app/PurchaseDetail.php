<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    
    protected $table='purchase_detail';

    protected $primaryKey='detailId';
    public $timestamps = false;
    protected $fillable = [
        'pizzaId', 'purchaseId','quantity','sizePizzaId','purchasePrice','status'
    ];
    
}
