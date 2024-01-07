    <!--begin::Action--->
    <td class="text-end">
        @if ($item->jumlah == 0)
            <div data-bs-toggle="tooltip" data-bs-placement="left" data-bs-trigger="hover" title="Jumlah produk 0">
                <button type="button" class="btn btn-secondary">
                    Return
                </button>
            </div>
        @else
            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop{{ $item->id }}">
                Return
            </button>
        @endif
    </td>
    <!--end::Action--->
