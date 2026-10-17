<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuraciones';

    protected $fillable = [
        'clave',
        'valor',
        'descripcion',
    ];

    public static function get(string $clave, $default = null)
    {
        $item = static::where('clave', $clave)->first();
        return $item ? $item->valor : $default;
    }

    public static function set(string $clave, $valor, ?string $descripcion = null): void
    {
        static::updateOrCreate(
            ['clave' => $clave],
            ['valor' => (string) $valor, 'descripcion' => $descripcion]
        );
    }

    public static function todas(): array
    {
        return static::pluck('valor', 'clave')->toArray();
    }
}