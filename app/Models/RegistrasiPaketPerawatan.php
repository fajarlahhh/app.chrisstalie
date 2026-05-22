<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrasiPaketPerawatan extends Model
{
    //
    protected $table = 'registrasi_paket_perawatan';
    
    public function paketPerawatan()
    {
        return $this->belongsTo(PaketPerawatan::class);
    }
    
    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }
    
    public function registrasi()
    {
        return $this->belongsTo(Registrasi::class);
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function kodeAkun()
    {
        return $this->belongsTo(KodeAkun::class);
    }

    public function tindakan(){
        return $this->hasMany(Tindakan::class);
    }
}
