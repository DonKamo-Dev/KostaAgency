<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'servicio',
        'tipo_cliente',
        'como_nos_conocio',
        'presupuesto',
        'mensaje',
        'estado',
        'notas',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * URL directa para abrir chat de WhatsApp con el lead
     */
    public function getWhatsappUrlAttribute(): ?string
    {
        if (blank($this->telefono)) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $this->telefono);
        if (strlen($digits) === 10 && str_starts_with($digits, '3')) {
            $digits = '57'.$digits;
        }

        $greeting = urlencode("¡Hola {$this->nombre}! Te contactamos de KOSTA Studio & Films con respecto a tu consulta de {$this->servicio}.");

        return "https://wa.me/{$digits}?text={$greeting}";
    }
}
