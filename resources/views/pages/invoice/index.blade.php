<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        .container {
            width: 300px;
        }

        .header {
            margin: 0;
            text-align: center;
        }

        h2,
        p {
            margin: 0;
        }

        .flex-container-1 {
            display: flex;
            margin-top: 10px;
        }

        .flex-container-1>div {
            text-align: left;
        }

        .flex-container-1 .right {
            text-align: right;
            width: 200px;
        }

        .flex-container-1 .left {
            width: 100px;
        }

        .flex-container {
            width: 300px;
            display: flex;
        }

        .flex-container>div {
            -ms-flex: 1;
            /* IE 10 */
            flex: 1;
        }

        ul {
            display: contents;
        }

        ul li {
            display: block;
        }

        hr {
            border-style: dashed;
        }

        a {
            text-decoration: none;
            text-align: center;
            padding: 10px;
            background: #00e676;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }
    </style>
</head>

<body onload="printOut()">
    <div class="container">
        <div class="header" style="margin-bottom: 30px;">
            <h2>Toko Jaya SA</h2>
        </div>
        <hr>
        <div class="flex-container-1">
            <div class="left">
                <ul>
                    <li>No Order</li>
                    <li>Kasir</li>
                    <li>Tanggal</li>
                </ul>
            </div>
            <div class="right">
                <ul>
                    <li> {{ $laporan->no_laporan }} </li>
                    <li> {{ $laporan->kasir->name }} </li>
                    <li> {{ $laporan->created_at->format('Y-m-d H:i') }} </li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="flex-container" style="margin-bottom: 10px; text-align:right;">
            <div style="text-align: left;">Nama Produk</div>
            <div>Jumlah</div>
            <div>Total</div>
        </div>
        @foreach ($laporan->laporan_products as $key => $item)
            <div class="flex-container" style="text-align: right;">
                <div style="text-align: left;">{{ $item->product->nama_produk }}</div>
                <div>{{ $item->jumlah }} {{ $item->satuan }}</div>
                <div>Rp.{{ number_format($item->sub_total, 0, ',', '.') }}</div>
            </div>
            <hr>
        @endforeach
        @if (count($laporan->laporan_return_products) > 0)
            <div class="flex-container" style="margin-bottom: 10px; text-align:right;">
                <div style="text-align: center;font-weight: bold;">Return Produk</div>
            </div>
            <div class="flex-container" style="margin-bottom: 10px; text-align:right;">
                <div style="text-align: left;">Nama Produk</div>
                <div>Jumlah</div>
                <div>Total</div>
            </div>
            @foreach ($laporan->laporan_return_products as $key => $item)
                <div class="flex-container" style="text-align: right;">
                    <div style="text-align: left;">{{ $item->product->nama_produk }}</div>
                    <div>{{ $item->jumlah }} {{ $item->satuan }}</div>
                    <div>Rp.{{ number_format($item->sub_total, 0, ',', '.') }}</div>
                </div>
                <hr>
            @endforeach
        @endif
        <div class="flex-container" style="text-align: right; margin-top: 10px;">
            <div>
                <ul>
                    <li>Grand Total</li>
                    <li>Pembayaran</li>
                    <li>Kembalian</li>
                </ul>
            </div>
            <div style="text-align: right;">
                <ul>
                    <li>Rp.{{ number_format($laporan->total, 0, ',', '.') }}</li>
                    <li>Rp.{{ number_format($laporan->bayar, 0, ',', '.') }}</li>
                    <li>Rp.{{ number_format($laporan->kembali, 0, ',', '.') }}</li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="header" style="margin-top: 50px;">
            <h3>Terimakasih</h3>
            <p>Silahkan berkunjung kembali</p>
        </div>
    </div>

</body>

</html>
