<?php

namespace App\Livewire\Klinik\Upload;

use Livewire\Component;
use App\Models\Registrasi;
use App\Traits\CustomValidationTrait;
use App\Traits\FileTrait;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;

class Form extends Component
{
    use FileTrait, WithFileUploads, CustomValidationTrait;

    #[Url]
    public $registrasi_id;
    
    public $fileInformedConsent;
    public $data;
    public $fileDiupload = [];

    public function mount(Registrasi $data)
    {
        $this->data = $data;
        if ($this->registrasi_id) {
            $this->data = Registrasi::with(['pasien'])->find($this->registrasi_id);
        }
        if ($data->file && method_exists($data->file, 'map')) {
            $this->fileDiupload = $data->file->where('jenis', 'Upload')->map(function ($q) {
                return [
                    'id' => $q['id'] ?? null,
                    'file' => $q['link'] ?? null,
                    'link' => $q['link'] ?? null,
                    'judul' => $q['judul'] ?? null,
                    'extensi' => $q['extensi'] ?? null,
                    'keterangan' => $q['keterangan'] ?? null
                ];
            })->all();
        }
    }

    public function updatedRegistrasiId($id)
    {
        $this->data = Registrasi::with(['pasien'])->find($id);
    }

    public function submit()
    {
        $this->validateWithCustomMessages([
            'fileDiupload' => 'nullable',
            'fileDiupload.*' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        $this->hapusFile();
        $this->uploadFile($this->data->id, 'Upload');

        session()->flash('success', 'Berhasil menyimpan data');
        $this->redirect('/klinik/upload');
    }

    public function render()
    {
        return view('livewire.klinik.upload.form');
    }
}
