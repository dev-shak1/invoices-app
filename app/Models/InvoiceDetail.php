<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'sender_street',
        'sender_city',
        'sender_postCode',
        'sender_country',
        'client_street',
        'client_city',
        'client_postCode',
        'client_country',
    ];
}
