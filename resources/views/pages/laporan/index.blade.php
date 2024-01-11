<x-base-layout>

    <!--begin::Card-->
    <div class="card">

        <div class="card-header pt-6 d-flex justify-content-between">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-penjualan-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-penjualan" type="button" role="tab" aria-controls="nav-penjualan"
                        aria-selected="true">Penjualan</button>
                    <button class="nav-link" id="nav-return-tab" data-bs-toggle="tab" data-bs-target="#nav-return"
                        type="button" role="tab" aria-coFntrols="nav-return" aria-selected="false">Return</button>
                </div>
            </nav>
        </div>
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

            <div class="tab-content" id="nav-tabContent">

                <div class="tab-pane fade show active" id="nav-penjualan" role="tabpanel"
                    aria-labelledby="nav-penjualan-tab" tabindex="0">
                    <div class="row d-flex justify-content-end">
                        <div class="col-4 col-sm-2">
                            <div class="me-3">
                                <select id="shiftFilter" class="form-select" name="shift">
                                    <option value="" selected>All Shifts</option>
                                    <option value="1">Shift Pagi</option>
                                    <option value="2">Shift Malam</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-end">
                        <div class="col-lg-3 col-8">
                            <div class="me-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Min</span>
                                    <input type="text" class="form-control" placeholder="Min" aria-label="Min"
                                        aria-describedby="basic-addon1" name="min" id="min">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-8">
                            <div class="me-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Max</span>
                                    <input type="text" class="form-control" placeholder="Max" aria-label="Max"
                                        aria-describedby="basic-addon1" name="max" id="max">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="laporans-table" class="table">
                            <thead>
                                <tr>
                                    <th>No Laporan</th>
                                    <th>Kasir</th>
                                    <th>Shift Kerja</th>
                                    <th>created_at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <!-- DataTable Body will be loaded dynamically by JavaScript -->
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-return" role="tabpanel" aria-labelledby="nav-return-tab"
                    tabindex="0">
                    <table id="returnProductTable"
                        class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" style="width:100%">
                        <thead>
                            <tr>
                                <th>No Laporan</th>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Deskripsi</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

    @push('scripts')
        <script>
            $(document).ready(function() {

                let minDate, maxDate;

                minDate = new DateTime('#min', {
                    format: 'YYYY-MM-DD'
                });
                maxDate = new DateTime('#max', {
                    format: 'YYYY-MM-DD'
                });


                var laporansTable = $('#laporans-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('laporan.table') }}',
                        data: function(d) {
                            var shiftValue = $('#shiftFilter').val();
                            d.shift = shiftValue;
                            d.from_date = $('#min').val();
                            d.end_date = $('#max').val();
                        }
                    },
                    columns: [{
                            data: 'no_laporan',
                            name: 'no_laporan'
                        },
                        {
                            data: 'user_id',
                            name: 'user_id'
                        },
                        {
                            data: 'shift_kerja',
                            name: 'shift_kerja',
                            searchable: false
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
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

                document.querySelectorAll('#min, #max').forEach((el) => {
                    el.addEventListener('change', function() {
                        console.log($('#min').val());
                        laporansTable.draw();
                    });
                });


                $('#shiftFilter').on('change', function() {
                    laporansTable.draw();
                });

                var returnProductTable = $('#returnProductTable').DataTable({
                    serverSide: true,
                    processing: true,
                    ajax: '{{ route('returnProductDatatable') }}',
                    columns: [{
                            data: 'no_laporan',
                            name: 'no_laporan',
                        },
                        {
                            data: 'nama_produk',
                            name: 'nama_produk',
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
                            data: 'deskripsi',
                            name: 'deskripsi'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                        },
                        // {
                        //     data: 'action',
                        //     name: 'action',
                        //     orderable: false,
                        //     searchable: false
                        // },
                    ],
                });

            });
        </script>
    @endpush
</x-base-layout>
