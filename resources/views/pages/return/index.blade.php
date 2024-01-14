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
                <div class="alert alert-danger" id="error-message">
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


            <!--begin::form no penjualan-->
            <div class="form-group row mb-3 d-flex">
                <label for="no_struk" class="col-lg-2 control-label">No Struk</label>
                <div class="col-lg-4 d-flex">
                    <input type="text" name="no_struk" class="form-control"
                        style="border-top-right-radius: 0px;border-bottom-right-radius: 0px" id="noStruk">
                    <button type="button" class="btn btn-sm btn-primary"
                        style="border-top-left-radius: 0px;border-bottom-left-radius: 0px" id="btnStruk"><i
                            class="fa fa-arrow-right"></i></button>
                </div>
            </div>
            <!--end::form no penjualan-->

            <!--begin::card-->
            <div class="row">
                <div class="col-md-8"><!--begin::table-->
                    <div class="table-responsive">
                        <table id="returnProducts-table" class="table">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Sub Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <!-- DataTable Body will be loaded dynamically by JavaScript -->
                        </table>
                    </div>
                    <!--end::table-->
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body border" style="padding: 1rem 1.25rem !important">
                            <form action="" method="GET">
                                @csrf
                                <div class="form-group row text-center mb-3">
                                    <label for="totalReturn" class="col-3 control-label">Total</label>
                                    <div class="col-8">
                                        <input type="number" min="0" id="totalReturn"
                                            class="form-control bg-secondary" readonly name="totalReturn">
                                    </div>
                                </div>
                                <div class="form-group row text-center mb-3">
                                    <label for="bayarReturn" class="col-3 control-label">Bayar</label>
                                    <div class="col-8">
                                        <input type="number" min="0" id="bayarReturn" name="bayarReturn"
                                            class="form-control" name="bayarReturn" value="0">
                                    </div>
                                </div>
                                <div class="form-group row text-center mb-3">
                                    <label for="kembaliReturn" class="col-3 control-label">Kembali</label>
                                    <div class="col-8">
                                        <input type="number" min="0" id="kembaliReturn"
                                            class="form-control bg-secondary" readonly name="kembaliReturn">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button data-bs-toggle="modal" data-bs-target="#batalPesananModal" type="button"
                                        class="btn btn-sm me-2 btn-danger" data-bs-toggle="tooltip"
                                        data-bs-placement="left" data-bs-trigger="hover" title="Batal Return">Batal
                                        Return</button>

                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="tooltip"
                                        data-bs-placement="left" data-bs-trigger="hover">Return
                                        Pesanan</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::card-->

        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

    <!-- Modal Products-->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
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

    <!-- Modal Batal Pesanan-->
    {{-- <div class="modal fade" id="batalPesananModal" tabindex="-1" aria-labelledby="batalPesananModalLabel"
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
    </div> --}}

    <!-- Modal returnProduct-->
    <div class="modal fade" id="returnProductModal" tabindex="-1" aria-labelledby="returnProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="returnProductModalLabel">Return Pesanan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('returnProduct') }}" method="post">
                  @csrf
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Return</button>
                </div>
              </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                /* begin::cashier */
                $('#kembali').val({!! !empty($cashier) ? 0 - $cashier->total_bayar : 0 !!});

                $('#cashier-table').on('change', '.satuan', function() {
                    var itemId = $(this).data('item-id');
                    var selectedValue = $(this).val();

                    let row = $(this).closest('tr');
                    let newValue = row.find('.quantity').val();
                    let satuan = row.find('.satuan').val();

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

                $(document).on('input', '.quantity', debounce(function() {
                    let $row = $(this).closest('tr');
                    let itemId = $(this).data('item-id');
                    let newValue = parseInt($(this).val());
                    let satuan = $row.find('.satuan').val();
                    subTotal = $row.find('.subTotal')

                    if (newValue > 0) {
                        addOrUpdate(itemId, newValue, satuan)
                            .then(response => {
                                subTotal.text(`Rp.${response.detail_cashier.sub_total.toFixed(2)}`);
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
                }, 500));

                $('#bayar').change(function(e) {
                    var changedValue = $(this).val();
                    var total = $('#total').val();
                    $('#kembali').val(changedValue - total);
                });
                /* end::cashier */

                /* begin::return */

                if ($('#noStruk').val() != '') {
                    returnDatatable();
                } else {
                    $('#returnProducts-table').DataTable({})
                }


                $('#btnStruk').click(function(e) {
                    e.preventDefault();
                    returnDatatable();
                });

                $(document).on('click', '.return-modal-trigger', function() {
                    var laporanProductId = $(this).data('laporan-product-id');
                    var productId = $(this).data('product-id');
                    var productSatuan = $(this).data('satuan');
                    var satuans = {!! $satuans !!};

                    var products = @json($products);

                    var product = products.find(item => item.id === productId);

                    if (product) {
                        var modalBody = $('#returnProductModal .modal-body');
                        modalBody.html(
                            `<input type="hidden" name="laporan_product_id" value="${laporanProductId}">` +
                            `<div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon2">Produk</span>
                                    <input type="text" class="form-control"
                                        value="${product.nama_produk}" disabled>
                              </div>
                          ` +
                            `
                          <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon2">Jumlah</span>
                                    <input type="number" class="form-control" name="jumlah">
                          </div>
                          ` +
                            `
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon2">Satuan</span>
                                <select class="form-select" aria-label="Satuan" name="satuan">
                                ${satuans.map(satuan => `
                                <option value="${satuan.nama}" ${productSatuan == satuan.nama ? 'selected' : ''}>
                                ${satuan.nama}
                                </option>
                              `).join('')}
                                </select>
                        </div>
                          ` +
                            `
                          <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon2">Deskripsi</span>
                                    <select class="form-select" aria-label="Deskripsi" name="deskripsi">
                                        <option value="Barang Rusak">Barang Rusak</option>
                                        <option value="Expired">Expired</option>
                                    </select>
                                </div>
                          `
                        );


                        $('#returnProductModal').modal('show');
                    } else {
                        console.log('Product not found.');
                    }
                });

                function returnDatatable() {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('return.showReturn') }}",
                        data: {
                            _token: '{{ csrf_token() }}',
                            noStruk: $('#noStruk').val()
                        },
                        dataType: "json",
                        success: function(response) {

                            $('#error-message').remove();

                            $('#totalReturn').val(response.laporan.total);
                            $('#bayarReturn').val(response.laporan.bayar);
                            $('#kembaliReturn').val(response.laporan.kembali);

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
                                    {
                                        data: 'action',
                                        name: 'action',
                                        orderable: false,
                                        searchable: false
                                    },
                                ],
                                order: [
                                    [0, 'asc']
                                ],
                            });
                        },
                        error: function(response) {
                            if (response.responseJSON && response.responseJSON.redirect) {
                                // Redirect to the specified URL
                                window.location.href = response.responseJSON.redirect;
                            } else {
                                // Handle other errors or redirect back
                                window.location.href = "{{ url()->previous() }}";
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
        </script>
    @endpush
</x-base-layout>
