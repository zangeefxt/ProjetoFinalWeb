<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'telefone', 'cpf', 'email', 'endereco_id'];

    public function endereco()
    {
        return $this->belongsTo(Endereco::class);
    }
}

