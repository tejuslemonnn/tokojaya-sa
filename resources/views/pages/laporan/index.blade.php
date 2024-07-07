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

                <div class="tab-pane fade active show" id="nav-penjualan" role="tabpanel"
                    aria-labelledby="nav-penjualan-tab">
                    <div class="row d-flex justify-content-end align-items-center">
                        <div class="col-lg-1 col-3 d-flex justify-content-end">
                            <div class="me-3">
                                <button class="btn btn-sm btn-danger" id="pdf">
                                    PDF
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="row d-flex justify-content-end mt-4">

                        <div class="col-lg-3 col-6">
                            <div class="me-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Min</span>
                                    <input type="text" class="form-control" placeholder="Min" aria-label="Min"
                                        aria-describedby="basic-addon1" name="min" id="min">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="me-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Max</span>
                                    <input type="text" class="form-control" placeholder="Max" aria-label="Max"
                                        aria-describedby="basic-addon1" name="max" id="max">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-6">
                            <div class="me-3">
                                <select id="shiftFilter" class="form-select" name="shift">
                                    <option value="" selected>All Shifts</option>
                                    <option value="1">Shift Pagi</option>
                                    <option value="2">Shift Malam</option>
                                </select>
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
                                    <th>Total Pendapatan</th>
                                    <th>created_at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-return" role="tabpanel" aria-labelledby="nav-return-tab"
                    tabindex="0">
                    <div class="row d-flex justify-content-end align-items-center">
                        <div class="col-lg-1 col-3 d-flex justify-content-end">
                            <div class="me-3">
                                <button class="btn btn-sm btn-danger" id="pdf_return">
                                    PDF
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="row d-flex justify-content-end mt-4">

                        <div class="col-lg-3 col-6">
                            <div class="me-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Min</span>
                                    <input type="text" class="form-control" placeholder="Min" aria-label="Min"
                                        aria-describedby="basic-addon1" name="min_return" id="min_return">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="me-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Max</span>
                                    <input type="text" class="form-control" placeholder="Max" aria-label="Max"
                                        aria-describedby="basic-addon1" name="max_return" id="max_return">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-6">
                            <div class="me-3">
                                <select id="shiftFilter_return" class="form-select" name="shift">
                                    <option value="" selected>All Shifts</option>
                                    <option value="1">Shift Pagi</option>
                                    <option value="2">Shift Malam</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="returnPenjualanTable"
                            class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>No Return</th>
                                    <th>No Laporan</th>
                                    <th>Nama Kasir</th>
                                    <th>Shift Kerja</th>
                                    <th>Tanggal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Card body-->
    <!--end::Card-->

    @push('scripts')
        <script>
            $(document).ready(function() {

                let minDate, maxDate;
                let minDateReturn, maxDateReturn;

                minDate = new DateTime('#min', {
                    format: 'YYYY-MM-DD'
                });
                maxDate = new DateTime('#max', {
                    format: 'YYYY-MM-DD'
                });

                minDateReturn = new DateTime('#min_return', {
                    format: 'YYYY-MM-DD'
                });
                maxDateReturn = new DateTime('#max_return', {
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
                            data: 'kasir',
                            name: 'kasir'
                        },
                        {
                            data: 'shift_kerja',
                            name: 'shift_kerja',
                        },
                        {
                            data: 'total',
                            name: 'total',
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

                var returnPenjualanTable = $('#returnPenjualanTable').DataTable({
                    serverSide: true,
                    processing: true,
                    ajax: {
                        url: '{{ route('returnProductDatatable') }}',
                        data: function(d) {
                            var shiftValue = $('#shiftFilter_return').val();
                            d.shift = shiftValue;
                            d.from_date = $('#min_return').val();
                            d.end_date = $('#max_return').val();
                        }
                    },
                    columns: [{
                            data: 'no_return',
                            name: 'no_return',
                        },
                        {
                            data: 'laporan_id',
                            name: 'laporan_id',
                        },
                        {
                            data: 'user_id',
                            name: 'user_id',
                        },
                        {
                            data: 'shift_kerja',
                            name: 'shift_kerja',
                            searchable: false
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                });

                $('#shiftFilter_return').on('change', function() {
                    returnPenjualanTable.draw();
                });

                document.querySelectorAll('#min_return, #max_return').forEach((el) => {
                    el.addEventListener('change', function() {
                        returnPenjualanTable.draw();
                    });
                });

                $('#pdf').on('click', function(e) {
                    e.preventDefault();
                    var selectedShift = $('#shiftFilter').val() == "" ? "semua" : $('#shiftFilter').val();
                    var minDateValue = $('#min').val();
                    var maxDateValue = $('#max').val();

                    var pdfRoute =
                        '{{ route('laporan.pdf', ['shift' => '', 'from_date' => '', 'end_date' => '']) }}' +
                        '/' + encodeURIComponent(selectedShift) +
                        '/' + encodeURIComponent(minDateValue) +
                        '/' + encodeURIComponent(maxDateValue);

                    window.location.href = pdfRoute;
                });

                $('#pdf_return').on('click', function(e) {
                    e.preventDefault();
                    var selectedShift = $('#shiftFilter').val() == "" ? "semua" : $('#shiftFilter').val();
                    var minDateValue = $('#min').val();
                    var maxDateValue = $('#max').val();

                    var pdfRoute =
                        '{{ route('return.pdf', ['shift' => '', 'from_date' => '', 'end_date' => '']) }}' +
                        '/' + encodeURIComponent(selectedShift) +
                        '/' + encodeURIComponent(minDateValue) +
                        '/' + encodeURIComponent(maxDateValue);

                    window.location.href = pdfRoute;
                });
            });
        </script>
    @endpush
</x-base-layout>
