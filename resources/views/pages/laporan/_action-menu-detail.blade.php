    <!--begin::Action--->
    <td class="text-end">
        @can('manage shop')
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop{{$item->id}}">
                Return
            </button>
        @endcan
    </td>
    <!--end::Action--->
