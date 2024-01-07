    <!--begin::Action--->
    <td class="text-end">
        @can('manage shop')
            <form action="{{ $destroyUrl }}" method="post" id="deleteForm{{$model->id}}">
                @csrf
                @method('DELETE')
                <button type="button"
                    class="btn btn-sm btn-danger my-2 btn-active-light"
                    onclick="confirmDelete({{ $model->id }})">
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
        function confirmDelete(userId) {
            var isConfirmed = confirm('Apakah anda yakin ingin menghapus?');

            if (isConfirmed) {
                document.getElementById(`deleteForm${userId}`).submit();
            }
        }
    </script>
