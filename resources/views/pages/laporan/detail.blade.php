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

            @include('partials.widgets.master._table')

        </div>
        <!--end::cardBody-->

        <!--begin::Actions-->
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <a href="{{ route('laporan.index') }}" class="btn btn-danger me-2" id="kt_account_profile_details_submit">
                @include('partials.general._button-indicator', ['label' => __('Back')])
            </a>
        </div>
        <!--end::Actions-->

        <!-- Modal -->
        @foreach ($laporan->laporan_products as $laporan_product)
            <div class="modal fade" id="staticBackdrop{{ $laporan_product->id }}" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Return Product</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form action="{{ route('returnProduct') }}" method="post">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="laporan_id" value="{{ $laporan->id }}">
                                <input type="hidden" name="laporan_product_id" value="{{ $laporan_product->id }}">
                                <input type="hidden" name="product_id" value="{{ $laporan_product->product->id }}">

                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon2">Produk</span>
                                    <input type="text" class="form-control"
                                        value="{{ $laporan_product->product->nama_produk }}" disabled>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon2">Jumlah</span>
                                    <input type="number" class="form-control" name="jumlah">
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon2">Satuan</span>
                                    <select class="form-select" aria-label="Satuan" name="satuan">
                                        @foreach ($satuans as $satuan)
                                            <option value="{{ $satuan->nama }}"
                                                {{ $laporan_product->satuan == $satuan->nama ? 'selected' : '' }}>
                                                {{ $satuan->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon2">Deskripsi</span>
                                    <select class="form-select" aria-label="Deskripsi" name="deskripsi">
                                        <option value="Barang Rusak">Barang Rusak</option>
                                        <option value="Expired">Expired</option>
                                    </select>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Return</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-base-layout>
