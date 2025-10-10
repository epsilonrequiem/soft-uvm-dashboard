<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Perfil;

class Perfil extends Model
{

    protected $table = 'perfiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'perfil'
    ];

    public function users()
    {
        return $this->hasMany(User::class,'id_perfil'); // Un perfil tiene muchos usuarios
    }
}
