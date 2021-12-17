<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TipoAudiencia;
use App\Models\Sala;

class Audiencia extends Model
{
    use HasFactory;

    protected $fillable = [
        // Expediente
        'folio',
        'juez',
        'juzgado',
        'actor',
        'demandado',
        'secretario',
        'juicio_id',
        // Audiencia
        'centroJusticia_id',
        'sala_id',
        'tipo_id',
        'expediente_id',
        //'estadoAudiencia_id',
        'fechaCelebracion',
        'horaInicio',
        'horaFinalizar'
    ];

    public function expediente()
    {
        return $this->belongsTo(Expediente::class);
    }

    public function tipoAudiencia()
    {
        return $this->hasOne(TipoAudiencia::class, 'id', 'tipo_id');
    }

    public function sala()
    {
        return $this->hasOne(Sala::class, 'id', 'sala_id');
    }

    public function estadoAudiencia()
    {
        return $this->hasOne(EstadoAudiencia::class, 'id', 'estadoAudiencia_id');
    }
}
