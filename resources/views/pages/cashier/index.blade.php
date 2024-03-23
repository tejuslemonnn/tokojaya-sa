<x-base-layout>

    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                    @php
                        Session::forget('success');
                    @endphp
                </div>
            @endif

            @if (Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                    @php
                        Session::forget('error');
                    @endphp
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Ada beberapa masalah dengan masukan Anda.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!--begin::Form Kode Barang-->
            <div class="form-group row mb-3 d-flex align-items-center">
                <label for="kode" class="col-lg-2 control-label mb-1">Kode Barang</label>
                <div class="col-lg-4 d-flex">
                    <form action="{{ route('cashier.addCart') }}" method="post" class="d-flex">
                        @csrf
                        <input type="text" name="kode" class="form-control"
                            style="border-top-right-radius: 0px;border-bottom-right-radius: 0px">
                        <button type="submit" class="btn btn-sm btn-primary"
                            style="border-top-left-radius: 0px;border-bottom-left-radius: 0px"><i
                                class="fa fa-arrow-right"></i></button>
                    </form>
                    <button class="btn btn-sm btn-primary mx-2" data-bs-toggle="modal"
                        data-bs-target="#productModal">List
                        Products</button>
                    <button class="btn btn-sm btn-dark" id="barcode" data-bs-toggle="modal"
                        data-bs-target="#qrCodeModal">
                        <img src="{{ asset('demo1/media/icons/duotune/ecommerce/ecm010.svg') }}" alt="Barcode"
                            style="fill: white;">
                    </button>
                </div>
            </div>
            <!--end::form Kode Barang-->

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body border">
                            @include('partials.widgets.master._table')
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body border" style="padding: 1rem 1.25rem !important">
                            <form action="{{ route('cashier.cetakStruk') }}" method="POST" id="cetakStrukForm">
                                @csrf
                                <input type="hidden" name="no_return" id="noReturnHidden">
                                <div class="form-group row text-center mb-3">
                                    <label for="total_return" class="col-3 control-label">Total Return</label>
                                    <div class="col-8">
                                        <input type="number" min="0" id="total_return"
                                            class="form-control bg-secondary" readonly value="0"
                                            name="total_return">
                                    </div>
                                </div>
                                <div class="form-group row text-center mb-3">
                                    <label for="total" class="col-3 control-label">Total</label>
                                    <div class="col-8">
                                        <input type="number" min="0" id="total"
                                            class="form-control bg-secondary" readonly
                                            value="{{ !empty($cashier) ? $cashier->total_bayar : 0 }}.00"
                                            name="total">
                                    </div>
                                </div>
                                <div class="form-group row text-center mb-3">
                                    <label for="bayar" class="col-3 control-label">Bayar</label>
                                    <div class="col-8">
                                        <input type="number" min="0" id="bayar" name="bayar"
                                            class="form-control" name="bayar" value="0">
                                    </div>
                                </div>
                                <div class="form-group row text-center mb-3">
                                    <label for="kembali" class="col-3 control-label">Kembali</label>
                                    <div class="col-8">
                                        <input type="number" min="0" id="kembali"
                                            class="form-control bg-secondary" value="{{ old('kembali') ?? 0 }}" readonly
                                            name="kembali">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button data-bs-toggle="modal" data-bs-target="#batalPesananModal" type="button"
                                        class="btn btn-sm me-2  {{ empty($cashier) ? 'btn-secondary' : 'btn-danger' }}"
                                        data-bs-toggle="tooltip" data-bs-placement="left" data-bs-trigger="hover"
                                        title="{{ empty($cashier) ? 'Produk belum ada!' : 'Batalkan Pesanan' }}">Batal
                                        Pesanan</button>

                                    <button type="button"
                                        class="btn btn-sm {{ empty($cashier) ? 'btn-secondary' : 'btn-success' }}"
                                        data-bs-toggle="tooltip" data-bs-placement="left" data-bs-trigger="hover"
                                        title="{{ empty($cashier) ? 'Produk belum ada!' : 'Cetak Struk' }}"
                                        id="{{ empty($cashier) ? '' : 'cetakStrukButton' }}">Cetak
                                        Struk</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

    <!--begin::Card-->
    <div class="card mt-2">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            <!--begin::form no penjualan-->
            <div class="form-group row mb-3 d-flex align-items-center">
                <label for="no_return" class="col-lg-2 control-label">Kode Return</label>
                <div class="col-lg-4 d-flex">
                    <input type="text" name="no_return" class="form-control"
                        style="border-top-right-radius: 0px;border-bottom-right-radius: 0px" id="noReturn"
                        value="{{ old('no_return') }}">
                    <button type="button" class="btn btn-sm btn-primary"
                        style="border-top-left-radius: 0px;border-bottom-left-radius: 0px" id="btnReturn"><i
                            class="fa fa-arrow-right"></i></button>
                </div>
            </div>
            <!--end::form no penjualan-->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body border">
                            <div class="table-responsive">
                                @csrf
                                <table id="returnProducts-table" class="table">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            <th>Satuan</th>
                                            <th>Sub Total</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

    <!-- Modal Products-->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="productModalLabel">List Product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="productDataTable" class="productDataTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Satuan</th>
                                    <th>Harga</th>
                                    <th>Diskon</th>
                                    <th>Stok</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key => $item)
                                    <tr>
                                        <td width="5%">{{ $key + 1 }}</td>
                                        <td><span class="label label-success">{{ $item->kode }}</span></td>
                                        <td>{{ $item->nama_produk }}</td>
                                        <td>{{ $item->category->nama }}</td>
                                        <td>{{ $item->satuan->nama }}</td>
                                        <td>{{ $item->harga }}</td>
                                        <td>{{ $item->diskon }} %</td>
                                        <td>{{ $item->stok }}</td>
                                        <td>
                                            <form action="{{ route('cashier.addCart') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="kode" value="{{ $item->kode }}">
                                                <button type="submit" class="btn btn-sm btn-primary">Pilih</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal QR Code-->
    <div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="reader" width="600px"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Batal Pesanan-->
    <div class="modal fade" id="batalPesananModal" tabindex="-1" aria-labelledby="batalPesananModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="batalPesananModalLabel">Batal Pesanan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('cashier.clearCart') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p class="fw-bold">Kepala Kasir</p>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="Password Kepala Kasir"
                                aria-label="Password Kepala Kasir" aria-describedby="basic-addon1" name="password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Batal Pesanan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            @if (session()->has('strukUrl'))
                window.open("{{ session('strukUrl') }}", "_blank");
            @endif

            function onScanSuccess(decodedText, decodedResult) {
                console.log(`Code matched = ${decodedText}`, decodedResult);
            }

            let config = {
                fps: 10,
                qrbox: {
                    width: 100,
                    height: 100
                },
                rememberLastUsedCamera: true,
                supportedScanTypes: [
                    Html5QrcodeScanType.SCAN_TYPE_CAMERA,
                    Html5QrcodeScanType.SCAN_TYPE_FILE
                ]
            };

            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", config, false);
            html5QrcodeScanner.render(onScanSuccess);

            $(document).ready(function() {
                /* begin::cashier */
                $('#kembali').val({!! !empty($cashier) ? 0 - $cashier->total_bayar : 0 !!});

                $('#cashier-table').on('change', '.satuan', function() {
                    var itemId = $(this).data('item-id');
                    var selectedValue = $(this).val();

                    let row = $(this).closest('tr');
                    let newValue = row.find('.quantity').val();
                    let satuan = row.find('.satuan').val();
                    let subTotal = row.find('.subTotal');

                    $.ajax({
                        type: "GET",
                        url: "{{ route('cashier.changeSatuan') }}",
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: itemId,
                            satuan: selectedValue
                        },
                        dataType: "json",
                        success: function(response) {
                            $(this).val(selectedValue);
                            addOrUpdate(itemId, newValue, satuan)
                                .then(response => {
                                    subTotal.text(
                                        `Rp.${response.detail_cashier.sub_total.toFixed(2)}`
                                    );
                                })
                                .catch(error => {
                                    console.error(error);
                                });
                        },
                        error: function(error) {
                            console.log('Error changing category: ' + error.responseText);
                        }
                    });
                });

                $('#cetakStrukButton').click(async function(e) {
                    var isConfirmed = confirm("Apakah anda yakin ingin mencetak pesanan?");

                    if (isConfirmed) {
                        $('#cetakStrukForm').submit();
                    }
                });

                $('#batalPesananBtn').on('click', function(e) {
                    var isConfirmed = confirm("Apakah anda yakin ingin membatakan pesanan?");

                    if (isConfirmed) {
                        window.location.href = "{{ route('cashier.clearCart') }}";

                    }
                });

                $(document).on('keyup', '.quantity', function() {
                    let $row = $(this).closest('tr');
                    let itemId = $(this).data('item-id');
                    let newValue = parseInt($(this).val());
                    let satuan = $row.find('.satuan').val();
                    let subTotal = $row.find('.subTotal');

                    if (newValue > 0) {
                        addOrUpdate(itemId, newValue, satuan)
                            .then(response => {
                                let productStokBeforeConvert = response.detail_cashier.product.stok;
                                let productStok = convertUnit(response.detail_cashier.product.satuan.nama,
                                    satuan,
                                    productStokBeforeConvert);

                                if (newValue > productStok) {
                                    alert(
                                        `Jumlah yang dimasukkan melebihi stok yang tersedia. \n Stok ${response.detail_cashier.product.nama_produk} = ${productStok}`
                                    );
                                    $(this).val(productStok);
                                    subTotal.text(
                                        `Rp.${(productStok * response.detail_cashier.product.harga).toFixed(2)}`
                                    );
                                } else {
                                    subTotal.text(`Rp.${response.detail_cashier.sub_total.toFixed(2)}`);
                                }
                            })
                            .catch(error => {
                                console.error(error);
                            });
                    }

                    if (newValue < 1) {
                        var isConfirmed = confirm('Apakah anda yakin ingin menghapus?');
                        if (isConfirmed) {
                            deleteCart(itemId);
                        } else {
                            $(this).val(1);
                            addOrUpdate(itemId, 1, satuan);
                        }
                    }
                });


                $('#bayar').keyup(function(e) {
                    var changedValue = $(this).val();
                    var total = $('#total').val();
                    $('#kembali').val(changedValue - total);
                });

                /* end::cashier */

                /* begin::return */
                if ($('#noReturn').val() != '') {
                    returnDatatable();
                } else {
                $("#total_return").val(0);
                $('#returnProducts-table').DataTable({})
                }


                $('#btnReturn').click(function(e) {
                    e.preventDefault();
                    returnDatatable();
                });

                function returnDatatable() {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('return.showReturnDatatable') }}",
                        data: {
                            _token: '{{ csrf_token() }}',
                            noReturn: $('#noReturn').val()
                        },
                        dataType: "json",
                        success: function(response) {
                            // $('#error-message').remove();
                            $("#noReturnHidden").val(response.noReturn);

                            if ($("#total_return").val() == 0) {
                                let total = $("#total").val();
                                let totalBayar = parseInt(total) - parseInt(response.return.total);
                                if(Math.sign(totalBayar) == 1){
                                    $("#total").val(totalBayar);
                                } else {
                                    $("#total").val(0);
                                }
                            }


                            if ($("#total").val() != $("kembali").val() && $("#total_return").val() == 0) {
                                let kembali = $("#kembali").val();
                                let totalKembali = parseInt(kembali) + parseInt(response.return.total);
                                $("#kembali").val(totalKembali);
                            }

                            $('#total_return').val(response.return.total);

                            $('#returnProducts-table').DataTable().destroy();

                            $('#returnProducts-table').DataTable({
                                processing: true,
                                data: response.datatable.original.
                                data,
                                columns: [{
                                        data: 'nama_produk',
                                        name: 'nama_produk'
                                    },
                                    {
                                        data: 'jumlah',
                                        name: 'jumlah'
                                    },
                                    {
                                        data: 'satuan',
                                        name: 'satuan'
                                    },
                                    {
                                        data: 'sub_total',
                                        name: 'sub_total'
                                    },
                                ],
                                order: [
                                    [0, 'asc']
                                ],
                            });
                        },
                        error: function(response) {
                            if (response.responseJSON && response.responseJSON.redirect) {
                                window.location.href = response.responseJSON.redirect;
                            } else {
                                $('#noReturn').val('');
                                window.location.href = "{{ route('cashier') }}";
                            }
                        }
                    });
                }
                /* end::return */

            });

            function debounce(func, delay) {
                let timeoutId;
                return function() {
                    const context = this;
                    const args = arguments;

                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(function() {
                        func.apply(context, args);
                    }, delay);
                };
            }

            function addOrUpdate(itemId, newValue, satuan) {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "PUT",
                        url: "{{ route('cashier.updateCart') }}",
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: itemId,
                            jumlah: newValue,
                            satuan: satuan
                        },
                        dataType: "json",
                        success: function(response) {
                            var subTotalCell = $('.subTotal[data-item-id="' + itemId + '"]');
                            var detailCashier = response.detail_cashier;
                            subTotalCell.text('Rp.' + detailCashier.sub_total.toFixed(2));
                            var totalCell = $('#total');
                            totalCell.val(response.total.toFixed(2));
                            var bayar = $('#bayar').val();
                            $('#kembali').val(bayar - response.total);
                            resolve(response);
                        },
                        error: function(error) {
                            console.log('Error updating cart: ' + error.responseText);
                            reject(error);
                        }
                    });
                });
            }

            function deleteCart(itemId) {
                var deleteUrl = `{{ route('cashier.deleteCart', ['id' => '__itemId__']) }}`;
                deleteUrl = deleteUrl.replace('__itemId__', itemId);
                $.ajax({
                    type: "post",
                    url: deleteUrl,
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    dataType: "json",
                    success: function(response) {
                        location.reload();
                    },
                    error: function(error) {
                        console.log('Error updating cart: ' + error.responseText);
                    }
                });
            }

            function convertUnit(fromUnit, toUnit, quantity) {
                fromUnit = fromUnit.toLowerCase();
                toUnit = toUnit.toLowerCase();
                if (fromUnit === 'kg' && toUnit === 'g') {
                    return quantity * 1000; // Corrected to multiply by 1000
                } else if (fromUnit === 'g' && toUnit === 'kg') {
                    return quantity / 1000;
                } else if (fromUnit === 'l' && toUnit === 'ml') {
                    return quantity * 1000;
                } else if (fromUnit === 'ml' && toUnit === 'l') {
                    return quantity / 1000;
                }

                // Default: no conversion
                return quantity;
            }
        </script>
    @endpush
</x-base-layout>
