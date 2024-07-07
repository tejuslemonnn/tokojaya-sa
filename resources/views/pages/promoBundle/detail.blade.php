<x-base-layout>
    <div class="card">
        <!--begin::cardBody-->
        <form action="{{ theme()->getPageUrl('promo-bundle.store') }}" method="post">
            @csrf
            <div class="card-body p-6">
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

                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Nama Bundel') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <input type="text" name="nama_bundel"
                                    class="form-control form-control-lg form-control-solid" placeholder="Nama Bundel"
                                    value="{{ $promoBundle->nama_bundel }}" disabled />
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Deskripsi') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <input type="text" name="deskripsi"
                                    class="form-control form-control-lg form-control-solid" placeholder="Deskripsi"
                                    value="{{ $promoBundle->deskripsi }}" disabled />
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Mulai Berlaku') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <input type="date" name="mulai_berlaku"
                                    class="form-control form-control-lg form-control-solid" placeholder="Mulai Berlaku"
                                    value="{{ Carbon\Carbon::parse($promoBundle->mulai_berlaku)->format('Y-m-d') }}"
                                    disabled />
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Selesai Berlaku') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <input type="date" name="selesai_berlaku"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="Selesai Berlaku"
                                    value="{{ Carbon\Carbon::parse($promoBundle->selesai_berlaku)->format('Y-m-d') }}"
                                    disabled />
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Kode Barcode') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <input type="number" min="0" name="kode_barcode"
                                    class="form-control form-control-lg form-control-solid" placeholder="Kode Barcode"
                                    value="{{ $promoBundle->kode_barcode }}" disabled />
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Harga asli') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <input type="number" min="0" name="harga_asli"
                                    class="form-control form-control-lg form-control-solid" placeholder="Harga asli"
                                    value="{{ $promoBundle->harga_asli }}" disabled />
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Harga Promo') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <input type="number" min="0" name="harga_promo"
                                    class="form-control form-control-lg form-control-solid" placeholder="Harga Promo"
                                    value="{{ $promoBundle->harga_promo }}" disabled />
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <hr>
                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <div class="col-lg-4">
                        <label class="col-form-zlabel required fw-bold fs-6 me-2">{{ __('Beli Produk') }}</label>
                    </div>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <table id="datatable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>QTY</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody id="show_buy_product">
                                        @if ($promoBundle->promoBundleItems)
                                            @php
                                                $buyProducts = $promoBundle->promoBundleItems->where('tipe', 'Beli');
                                            @endphp
                                            @foreach ($buyProducts as $index => $buyProduct)
                                                <tr>
                                                    <td>
                                                        <select
                                                            class="form-select buyProductSelect js-example-theme-single"
                                                            aria-label="Default select example" name="product_id[]"
                                                            disabled>
                                                            <option value="" selected>Choose a product</option>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}"
                                                                    {{ $buyProduct->product->id == $product->id ? 'selected' : '' }}>
                                                                    {{ $product->nama_produk }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="qty[]" class="form-control qty"
                                                            value="{{ $buyProduct->qty }}" min=0 disabled>
                                                    <td><input type="number" name="harga[]" class="form-control harga"
                                                            min="0" disabled
                                                            value="{{ $buyProduct->product->harga * $buyProduct->qty }}">
                                                    </td>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <hr>
                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <div class="col-lg-4">
                        <label class="col-form-zlabel required fw-bold fs-6 me-2">{{ __('Gratis Produk') }}</label>
                    </div>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <table id="datatable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>QTY</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody id="show_free_product">
                                        @if ($promoBundle->promoBundleItems)
                                            @php
                                                $freeProducts = $promoBundle->promoBundleItems->where('tipe', 'Gratis');
                                            @endphp
                                            @foreach ($freeProducts as $index => $freeProduct)
                                                <tr>
                                                    <td>
                                                        <select
                                                            class="form-select freeProductSelect js-example-theme-single"
                                                            aria-label="Default select example" name="product_id[]"
                                                            disabled>
                                                            <option value="" selected>Choose a product</option>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}"
                                                                    {{ $freeProduct->product->id == $product->id ? 'selected' : '' }}>
                                                                    {{ $product->nama_produk }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" name="tipe[]" value="2" />
                                                    </td>
                                                    <td>
                                                        <input type="number" name="qty[]" class="form-control qty"
                                                            value="{{ $freeProduct->qty }}" min=0 disabled>
                                                    <td><input type="number" name="harga[]"
                                                            class="form-control harga" min="0" disabled
                                                            value="{{ $freeProduct->product->harga * $freeProduct->qty }}">
                                                    </td>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

            </div>
            <!--end::cardBody-->


            <!--begin::Actions-->
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('promo-bundle.index') }}" class="btn me-2 btn-danger"
                    id="kt_account_profile_details_submit">
                    @include('partials.general._button-indicator', ['label' => __('Back')])
                </a>
            </div>
            <!--end::Actions-->
    </div>
    </form>
</x-base-layout>
