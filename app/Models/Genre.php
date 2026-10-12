<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Genre extends Model
{
    // Definir propiedas y tratis

    // Trait HasFactory para usar dake data para pruebas
    use HasFactory;

    // Trait para SoftDelete
    use SoftDeletes;

    // Filtramos lo que en el Body se puede enviar
    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    // Elocuent convierta de manera implicita algunos valores
    protected $casts = ['is_active' => 'boolean'];

    // Registra eventos del modelo para generar o actualizar automáticamente el slug.
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Genre $genre) {
            if (empty($genre->slug)) {
                $genre->slug = Str::slug($genre->name);
            }
        });

        static::updating(function (Genre $genre) {
            if ($genre->isDirty('name')) {
                $genre->slug = Str::slug($genre->name);
            }
        });
    }
}
