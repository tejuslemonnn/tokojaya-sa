@php
    $chartColor = $chartColor ?? 'primary';
    $chartHeight = $chartHeight ?? '175px';
@endphp

<!--begin::Mixed Widget 2-->
<div class="card {{ $class }}">
    <!--begin::Header-->
    <div class="card-header border-0 bg-{{ $chartColor }} py-5">
        <h3 class="card-title fw-bolder text-white">Sales Statistics</h3>

        <div class="card-toolbar">
        </div>
    </div>
    <!--end::Header-->

    <!--begin::Body-->
    <div class="card-body p-0">
        <!--begin::Chart-->
        <div class="income-statistics-chart card-rounded-bottom bg-{{ $chartColor }}"
            data-kt-color="{{ $chartColor }}" data-kt-chart-url="{{ route('incomeStatistics') }}"
            style="height: {{ $chartHeight }}"></div>
        <!--end::Chart-->

        <!--begin::Stats-->
        <div class="card-p mt-n20 position-relative">
            <!--begin::Row-->
            <div class="row g-0">
                <!--begin::Col-->
                <div class="col bg-light-success shadow px-6 py-8 rounded-2 me-7 mb-7">
                    <a href="{{ route('laporan.index') }}">
                        {!! theme()->getSvgIcon('icons/duotune/general/gen032.svg', 'svg-icon-3x svg-icon-success d-block my-2') !!}
                        <h2 class="text-success fw-normal fs-6">
                            Pendapatan: <strong>Rp.{{convertRupiah($totalIncome)}}</strong>
                        </h2>
                    </a>
                </div>
                <!--end::Col-->

                <!--begin::Col-->
                <div class="col bg-light-primary px-6 py-8 rounded-2 mb-7">
                    <a href="{{ route('products.index') }}">
                        {!! theme()->getSvgIcon('icons/duotune/finance/fin006.svg', 'svg-icon-3x svg-icon-primary d-block my-2') !!}
                        <h2 class="text-primary fw-normal fs-6">
                            Produk: <strong>{{ $productCounts }} item</strong>
                        </h2>
                    </a>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
        </div>
        <!--end::Stats-->
    </div>
    <!--end::Body-->
</div>
<!--end::Mixed Widget 2-->
