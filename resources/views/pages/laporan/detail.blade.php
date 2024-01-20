<x-base-layout>
    <div class="card">
        <!--begin::cardBody-->
        <div class="card-body p-6">
            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                    @php
                        Session::forget('success');
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

            <div class="row">

                <div class="col-2">
                    <p class="fw-bold">No:</p>
                </div>
                <div class="col-10">{{ $laporan->no_laporan }}</div>

                <div class="col-2">
                    <p class="fw-bold">Kasir:</p>
                </div>
                <div class="col-8">
                    <p>{{ $laporan->kasir->username }}</p>
                </div>

                <div class="col-2 text-end d-flex">
                    <a href="{{ route('invoice', $laporan->no_laporan) }}" class="btn btn-sm btn-primary"
                        target="_blank">
                        Struk
                    </a>

                    <a href="{{ route('laporan.pdfDetail', $laporan->no_laporan) }}" class="btn btn-sm btn-danger mx-2">
                        PDF
                    </a>
                </div>

                <div class="col-2">
                    <p class="fw-bold">Tanggal:</p>
                </div>
                <div class="col-10">
                    <p>{{ $laporan->created_at }}</p>
                </div>

                <div class="col-2">
                    <p class="fw-bold">Total:</p>
                </div>
                <div class="col-10">
                    <p>Rp.{{ $laporan->total }}</p>
                </div>
                <div class="col-2">
                    <p class="fw-bold">Bayar:</p>
                </div>
                <div class="col-10">
                    <p>Rp.{{ $laporan->bayar }}</p>
                </div>
                <div class="col-2">
                    <p class="fw-bold">Kembalian:</p>
                </div>
                <div class="col-10">
                    <p>Rp.{{ $laporan->kembali }}</p>
                </div>
            </div>

            <nav class="mt-2">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-penjualan-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-penjualan" type="button" role="tab" aria-controls="nav-penjualan"
                        aria-selected="true">Penjualan</button>
                    <button class="nav-link" id="nav-return-tab" data-bs-toggle="tab" data-bs-target="#nav-return"
                        type="button" role="tab" aria-coFntrols="nav-return" aria-selected="false">Return</button>
                </div>
            </nav>

            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade active show" id="nav-penjualan" role="tabpanel"
                    aria-labelledby="nav-penjualan-tab">
                    @include('partials.widgets.master._table')
                </div>

                <div class="tab-pane fade" id="nav-return" role="tabpanel" aria-labelledby="nav-return-tab"
                    tabindex="0">
                    <div class="table-responsive">
                        <table id="returnPenjualanTable"
                            class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--end::cardBody-->

        <!--begin::Actions-->
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <a href="{{ route('laporan.index') }}" class="btn btn-danger me-2" id="kt_account_profile_details_submit">
                @include('partials.general._button-indicator', ['label' => __('Back')])
            </a>
        </div>
        <!--end::Actions-->

    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                var returnPenjualanTable = $('#returnPenjualanTable').DataTable({
                    serverSide: true,
                    processing: true,
                    ajax: {
                        url: '{{ route('laporanProductReturnsDatatable') }}',
                        data: function(d) {
                            d.no_laporan = {{ $laporan->no_laporan }}
                        }
                    },
                    columns: [{
                            data: 'nama_produk',
                            name: 'nama_produk',
                        },
                        {
                            data: 'jumlah',
                            name: 'jumlah',
                        },
                        {
                            data: 'satuan',
                            name: 'satuan',
                        },
                    ],
                });
            });
        </script>
    @endpush
</x-base-layout>
