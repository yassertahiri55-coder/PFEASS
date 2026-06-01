<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $fillable = [
        'type',
        'message',
        'is_read',
        'dossier_id',
        'user_id',
    ];
    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }
}
