<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'clientName',
        'clientEmail',
        'description',
        'paymentTerms',
        'paymentDue',
        'status',
        'total'
    ];

    // protected $casts = ['id' => 'string'];

    public function invoice_details() {
        return $this->hasOne(InvoiceDetail::class, 'invoice_id', 'id');
    }

    public function items() {
        return $this->hasMany(Item::class);
    }
}
