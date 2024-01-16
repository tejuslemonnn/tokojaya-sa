<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan {{ $laporan->no_laporan }}</title>
</head>
<style>
    .d-flex {
        display: flex;
    }

    .custom-row::after,
    .custom-row::before {
        content: '';
        display: table;
        clear: both;
    }

    .custom-row {
        margin-right: -15px;
        margin-left: -15px;
    }

    .custom-col {
        float: left;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }

    .custom-col-6 {
        float: left;
        width: 50%;
        padding-right: 15px;
        padding-left: 15px;
    }

    .custom-col-2 {
        float: left;
        width: 16.66666667%;
        padding-right: 15px;
        padding-left: 15px;
    }


    .custom-form-group {
        margin-bottom: 15px;
    }

    .custom-table {
        border-collapse: collapse;
        width: 100%;
    }

    .custom-table th,
    .custom-table td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }

    .custom-table th {
        background-color: #f2f2f2;
    }

    .custom-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .custom-table tbody tr:hover {
        background-color: #ddd;
    }
</style>

<body>

    <div class="custom-row">
        <p class="custom-col-2" style=" font-weight: bold;">No Laporan </p>
        <p class="custom-col">: {{ $laporan->no_laporan }} </p>
    </div>

    <div class="custom-row">
        <p class="custom-col-2" style=" font-weight: bold;">Kasir </p>
        <p class="custom-col">: {{ $laporan->kasir->name }} </p>
    </div>

    <div class="custom-row">
        <p class="custom-col-2" style=" font-weight: bold;">Tanggal </p>
        <p class="custom-col">: {{ Carbon\Carbon::parse($laporan->created_at)->format('Y-m-d H:i') }} </p>
    </div>

    <div class="custom-row">
        <p class="custom-col-2" style=" font-weight: bold;">Total </p>
        <p class="custom-col">: Rp.{{ convertRupiah($laporan->total) }} </p>
    </div>

    <div class="custom-row">
        <p class="custom-col-2" style=" font-weight: bold;">Bayar </p>
        <p class="custom-col">: Rp.{{ convertRupiah($laporan->bayar) }} </p>
    </div>

    <div class="custom-row">
        <p class="custom-col-2" style=" font-weight: bold;">Kembalian </p>
        <p class="custom-col">: Rp.{{ convertRupiah($laporan->kembali) }} </p>
    </div>

    <table class="custom-table">
        <tr>
            <th>#</th>
            <th>Nama Produk</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Sub Total</th>
        </tr>
        @foreach ($laporan->laporan_products as $index => $product)
            <tr>
                <td style="font-weight: bold">{{ $index + 1 }}</td>
                <td>{{ $product->product->nama_produk }}</td>
                <td>{{ $product->jumlah }}</td>
                <td>{{ $product->satuan }}</td>
                <td>Rp.{{ convertRupiah($product->sub_total) }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>
