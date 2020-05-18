<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    
    protected $table='purchase';

    protected $primaryKey='purchaseId';
    public $timestamps = false;
    protected $fillable = [
        'clientId', 'paymentId','purchaseAmount','subtotal','costDelivery','purchaseDate','status'
    ];
    
}
