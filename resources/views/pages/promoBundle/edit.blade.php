<x-base-layout>
    <div class="card">
        <!--begin::cardBody-->
        <form action="{{ route('promo-bundle.update', ['promo_bundle' => $promoBundle->id]) }}" method="post">
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
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Nama Bundel') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <input type="text" name="nama_bundel"
                                    class="form-control form-control-lg form-control-solid" placeholder="Nama Bundel"
                                    value="{{ $promoBundle->nama_bundel }}" required />
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
                    <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('Deskripsi') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <input type="text" name="deskripsi"
                                    class="form-control form-control-lg form-control-solid" placeholder="Deskripsi"
                                    value="{{ $promoBundle->deskripsi }}" />
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
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Mulai Berlaku') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <input type="date" name="mulai_berlaku"
                                    class="form-control form-control-lg form-control-solid" placeholder="Mulai Berlaku"
                                    value="{{ Carbon\Carbon::parse($promoBundle->mulai_berlaku)->format('Y-m-d') }}"
                                    required />
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
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Selesai Berlaku') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <input type="date" name="selesai_berlaku"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="Selesai Berlaku"
                                    value="{{ Carbon\Carbon::parse($promoBundle->selesai_berlaku)->format('Y-m-d') }}"
                                    required />
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
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Kode Barcode') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <input type="number" min="0" name="kode_barcode"
                                    class="form-control form-control-lg form-control-solid" placeholder="Kode Barcode"
                                    value="{{ $promoBundle->kode_barcode }}" required />
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
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Harga asli') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <input type="number" min="0" name="harga_asli"
                                    class="form-control form-control-lg form-control-solid" placeholder="Harga asli"
                                    value="{{ $promoBundle->harga_asli }}" readonly />
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
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('Harga Promo') }}</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <input type="number" min="0" name="harga_promo"
                                    class="form-control form-control-lg form-control-solid" placeholder="Harga Promo"
                                    value="{{ $promoBundle->harga_promo }}" required />
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <hr>
                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <div class="col-lg-4">
                        <label class="col-form-zlabel required fw-bold fs-6 me-2">{{ __('Beli Produk') }}</label>
                        <button type="button" class="btn btn-sm btn-secondary add_buy_product-btn"><i
                                class="fas fa-plus"></i>
                            Beli Produk
                        </button>
                    </div>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <table id="datatable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>QTY</th>
                                            <th>Harga</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="show_buy_product">
                                        @if ($promoBundle->promoBundleItems)
                                            @php
                                                $buyProducts = $promoBundle->promoBundleItems->where('tipe', 'Beli');
                                            @endphp
                                            @foreach ($buyProducts as $index => $buyProduct)
                                                <tr>
                                                    <td>
                                                        <select
                                                            class="form-select buyProductSelect js-example-theme-single"
                                                            aria-label="Default select example" name="product_id[]">
                                                            <option value="" selected>Choose a product</option>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}"
                                                                    {{ $buyProduct->product->id == $product->id ? 'selected' : '' }}>
                                                                    {{ $product->nama_produk }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" name="tipe[]" value="1" />
                                                    </td>
                                                    <td>
                                                        <input type="number" name="qty[]" class="form-control qty"
                                                            value="{{ $buyProduct->qty }}" min=0>
                                                    <td><input type="number" name="harga[]"
                                                            class="form-control harga" min="0" readonly
                                                            value="{{ $buyProduct->product->harga * $buyProduct->qty }}">
                                                    </td>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-danger remove_item_btn">-</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->

                <hr>
                <!--begin::Input group-->
                <div class="row mb-6">
                    <!--begin::Label-->
                    <div class="col-lg-4">
                        <label class="col-form-zlabel required fw-bold fs-6 me-2">{{ __('Gratis Produk') }}</label>
                        <button type="button" class="btn btn-sm btn-secondary add_free_product-btn"><i
                                class="fas fa-plus"></i>
                            Gratis Produk
                        </button>
                    </div>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg fv-row">
                                <table id="datatable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>QTY</th>
                                            <th>Harga</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="show_free_product">
                                        @if ($promoBundle->promoBundleItems)
                                            @php
                                                $freeProducts = $promoBundle->promoBundleItems->where('tipe', 'Gratis');
                                            @endphp
                                            @foreach ($freeProducts as $index => $freeProduct)
                                                <tr>
                                                    <td>
                                                        <select
                                                            class="form-select freeProductSelect js-example-theme-single"
                                                            aria-label="Default select example" name="product_id[]">
                                                            <option value="" selected>Choose a product</option>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}"
                                                                    {{ $freeProduct->product->id == $product->id ? 'selected' : '' }}>
                                                                    {{ $product->nama_produk }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" name="tipe[]" value="2" />
                                                    </td>
                                                    <td>
                                                        <input type="number" name="qty[]" class="form-control qty"
                                                            value="{{ $freeProduct->qty }}" min=0>
                                                    <td><input type="number" name="harga[]"
                                                            class="form-control harga" min="0" readonly
                                                            value="{{ $freeProduct->product->harga * $freeProduct->qty }}">
                                                    </td>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-danger remove_item_btn">-</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
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
                <a href="{{ route('promo-bundle.index') }}" class="btn me-2 btn-danger"
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

<script>
    $(function() {
        var buyProducts = {!! json_encode($products) !!};
        var freeProducts = {!! json_encode($products) !!};

        function calculateHargaAsli() {
            let totalHargaAsli = {!! $promoBundle->harga_asli !!}
            let totalHargaPromo = {!! $promoBundle->harga_promo !!}

            $('#show_buy_product tr').each(function() {
                const price = parseFloat($(this).find('.buyProductSelect').find(':selected').data(
                    'price'));
                const qty = parseFloat($(this).find('input.qty').val());
                if (!isNaN(price) && !isNaN(qty)) {
                    totalHargaAsli += price * qty;
                    totalHargaPromo += price * qty;
                }
            });

            $('#show_free_product tr').each(function() {
                const price = parseFloat($(this).find('.freeProductSelect').find(':selected').data(
                    'price'));
                const qty = parseFloat($(this).find('input.qty').val());
                if (!isNaN(price) && !isNaN(qty)) {
                    totalHargaAsli += price * qty;
                }
            });

            $('input[name="harga_promo"]').val(totalHargaPromo);
            $('input[name="harga_asli"]').val(totalHargaAsli);
        }

        calculateHargaAsli();


        $(document).on('click', '.remove_item_btn', function(e) {
            e.preventDefault();
            let row_item = $(this).parent().parent();
            $(row_item).remove();
            calculateHargaAsli();
        });

        $(document).on('change', '.buyProductSelect', function(e) {
            calculateHargaAsli();
            let selectedProductId = $(this).val();
            let selectedProduct = buyProducts.find(product => product.id == selectedProductId);
            let selectedProductSatuan = selectedProduct?.satuan?.nama ?? 'satuan';
            $(this).closest('tr').find('.satuanProduct').text(selectedProductSatuan);
            $(this).closest('tr').find('.harga').val(null);

            if (selectedProductSatuan === 'satuan') {
                $(this).closest('tr').find('.qty').prop('readonly', true).val(null);
            } else {
                $(this).closest('tr').find('.qty').prop('readonly', false);
            }
        });

        $(document).on('change', '.freeProductSelect', function(e) {
            calculateHargaAsli();
            let selectedProductId = $(this).val();
            let selectedProduct = freeProducts.find(product => product.id == selectedProductId);
            let selectedProductSatuan = selectedProduct?.satuan?.nama ?? 'satuan';
            $(this).closest('tr').find('.satuanProduct').text(selectedProductSatuan);
            $(this).closest('tr').find('.harga').val(null);

            if (selectedProductSatuan === 'satuan') {
                $(this).closest('tr').find('.qty').prop('readonly', true).val(null);
            } else {
                $(this).closest('tr').find('.qty').prop('readonly', false);
            }
        });

        $(document).on('keyup change', '.qty', function(e) {
            calculateHargaAsli();
            let qty = $(this).val();
            let selectedProductId = $(this).closest('tr').find('.buyProductSelect, .freeProductSelect')
                .val();
            let selectedProduct = buyProducts.find(product => product.id == selectedProductId);

            if (selectedProduct) {
                let hargaAsli = selectedProduct.harga * qty;
                $(this).closest('tr').find('.harga').val(hargaAsli);
            }
        });

        $(".add_buy_product-btn").click(function(e) {
            e.preventDefault();

            let buySelectedValues = [];


            $('.buyProductSelect').each(function(index, element) {
                buySelectedValues.push(parseInt($(element).val()));
            });

            let buyOptions = '';

            buyProducts.forEach(function(product) {
                if (!buySelectedValues.includes(product.id)) {
                    buyOptions +=
                        `<option value="${ product.id }" data-price="${ product.harga }">${ product.nama_produk }</option>`
                }
            });


            var new_row = '<tr>' +
                '<td>' +
                '<select class="form-select buyProductSelect js-example-theme-single" aria-label="Default select example" name="product_id[]" required>' +
                '<option value="" selected>Choose a product</option>' +
                buyOptions +
                '</select>' +
                '<input type="hidden" name="tipe[]" value="1" />' +
                '</td>' +
                '<td>' +
                '<div class="input-group mb-3">' +
                '<input type="number" name="qty[]" class="form-control qty" min="0" required>' +
                '<span class="input-group-text satuanProduct">Satuan</span>' +
                '</div>' +
                '</td>' +
                '<td><input type="number" name="harga[]" class="form-control harga" min="0" readonly></td>' +
                '<td><button class="btn btn-danger remove_item_btn">-</button></td>' +
                '</tr>';
            $('#show_buy_product').append(new_row);

            calculateHargaAsli();
        });

        $(".add_free_product-btn").click(function(e) {
            e.preventDefault();

            let freeSelectedValues = [];

            $('.freeProductSelect').each(function(index, element) {
                freeSelectedValues.push(parseInt($(element).val()));
            });

            let freeOptions = '';

            freeProducts.forEach(function(product) {
                if (!freeSelectedValues.includes(product.id)) {
                    freeOptions +=
                        `<option value="${ product.id }" data-price="${ product.harga }">${ product.nama_produk }</option>`
                }
            });


            var new_row = '<tr>' +
                '<td>' +
                '<select class="form-select freeProductSelect js-example-theme-single" aria-label="Default select example" name="product_id[]" required>' +
                '<option value="" selected>Choose a product</option>' +
                freeOptions +
                '</select>' +
                '<input type="hidden" name="tipe[]" value="2" />' +
                '</td>' +
                '<td>' +
                '<div class="input-group mb-3">' +
                '<input type="number" name="qty[]" class="form-control qty" min="0" required>' +
                '<span class="input-group-text satuanProduct">Satuan</span>' +
                '</div>' +
                '</td>' +
                '<td><input type="number" name="harga[]" class="form-control harga" min="0" readonly></td>' +
                '<td><button class="btn btn-danger remove_item_btn">-</button></td>' +
                '</tr>';
            $('#show_free_product').append(new_row);

            calculateHargaAsli();
        });
    });
</script>
