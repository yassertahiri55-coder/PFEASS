<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RendezVous extends Model
{
    use HasFactory;
    protected $table = 'rendez_vous';
    protected $primaryKey = 'id';
    protected $fillable = [
        'date',
        'lieu',
        'description',
        'statut',
        'dossier_id',
    ];
    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }
}
