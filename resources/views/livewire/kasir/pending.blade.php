<div wire:ignore.self class="modal fade" id="modal-pasien">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Data Pasien Tindakan/Resep Obat</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body table-responsive" id="modal-body-pasien">
                <input id="cari"  type="text" class="form-control mb-3" placeholder="Cari" aria-label="Sizing example input"
                    autocomplete="off" aria-describedby="basic-addon2" wire:model.lazy="cari">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="w-10px">No.</th>
                            <th>No. Registrasi</th>
                            <th>Tgl. Registrasi</th>
                            <th>RM</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th class="w-10px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataPasienTindakanResepObat as $index => $row)
                            <tr>
                                <td>
                                    {{ $loop->index + 1 }}
                                </td>
                                <td>{{ $row['id'] }}</td>
                                <td>{{ $row['tanggal'] }}</td>
                                <td>{{ $row['pasien']['id'] }}</td>
                                <td>{{ $row['pasien']['nama'] }}</td>
                                <td>{{ $row['pasien']['jenis_kelamin'] }}
                                </td>
                                <td>{{ $row['pasien']['alamat'] }}</td>
                                <td nowrap>
                                    <small>
                                        Tindakan : {!! collect($row['tindakan'])->count() > 0 ? '<span class="badge bg-success">Selesai</span>' : '' !!}
                                        <br>
                                        Resep Obat :
                                        {!! collect($row['peracikan_resep_obat'])->count() > 0 ? '<span class="badge bg-success">Selesai</span>' : '' !!}
                                    </small>
                                </td>
                                <td class="with-btn-group text-end" nowrap>
                                    @role('administrator|supervisor|operator')
                                        <a href="javascript:void(0)" wire:click="setRegistrasi({{ $row['id'] }})"
                                            data-bs-dismiss="modal" class="btn btn-primary btn-sm">
                                            Pilih
                                        </a>
                                    @endrole
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
