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

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-penjualan-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-penjualan" type="button" role="tab" aria-controls="nav-penjualan"
                        aria-selected="true">Penjualan</button>
                    <button class="nav-link" id="nav-return-tab" data-bs-toggle="tab" data-bs-target="#nav-return"
                        type="button" role="tab" aria-coFntrols="nav-return" aria-selected="false">Return</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-penjualan" role="tabpanel"
                    aria-labelledby="nav-penjualan-tab" tabindex="0">
                    @include('partials.widgets.master._table')
                </div>
                <div class="tab-pane fade" id="nav-return" role="tabpanel" aria-labelledby="nav-return-tab"
                    tabindex="0">
                    <table id="returnProductTable" class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" style="width:100%">
                        <thead>
                            <tr>
                                <th>No Laporan</th>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Deskripsi</th>
                                <th>Tanggal</th>
                                {{-- <th>Action</th> --}}
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
                $('#returnProductTable').DataTable({
                    serverSide: true,
                    processing:true,
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
