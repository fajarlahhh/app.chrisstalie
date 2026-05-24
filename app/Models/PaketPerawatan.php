<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaketPerawatan extends Model
{
    //
    use SoftDeletes;
    protected $table = 'paket_perawatan';

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class)->with('kepegawaianPegawai')->withTrashed();
    }

    public function kodeAkunPendapatan(): BelongsTo
    {
        return $this->belongsTo(KodeAkun::class);
    }

    public function kodeAkunKewajiban(): BelongsTo
    {
        return $this->belongsTo(KodeAkun::class);
    }

    public function paketPerawatanDetail(): HasMany
    {
        return $this->hasMany(PaketPerawatanDetail::class);
    }
}
