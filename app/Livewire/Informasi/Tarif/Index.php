<?php

namespace App\Livewire\Informasi\Tarif;

use App\Models\PaketPerawatan;
use App\Models\TarifTindakan;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    #[Url]
    public $tarifId;

    public $data;

    public function updatedTarifId($id)
    {
        if (str_contains($id, '-tindakan')) {
            $this->data = TarifTindakan::find(str_replace('-tindakan', '', $id));
        } else {
            $this->data = PaketPerawatan::find(str_replace('-paket', '', $id));
        }
    }

    public function mount()
    {
        if ($this->tarifId) {
            if (str_contains($this->tarifId, '-tindakan')) {
                $this->data = TarifTindakan::find(str_replace('-tindakan', '', $this->tarifId));
            } else {
                $this->data = PaketPerawatan::find(str_replace('-paket', '', $this->tarifId));
            }
        } else {
            $this->data = null;
        }
    }

    public function render()
    {
        return view('livewire.informasi.tarif.index');
    }
}
