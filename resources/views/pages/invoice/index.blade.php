<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Receipt example</title>
    <style>
        * {
            font-size: 12px;
            font-family: 'Times New Roman';
        }

        .item,
        th,
        table {
            border-bottom: 1px solid black;
            border-collapse: collapse;
            text-align: center;
        }

        .product,
        .jumlah,
        .price {
            padding-right: 5px;
            text-align: center;
        }

        td.product,
        th.product {
            width: 25mm;
            max-width: 25mm;
        }

        td.jumlah,
        th.jumlah {
            width: 15mm;
            max-width: 15mm;
            word-break: break-all;
        }

        td.price,
        th.price {
            width: 25mm;
            max-width: 25mm;
            word-break: break-all;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        .ticket {
            width: 70mm;
            max-width: 70mm;
            box-shadow: 0 0 1in -0.25in rgb(0, 0, 0.5);
            padding: 2mm;
            margin: 0 auto;
            max-width: 75mm;
            background: #ffffff;
            overflow: hidden;
        }

        img {
            max-width: inherit;
            width: inherit;
        }

        .kasir,
        .tanggal {
            display: flex;
        }

        .kasir p {
            margin: 0;
        }
        .tanggal p {
            margin: 0, 0, 10px, 0;
        }

        /* @media print {

            .hidden-print,
            .hidden-print * {
                display: none !important;
            } */
        }
    </style>
</head>

<body>
    <div class="ticket">
        <p class="centered">TOKO JAYA SA
            <br>Jl.TokoJaya no.5 Surabaya
        </p>
        <div class="info">
            <div class="kasir">
                <p style="padding-right: 10px;font-weight: bold">Nama Kasir:</p>
                <p style="font-weight: 600;">{{ $laporan->kasir->username }}</p>
            </div>
            <div class="tanggal">
                <p style="padding-right: 10px;font-weight: bold">Tanggal:</p>
                <p style="font-weight: 600;"> {{ $laporan->created_at->format('d-M-Y H:i') }}
                </p>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th class="jumlah">Jumlah</th>
                    <th class="product">Produk</th>
                    <th class="price">Rp.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan->laporan_products as $item)
                    <tr class="item">
                        <td class="jumlah">{{ $item->jumlah }} {{ $item->satuan }}</td>
                        <td class="product">{{ $item->product->nama_produk }}</td>
                        <td class="price">Rp.{{ number_format($item->sub_total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td class=>Total</td>
                    <td class=>Rp.{{ number_format($laporan->total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td class=>Bayar</td>
                    <td class=>Rp.{{ number_format($laporan->bayar, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td class=>Kembalian</td>
                    <td class=>Rp.{{ number_format($laporan->kembali, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <p class="centered"><strong>Terima kasih telah berkunjung!</strong>
            <br>Silahkan datang kembali!
            <br>{{$laporan->no_laporan}}
        </p>
    </div>
    {{-- <button id="btnPrint" class="hidden-print">Print</button>
    <script>
        const $btnPrint = document.querySelector("#btnPrint");
        $btnPrint.addEventListener("click", () => {
            window.print();
        });
    </script> --}}
</body>

</html>
