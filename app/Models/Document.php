<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'person_id',
        'title',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'description',
        'type',
        'expiration_date',
        'is_private',
        'shared_at',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'is_private' => 'boolean',
        'shared_at' => 'datetime',
    ];

    /**
     * Obtém a pessoa associada ao documento.
     */
    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Formata o tamanho do arquivo para exibição.
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Verifica se o documento está expirado.
     */
    public function getIsExpiredAttribute()
    {
        if (!$this->expiration_date) {
            return false;
        }
        
        return $this->expiration_date->isPast();
    }

    /**
     * Filtra documentos pelo tipo.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Filtra documentos públicos (não privados).
     */
    public function scopePublic($query)
    {
        return $query->where('is_private', false);
    }

    /**
     * Filtra documentos compartilhados com o cliente.
     */
    public function scopeShared($query)
    {
        return $query->whereNotNull('shared_at');
    }
}
