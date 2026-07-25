<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Diskon extends Model
{
    //
    protected $table = 'diskon';

    public function tarifTindakan()
    {
        return $this->belongsTo(TarifTindakan::class);
    }

    public function barangSatuan()
    {
        return $this->belongsTo(BarangSatuan::class);
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }
}
