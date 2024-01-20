<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Return PDF</title>
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
        <p class="custom-col-2" style=" font-weight: bold;">Total Return </p>
        <p class="custom-col">: {{ count($returns) }} Return</p>
    </div>

    <table class="custom-table">
        <tr>
            <th>#</th>
            <th>No Laporan</th>
            <th>Kasir</th>
            <th>Shift Kerja</th>
            <th>Total Barang</th>
            <th>Created At</th>
        </tr>
        @foreach ($returns as $index => $return)
            <tr>
                <td style="font-weight: bold">{{ $index + 1 }}</td>
                <td>{{ $return->no_return }}</td>
                <td>{{ $return->kasir_name }}</td>
                <td>{{ $return->shift_kerja }}</td>
                <td>{{ $return->return_products_count }} Barang</td>
                <td>{{ $return->created_at }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>
