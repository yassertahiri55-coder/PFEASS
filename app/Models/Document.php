<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;
    protected $table = 'documents';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nom',
        'chemin',
        'type',
        'sinistre_id',
        'dossier_id',
        'user_id',
    ];
        public function sinistre()
        {
            return $this->belongsTo(Sinistre::class);
        }

        public function user()
        {
            return $this->belongsTo(User::class);
        }
}
