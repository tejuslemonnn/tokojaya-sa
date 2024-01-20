<x-base-layout>
    <div class="card">
        <!--begin::cardBody-->
        <div class="card-body p-6">
            <div class="row">

                <div class="col-2">
                    <p class="fw-bold">No:</p>
                </div>
                <div class="col-10">{{ $return->no_return }}</div>

                <div class="col-2">
                    <p class="fw-bold">Kasir:</p>
                </div>
                <div class="col-8">
                    <p>{{ $return->kasir->username }}</p>
                </div>

                <div class="col-2 text-end d-flex justify-content-end">
                    <a href="{{ route('invoiceReturn', $return->no_return) }}" class="btn btn-sm btn-primary"
                        target="_blank">
                        Struk
                    </a>

                    <a href="{{route('return.pdfDetail', $return->no_return)}}" class="btn btn-sm btn-danger mx-2">
                        PDF
                    </a>
                </div>

                <div class="col-2">
                    <p class="fw-bold">Tanggal:</p>
                </div>
                <div class="col-10">
                    <p>{{ $return->created_at }}</p>
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

    </div>
</x-base-layout>
