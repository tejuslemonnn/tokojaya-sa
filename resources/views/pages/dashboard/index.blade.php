<x-base-layout>

    <!--begin::row-->
    <div class="row mb-6">
        <div class="col-xxl-4">
            <div class="col bg-light-success shadow px-6 py-8 rounded-2">
                <a href="{{ route('laporan.index') }}">
                    {!! theme()->getSvgIcon('icons/duotune/general/gen032.svg', 'svg-icon-3x svg-icon-success d-block my-2') !!}
                    <h2 class="text-success fw-normal fs-6">
                        Pendapatan: <strong>Rp.{{ convertRupiah($totalIncome) }}</strong>
                    </h2>
                </a>
            </div>
        </div>
        <div class="col-xxl-4">
            <div class="col bg-light-primary shadow px-6 py-8 rounded-2">
                <a href="{{ route('laporan.index') }}">
                    {!! theme()->getSvgIcon('icons/duotune/general/gen032.svg', 'svg-icon-3x svg-icon-primary d-block my-2') !!}
                    <h2 class="text-primary fw-normal fs-6">
                        Pendapatan Hari Ini: <strong>Rp.{{ convertRupiah($totalIncomeToday) }}</strong>
                    </h2>
                </a>
            </div>
        </div>
        <div class="col-xxl-4">
            <!--begin::Col-->
            <div class="col bg-light-primary shadow px-6 py-8 rounded-2 mb-7">
                <a href="{{ route('products.index') }}">
                    {!! theme()->getSvgIcon('icons/duotune/finance/fin006.svg', 'svg-icon-3x svg-icon-primary d-block my-2') !!}
                    <h2 class="text-primary fw-normal fs-6">
                        Produk: <strong>{{ $productCounts }} item</strong>
                    </h2>
                </a>
            </div>
            <!--end::Col-->
        </div>
    </div>
    <!--end::row-->

    <!--begin::Row-->
    <div class="row gy-5 g-xl-8">
        <!--begin::Col-->
        <div class="col-xxl">
            {{ theme()->getView('partials/widgets/dashboard/mixed/income-statistics', ['class' => 'card-xxl-stretch mb-5 mb-xl-8', 'chartColor' => 'danger', 'chartHeight' => '200px', 'totalIncome' => $totalIncome]) }}
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    <!--begin::Row-->
    <div class="row g-5 gx-xxl-8">
        <!--begin::Col-->
        {{-- <div class="col-xxl-4">
            {{ theme()->getView('partials/widgets/mixed/_widget-5', array('class' => 'card-xxl-stretch mb-xl-3', 'chartColor' => 'success', 'chartHeight' => '150px')) }}
        </div> --}}
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-xxl">
            {{ theme()->getView('partials/widgets/dashboard/tables/products', ['class' => 'card-xxl-stretch mb-5 mb-xxl-8', 'products' => $productsLowestStock]) }}
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    <!--begin::Row-->
    <div class="row g-5 gx-xxl-8">
        <!--begin::Col-->
        {{-- <div class="col-xxl-4">
            {{ theme()->getView('partials/widgets/mixed/_widget-5', array('class' => 'card-xxl-stretch mb-xl-3', 'chartColor' => 'success', 'chartHeight' => '150px')) }}
        </div> --}}
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-xxl">
            {{ theme()->getView('partials/widgets/dashboard/tables/non-best-seller-products', ['class' => 'card-xxl-stretch mb-5 mb-xxl-8', 'products' => $productsNonBestSeller]) }}
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-xxl">
            {{ theme()->getView('partials/widgets/dashboard/tables/best-seller-products', ['class' => 'card-xxl-stretch mb-5 mb-xxl-8', 'products' => $productsBestSeller]) }}
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

</x-base-layout>
