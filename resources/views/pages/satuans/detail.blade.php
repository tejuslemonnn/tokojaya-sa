<x-base-layout>
    <div class="card">
        <!--begin::cardBody-->
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
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Nama Satuan') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <input type="text" name="nama"
                                    class="form-control form-control-lg form-control-solid" placeholder="Nama Satuan"
                                    value="{{ $satuan->nama }}" required readonly/>
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
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Kode') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <input type="text" name="kode"
                                    class="form-control form-control-lg form-control-solid" placeholder="Kode"
                                    value="{{ $satuan->kode }}" required readonly />
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
                <a href="{{ route('categories.index') }}" class="btn btn-danger me-2" id="kt_account_profile_details_submit">
                    @include('partials.general._button-indicator', ['label' => __('Back')])
                </a>
            </div>
            <!--end::Actions-->
    </div>
</x-base-layout>
