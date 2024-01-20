<x-base-layout>
    <div class="card">
        <!--begin::cardBody-->
        <div class="card-body p-6">
            <!--begin::Input group-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Foto Produk') }}</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <div class="image-input image-input-outline" data-kt-image-input="true"
                        style="background-image: url({{ asset(theme()->getMediaUrlPath() . 'avatars/blank.png') }})">
                        <!--begin::Preview existing avatar-->
                        <div class="image-input-wrapper w-125px h-125px"
                            style="background-image: url('{{ url('storage/' . $product->foto) }}');">
                        </div>
                        <!--end::Preview existing avatar-->
                    </div>

                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Kode') }}</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg fv-row">
                            <input type="number" min="0" name="kode"
                                class="form-control form-control-lg form-control-solid" placeholder="Kode"
                                value="{{ $product->kode }}" disabled
                                style="-webkit-appearance: none;margin: 0;-moz-appearance: textfield;" disabled />
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
                <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Nama Barang') }}</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg fv-row">
                            <input type="text" name="nama_produk"
                                class="form-control form-control-lg form-control-solid" placeholder="Nama Barang"
                                value="{{ $product->nama_produk }}"
                                style="-webkit-appearance: none;margin: 0;-moz-appearance: textfield;" disabled />
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
                <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Kategori') }}</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg fv-row">
                            <input type="text" name="kategori"
                                class="form-control form-control-lg form-control-solid" placeholder="Kategori"
                                value="{{ $product->category->nama }}"
                                style="-webkit-appearance: none;margin: 0;-moz-appearance: textfield;" disabled />
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
                <label class="col-lg-4 col-form-label fw-bold fs-6">
                    <span class="required">{{ __('Satuan') }}</span>

                    <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                        title="{{ __('Satuan produk') }}"></i>
                </label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8 fv-row">
                    <select name="satuan_id" aria-label="{{ __('Pilih satuan') }}" data-control="select2"
                        data-placeholder="{{ __('Pilih satuan...') }}"
                        class="form-select form-select-solid form-select-lg fw-bold" disabled>
                        <option value="">{{ __('Pilih satuan...') }}</option>
                        @foreach ($satuans as $satuan)
                            <option value="{{ $satuan->id }}"
                                {{ $product->satuan->nama == $satuan->nama ? 'selected' : '' }}>
                                {{ $satuan->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Harga, Stok & Diskon') }}</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">Rp.</span>
                                <input type="number" min="0" name="harga"
                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                    placeholder="Harga" value="{{ $product->harga }}"
                                    style="-webkit-appearance: none;margin: 0;-moz-appearance: textfield;" disabled />
                            </div>
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-4 fv-row">
                            <input type="number" min="0" name="stok"
                                class="form-control form-control-lg form-control-solid" placeholder="Stok"
                                value="{{ $product->stok }}"
                                style="-webkit-appearance: none;margin: 0;-moz-appearance: textfield;" disabled />
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-2 fv-row">
                            <div class="input-group">
                                <input type="number" min="0" name="diskon"
                                    class="form-control form-control-lg form-control-solid" placeholder="diskon"
                                    value="{{ $product->diskon }}"
                                    style="-webkit-appearance: none;margin: 0;-moz-appearance: textfield;" disabled />
                                <span class="input-group-text" id="basic-addon1">%</span>
                            </div>
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
            <a href="{{ route('products.index') }}" class="btn btn-danger" id="kt_account_profile_details_submit">
                @include('partials.general._button-indicator', ['label' => __('Back')])
            </a>
        </div>
        <!--end::Actions-->
    </div>
</x-base-layout>
