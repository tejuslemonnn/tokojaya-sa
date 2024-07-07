<x-base-layout>

    <!--begin::Card-->
    <div class="card">
        @can('manage account')
            <div class="card-header pt-6 d-flex justify-content-end">
                <div class="me-3">
                    <select id="shiftFilter" class="form-select" name="shift">
                        <option value="" selected>All Shifts</option>
                        <option value="1">Shift Pagi</option>
                        <option value="2">Shift Malam</option>
                    </select>
                </div>

                <div data-bs-toggle="tooltip" data-bs-placement="left" data-bs-trigger="hover" title="Tambahkan User">
                    <a href="{{ theme()->getPageUrl('users/create') }}" class="btn btn-sm btn-primary fw-bolder">
                        Create
                    </a>
                </div>
            </div>
        @endcan
        <!--begin::Card body-->
        <div class="card-body pt-6">
            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                    @php
                        Session::forget('success');
                    @endphp
                </div>
            @endif

            <div class="table-responsive">
                <table id="user-table" class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Shift</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Permissions</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <!-- DataTable Body will be loaded dynamically by JavaScript -->
                </table>
            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->


    @push('scripts')
        <script>
            $(document).ready(function() {
                var table = $('#user-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('users.table') }}',
                        data: function(d) {
                            var shiftValue = $('#shiftFilter').val();
                            d.shift = shiftValue;
                        }
                    },
                    columns: [{
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'shift_kerja',
                            name: 'shift_kerja',
                        },
                        {
                            data: 'username',
                            name: 'username'
                        },
                        {
                            data: 'role',
                            name: 'role',
                        },
                        {
                            data: 'permissions',
                            name: 'permissions',
                        },
                        {
                            data: 'phone',
                            name: 'phone',
                        },
                        {
                            data: 'address',
                            name: 'address',
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    order: [
                        [0, 'asc']
                    ],
                });

                $('#shiftFilter').on('change', function() {
                    console.log('Shift Filter Changed');
                    table.draw();
                });
            });
        </script>
    @endpush
</x-base-layout>
