<x-base-layout>
    <div class="card">
        <!--begin::cardBody-->
        <form action="{{ route('products.update', ['product' => $product->id]) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
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
                    <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Foto Produk') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Image input-->
                        <div class="image-input image-input-outline {{ url('storage/' . $product->foto) ? '' : 'image-input-empty' }}"
                            data-kt-image-input="true"
                            style="background-image: url({{ asset(theme()->getMediaUrlPath() . 'avatars/blank.png') }})">
                            <!--begin::Preview existing avatar-->
                            <div class="image-input-wrapper w-125px h-125px"
                                style="background-image: {{ url('storage/' . $product->foto) ? 'url(' . asset('storage/' . $product->foto) . ')' : 'none' }};">
                            </div>
                            <!--end::Preview existing avatar-->

                            <!--begin::Label-->
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Ganti Foto">
                                <i class="bi bi-pencil-fill fs-7"></i>

                                <!--begin::Inputs-->
                                <input type="file" name="foto" accept=".png, .jpg, .jpeg" />
                                {{-- <input type="hidden" name="foto_remove" /> --}}
                                <!--end::Inputs-->
                            </label>
                            <!--end::Label-->

                            <!--begin::Cancel-->
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Batal Foto">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <!--end::Cancel-->

                            <!--begin::Remove-->
                            {{-- <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Hapus Foto">
                                <i class="bi bi-x fs-2"></i>
                            </span> --}}
                            <!--end::Remove-->
                        </div>
                        <!--end::Image input-->

                        <!--begin::Hint-->
                        <div class="form-text">Support file: png, jpg, jpeg.</div>
                        <!--end::Hint-->
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
                                    value="{{ $product->kode }}" />
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
                                    value="{{ $product->nama_produk }}" />
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
                        <span class="required">{{ __('Kategori') }}</span>

                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                            title="{{ __('kategori produk') }}"></i>
                    </label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row">
                        <select name="kategori_id" aria-label="{{ __('Pilih kategori') }}" data-control="select2"
                            data-placeholder="{{ __('Pilih kategori...') }}"
                            class="form-select form-select-solid form-select-lg fw-bold" required>
                            <option value="">{{ __('Pilih kategori...') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $category->id == $product->kategori_id ? 'selected' : '' }}>
                                    {{ $category->nama }}</option>
                            @endforeach
                        </select>
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
                            class="form-select form-select-solid form-select-lg fw-bold" required>
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
                    <label
                        class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Harga, Stok & Diskon') }}</label>
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
                                        placeholder="Harga" value="{{ $product->harga }}" />
                                </div>
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-lg-4 fv-row">
                                <input type="number" min="0" name="stok"
                                    class="form-control form-control-lg form-control-solid" placeholder="Stok"
                                    value="{{ $product->stok }}" />
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-lg-2 fv-row">
                                <div class="input-group">
                                    <input type="number" min="0" name="diskon"
                                        class="form-control form-control-lg form-control-solid" placeholder="Diskon"
                                        value="{{ $product->diskon }}" required />
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
                <a href="{{ route('products.index') }}" class="btn btn-danger me-2"
                    id="kt_account_profile_details_submit">
                    @include('partials.general._button-indicator', ['label' => __('Back')])
                </a>
                <button type="submit" class="btn btn-success" id="kt_account_profile_details_submit">
                    @include('partials.general._button-indicator', ['label' => __('Save Changes')])
                </button>
            </div>
            <!--end::Actions-->
    </div>
    </form>
</x-base-layout>
