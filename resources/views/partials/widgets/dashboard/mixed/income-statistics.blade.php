@php
    $chartColor = $chartColor ?? 'primary';
    $chartHeight = $chartHeight ?? '175px';
@endphp

<!--begin::Mixed Widget 7-->
<div class="card {{ $class }}">
    <!--begin::Body-->
    <div class="card-body d-flex flex-column p-0">
        <!--begin::Stats-->
        <div class="flex-grow-1 card-p pb-0">
            <div class="d-flex flex-stack flex-wrap">
                <div class="me-2">
                    <a href="#" class="text-dark text-hover-primary fw-bolder fs-3">Statistik Penjualan</a>
                </div>

                <div class="fw-bolder fs-3 text-{{ $chartColor }}">
                    Rp.{{convertRupiah($totalIncome)}}
                </div>
            </div>
        </div>
        <!--end::Stats-->

        <!--begin::Chart-->
        <div class="income-statistics-chart card-rounded-bottom" data-kt-chart-color="{{ $chartColor }}" data-kt-chart-url="{{ route('incomeStatistics') }}" style="height: {{ $chartHeight }}"></div>
        <!--end::Chart-->
    </div>
    <!--end::Body-->
</div>
<!--end::Mixed Widget 7-->
