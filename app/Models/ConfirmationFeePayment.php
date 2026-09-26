<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfirmationFeePayment extends Model
{
    protected $fillable = [
        'application_id',
        'user_id',
        'reference',
        'transaction_id',
        'amount',
        'currency',
        'status',
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}