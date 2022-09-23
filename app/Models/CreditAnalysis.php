<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class CreditAnalysis extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'credit_analysis';
    protected $fillable = [
        'reference_code',
        'score',
        'result',
        'customer_id',
    ];
    protected $guardaded = ['id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
