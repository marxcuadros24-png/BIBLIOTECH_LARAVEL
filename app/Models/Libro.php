<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    protected $table = 'libros';

    protected $fillable = [
        'titulo',
        'autor',
        'isbn',
        'id_categoria',
        'editorial',
        'anio',
        'portada_url',
        'sinopsis',
        'stock_total',
        'stock_disponible',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'id_libro');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_libro');
    }

    public function getTieneStockAttribute(): bool
    {
        return $this->stock_disponible > 0;
    }
}
