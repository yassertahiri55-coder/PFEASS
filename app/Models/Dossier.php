<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dossier extends Model
{
    use HasFactory;
    protected $table = 'dossiers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'numero',
        'statut',
        'date_ouverture',
        'date_cloture',
        'sinistre_id',
    ];
    public function sinistre()
    {
        return $this->belongsTo(Sinistre::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }
}
