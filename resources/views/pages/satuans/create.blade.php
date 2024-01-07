<x-base-layout>
    <div class="card">
        <!--begin::cardBody-->
        <form action="{{ theme()->getPageUrl('categories.store') }}" method="post">
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
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Nama Kategori') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <input type="text" name="nama"
                                    class="form-control form-control-lg form-control-solid" placeholder="Nama Kategori"
                                    value="{{ old('nama') }}" required />
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
                                    value="{{ old('kode') }}" required readonly />
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
                <button type="submit" class="btn btn-success" id="kt_account_profile_details_submit">
                    @include('partials.general._button-indicator', ['label' => __('Save Changes')])
                </button>
            </div>
            <!--end::Actions-->
    </div>
    </form>

    @push('scripts')
        <script>
            $(document).ready(function() {
                function generateCodeFromName() {
                    var nameValue = $('[name="nama"]').val();
                    var codeValue = nameValue.toLowerCase().replace(/\s+/g, '-');
                    $('[name="kode"]').val(codeValue);
                }
                $('[name="nama"]').on('input', function() {
                    generateCodeFromName();
                });
                generateCodeFromName();
            });
        </script>
    @endpush
</x-base-layout>
