<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    // //keep your constants in the specific context (i.e. classes) they belong
    const TYPE_APPLICATION = "Application";
    const TYPE_PURCHASE = "Purchase";

    protected $table =  'inventory';
    protected $fillable = [
        'type',
        'quantity',
        'unit_price'
    ];

}
