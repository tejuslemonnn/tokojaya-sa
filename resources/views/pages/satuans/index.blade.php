<x-base-layout>

    <!--begin::Card-->
    <div class="card">
        @can('manage shop')
            <div class="card-header pt-6 d-flex justify-content-end">
                <div data-bs-toggle="tooltip" data-bs-placement="left" data-bs-trigger="hover" title="Tambahkan Kategori">
                    <a href="{{ theme()->getPageUrl('categories/create') }}" class="btn btn-sm btn-primary fw-bolder">
                        Create
                    </a>
                </div>
            </div>
        @endcan
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

            @include('partials.widgets.master._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

</x-base-layout>
