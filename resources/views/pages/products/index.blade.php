<x-base-layout>

    <!--begin::Card-->
    <div class="card">
        <form action='{{ route('products.cetak-barcode') }}' method="post" id="cetak-barcode">
            @csrf
            <div class="card-header pt-6 d-flex justify-content-end">
                @can('manage shop')
                    <div data-bs-toggle="tooltip" data-bs-placement="left" data-bs-trigger="hover" title="Tambahkan Produk">
                        <a href="{{ theme()->getPageUrl('products/create') }}" class="btn btn-sm btn-primary fw-bolder me-2">
                            Create
                        </a>
                    </div>
                @endcan

                {{-- <div data-bs-toggle="tooltip" data-bs-placement="left" data-bs-trigger="hover" title="Cetak Barcode">
                    <button onclick="cetakBarcode()" class="btn btn-sm btn-primary fw-bolder">
                        Cetak Barcode
                    </button>
                </div> --}}
            </div>
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
        </form>

    </div>
    <!--end::Card-->

    @push('scripts')
        <script>
            $(function() {
                $('#select-all').on('click', function() {
                    $(':checkbox').prop('checked', this.checked);
                });
            });

            function cetakBarcode() {
                if ($('input:checked').length < 1) {
                    alert('Pilih data yang akan dicetak');
                    return;
                } else {
                    $('#cetak-barcode')
                        .submit();
                }
            }
        </script>
    @endpush
</x-base-layout>
