<div>
    @section('title', 'Absensi')

    @section('breadcrumb')
        <li class="breadcrumb-item">Kepegawaian</li>
        <li class="breadcrumb-item active">Absensi</li>
    @endsection

    <!-- BEGIN page-header -->
    <h1 class="page-header">Absensi</h1>
    <!-- END page-header -->

    @php
        $connected = false;
        $output = null;
        $return_var = null;

        // Only check if the function exists for security and hosting compatibility
        if (function_exists('exec')) {
            $pingCommand =
                stripos(PHP_OS, 'WIN') === 0
                    ? 'ping -n 1 ' . config('app.fingerprint_ip')
                    : 'ping -c 1 ' . config('app.fingerprint_ip');
            @exec($pingCommand, $output, $return_var);
            $connected = $return_var === 0;
        }
    @endphp

    @if ($connected)
        <div class="alert alert-success">
            Terhubung ke Mesin Fingerprint
        </div>
    @else
        <div class="alert alert-danger">
            Tidak terhubung ke Mesin Fingerprint
        </div>
    @endif

    <div class="panel panel-inverse" data-sortable-id="table-basic-2">
        <!-- BEGIN panel-heading -->
        <div class="panel-heading overflow-auto d-flex">
            @unlessrole('guest')
                @if ($connected)
                    <a href="javascript:;" wire:click="download" class="btn btn-outline-secondary btn-block"
                        wire:loading.attr="disabled">
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                        Download
                    </a>
                @endif
                <a href="javascript:;" wire:click="posting" class="btn btn-outline-secondary btn-block"
                    wire:loading.attr="disabled">
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                    Posting
                </a>
            @endunlessrole
            <div class="ms-auto d-flex align-items-center">
                <select id="kepegawaian_pegawai_id"  class="form-control w-auto" wire:model="kepegawaian_pegawai_id">
                    <option value="">Semua Pegawai</option>
                    @foreach ($dataPegawai as $row)
                        <option value="{{ $row['id'] }}">{{ $row['nama'] }}</option>
                    @endforeach
                </select>
                &nbsp;
                <input id="tanggal1"  class="form-control w-auto" type="date" autocomplete="off" wire:model="tanggal1" />&nbsp;
                <input id="tanggal2"  class="form-control w-auto" type="date" autocomplete="off" wire:model="tanggal2" />&nbsp;
                <input id="cari"  type="text" class="form-control w-auto" placeholder="Cari" autocomplete="off"
                    wire:model="cari">
                &nbsp;
                <button class="btn btn-primary" type="button" wire:click="$commit">Filter</button>
            </div>
        </div>
        <!-- END panel-heading -->
        <!-- BEGIN panel-body -->
        <div class="panel-body">
            <!-- BEGIN table-responsive -->
            <div class="table-responsive">
                <table class="table table-hover mb-0 text-dark">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Jadwal Shift</th>
                            <th>Izin</th>
                            <th>Masuk</th>
                            <th>Pulang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            <tr @if ($row->izin) class="table-warning" @endif>
                                <td class=" w-5px">
                                    {{ ($data->currentpage() - 1) * $data->perpage() + $loop->index + 1 }}
                                </td>
                                <td>{{ $row->tanggal }}</td>
                                <td>{{ $row->kepegawaianPegawai->nama }}</td>
                                <td>
                                    @if ($row->jam_masuk && $row->jam_pulang)
                                        {{ $row->jam_masuk . ' s/d ' . $row->jam_pulang }}
                                    @else
                                        Libur
                                    @endif
                                </td>
                                <td>{{ $row->izin ? $row->izin . ' (' . $row->keterangan . ')' : null }}</td>
                                @if ($row->jam_masuk && $row->jam_pulang)
                                    @php
                                        $kepegawaianKehadiran = $row->kepegawaianPegawai->kepegawaianKehadiran->where(
                                            'tanggal',
                                            $row->tanggal,
                                        );
                                        $masuk = $kepegawaianKehadiran->first()?->waktu;
                                        $pulang = $kepegawaianKehadiran->last()?->waktu;
                                    @endphp
                                    <td>{{ $masuk }}</td>
                                    <td>{{ $pulang }}</td>
                                @else
                                    <td></td>
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- END table-responsive -->
        </div>
        <!-- END panel-body -->
        <div class="panel-footer" wire:loading.remove>
            {{ $data->links() }}
        </div>
    </div>

    <div wire:loading>
        <x-loading />
    </div>
</div>
