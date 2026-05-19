<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaketPerawatanDetail extends Model
{
    //  
    protected $table = 'paket_perawatan_detail';

    public function paketPerawatan(): BelongsTo
    {
        return $this->belongsTo(PaketPerawatan::class);
    }

    public function tarifTindakan(): BelongsTo
    {
        return $this->belongsTo(TarifTindakan::class);
    }
}
