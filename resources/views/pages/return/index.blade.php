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
            <div class="form-group row mb-3 d-flex align-items-center">
                <label for="no_struk" class="col-lg-2 control-label">Kode Struk</label>
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
            <form action="{{ route('returnProduct') }}" method="POST">
                <input type="hidden" name="no_laporan" id="noLaporanHidden">
                <div class="row">
                    <div class="col-md-8"><!--begin::table-->
                        <div class="table-responsive">
                            @csrf
                            <table id="returnProducts-table" class="table">
                                <thead>
                                    <tr>
                                        <th>Nama Produk</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                        <th>Sub Total</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <!--end::table-->
                    </div>
                    <div class="col-md-4 d-flex align-items-end justify-content-end">
                        <div class="d-flex justify-content-end">
                            <button data-bs-toggle="modal" data-bs-target="#batalPesananModal" type="button"
                                class="btn btn-sm me-2 btn-danger" data-bs-toggle="tooltip" data-bs-placement="left"
                                data-bs-trigger="hover" title="Batal Return">Batal
                                Return</button>

                            <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip"
                                data-bs-placement="left" data-bs-trigger="hover">Return
                                Pesanan</button>

                        </div>
                    </div>
                </div>
            </form>
            <!--end::card-->

        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

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
            $(document).ready(function() {
                /* begin::return */
                @if (session()->has('strukUrl'))
                    window.open("{{ session('strukUrl') }}", "_blank");
                @endif

                if ($('#noStruk').val() != '') {
                    returnDatatable();
                } else {
                    $('#returnProducts-table').DataTable({})
                }


                $('#btnStruk').click(function(e) {
                    e.preventDefault();
                    returnDatatable();
                });

                function returnDatatable() {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('return.showLaporanDatatable') }}",
                        data: {
                            _token: '{{ csrf_token() }}',
                            noStruk: $('#noStruk').val()
                        },
                        dataType: "json",
                        success: function(response) {
                            $('#error-message').remove();
                            $("#noLaporanHidden").val(response.laporan.no_laporan);

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
                                        data: 'qty',
                                        name: 'qty',
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
                                window.location.href = response.responseJSON.redirect;
                            } else {
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
        </script>
    @endpush
</x-base-layout>
