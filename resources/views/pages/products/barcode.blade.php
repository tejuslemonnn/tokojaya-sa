<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Barcode</title>

    <style>
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            @foreach ($products as $product)
                <td class="text-center" style="border: 1px solid #333;">
                    <p>{{ $product->nama_produk }} - Rp. {{ $product->harga }}</p>
                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->kode, 'C39') }}" 
                        alt="{{ $product->kode }}"
                        width="180"
                        height="60">
                    <br>
                    {{ $product->kode_produk }}
                </td>
                @if ($no++ % 3 == 0)
                    </tr><tr>
                @endif
            @endforeach
        </tr>
    </table>
</body>
</html>