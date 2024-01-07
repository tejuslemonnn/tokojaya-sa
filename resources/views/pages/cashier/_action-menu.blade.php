    <!--begin::Action--->
    <td class="text-end">
        @can('manage sale')
            <form action="{{ route('cashier.deleteCart', $item['id']) }}" method="post" id="deleteForm{{$item['id']}}">
                @csrf
                @method('DELETE')
                <button type="button"
                    class="btn btn-sm btn-danger my-2 me-2 btn-active-light {{ request()->is('categories*') ? 'me-2' : '' }}"
                    onclick="confirmDelete({{$item['id']}})">
                    Delete
                </button>
            </form>
        @endcan

    </td>
    <!--end::Action--->

    <script>
        function confirmDelete(id) {
            var isConfirmed = confirm('Apakah anda yakin ingin menghapus?');

            if (isConfirmed) {
                document.getElementById(`deleteForm${id}`).submit();
            }
        }
    </script>
