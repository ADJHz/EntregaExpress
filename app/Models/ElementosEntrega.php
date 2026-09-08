<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElementosEntrega extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada al modelo.
     * Es importante definirlo explícitamente al usar nombres en español.
     *
     * @var string
     */
    protected $table = 'elementos_entregas';

    /**
     * Los atributos que son asignables masivamente (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'csp',
        'ubicacion',
        'coordinacion',
        'genero',
        'tipo_uniforme',
        'color_franja',
        'camisola',
        'pantalon',
        'chamarra',
        'bota',
        'cinturon',
        'recibio',
    ];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'recibio' => 'boolean',
    ];
}
