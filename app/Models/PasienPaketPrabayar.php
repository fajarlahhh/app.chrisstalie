<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasienPaketPrabayar extends Model
{
    //

    protected $table = 'pasien_paket_prabayar';
    protected $primaryKey = 'id';
    public $incrementing = false;

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function paketPerawatan()
    {
        return $this->belongsTo(PaketPerawatan::class);
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function registrasiPaketPerawatan()
    {
        return $this->hasMany(RegistrasiPaketPerawatan::class, 'pasien_paket_prabayar_id');
    }

    public function scopeAktif($query)
    {
        $query->where('tanggal_berakhir', '>', date('Y-m-d 00:00:00'));
    }

    public function scopeTidakAktif($query)
    {
        $query->where('tanggal_berakhir', '<', date('Y-m-d 00:00:00'));
    }

    public function getQtyTerpakaiAttribute()
    {
        return $this->registrasiPaketPerawatan->where('terbayar', 1)->count();
    }
}
