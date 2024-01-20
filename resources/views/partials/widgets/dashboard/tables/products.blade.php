<!--begin::Tables Widget 5-->
<div class="card {{ $class }}">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Produk Stok Terendah</span>

        </h3>
    </div>
    <!--end::Header-->

    <!--begin::Body-->
    <div class="card-body py-3">
        <div class="tab-content">
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                        <!--begin::Table head-->
                        <thead>
                            <tr class="border-0">
                                <th class="p-0 min-w-150px"></th>
                                <th class="p-0 min-w-140px"></th>
                                <th class="p-0 min-w-110px"></th>
                                <th class="p-0 min-w-50px"></th>
                            </tr>
                        </thead>
                        <!--end::Table head-->

                        <!--begin::Table body-->
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>
                                        <a href="#"
                                            class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{ $product->nama_produk }}</a>
                                        <span class="text-muted fw-bold d-block">{{ $product->category->nama}}</span>
                                    </td>
                                    <td class="text-end">
                                        <span
                                            class="badge badge-light-danger">{{ $product->stok }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{route('products.show', $product->id)}}"
                                            class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                            {!! theme()->getSvgIcon('icons/duotune/arrows/arr064.svg', 'svg-icon-2') !!}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                </div>
                <!--end::Table-->
        </div>
    </div>
    <!--end::Body-->
</div>
<!--end::Tables Widget 5-->
