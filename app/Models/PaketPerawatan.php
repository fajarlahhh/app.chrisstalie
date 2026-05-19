<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaketPerawatan extends Model
{
    //
    protected $table = 'paket_perawatan';

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class)->with('kepegawaianPegawai')->withTrashed();
    }

    public function paketPerawatanDetail(): HasMany
    {
        return $this->hasMany(PaketPerawatanDetail::class);
    }
}
