<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'customers';
    protected $fillable = [
        'name',
        'cpf',
        'negative',
        'salary',
        'card_limit',
        'rent_value',
        'road',
        'number',
        'city',
        'federative_unit',
        'cep',
    ];
    protected $guardaded = ['id'];

    public function credit_analysis()
    {
        return $this->hasMany(CreditAnalysis::class);
    }
}
