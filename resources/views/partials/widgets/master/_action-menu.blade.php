    <!--begin::Action--->
    <td class="text-end">
        @can('manage shop')
            <form action="{{ $destroyUrl }}" method="post" id="deleteForm">
                @csrf
                @method('DELETE')
                <button type="button"
                    class="btn btn-sm btn-danger my-2 btn-active-light {{ request()->is('categories*') ? 'me-2' : '' }}"
                    onclick="confirmDelete()">
                    Delete
                </button>
            </form>
        @endcan

        <a href="{{ $showUrl }}" class="btn btn-sm btn-warning my-2 mx-2 btn-active-light">
            Detail
        </a>

        @can('manage shop')
            <a href="{{ $editUrl }}" class="btn btn-sm btn-primary my-2 btn-active-light">
                Edit
            </a>
        @endcan

    </td>
    <!--end::Action--->

    <script>
        function confirmDelete() {
            var isConfirmed = confirm('Apakah anda yakin ingin menghapus?');

            if (isConfirmed) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
