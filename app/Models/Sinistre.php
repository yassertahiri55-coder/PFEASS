<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sinistre extends Model
 {

    use HasFactory;

    protected $fillable = [
        'titre',
        'type',
        'description',
        'date_declaration',
        'statut',
        'user_id',
        'client_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dossiers()
    {
        return $this->hasMany(Dossier::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
      public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
