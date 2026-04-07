<table class="table table-borderless">
    <tr>
        <td class="p-0 text-end">
            {!! \App\Helpers\QrCode::size(100)->generate($data->nomor) !!}
        </td>
        <td class="align-middle p-0">
            {{ $data->nomor }}
            <br>
            {{ $data->nama }}
            <br>
            {{ $data->lokasi }}
        </td>
    </tr>
</table>
