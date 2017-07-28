@extends('layouts.app')

@section('custom-styles')
    <link rel="stylesheet" type="text/css" href="/vendor/roles/css/font-awesome.css" />
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <input type="hidden" id="tok" value="{{ csrf_token() }}" />
                    <div class="panel-heading">Permissions <a href="{{ route('eon.admin.permissions.create') }}" class="btn btn-primary btn-xs"><span class="fa fa-plus"></span></a><div class="col-md-6 pull-right"><input type="text" id="txt_search" class="form-control" onkeyup="search()" placeholder="Search Permissions.."></div><div class="clearfix"></div></div>
                    <table class="panel-body table table-hover table-striped" id="permissions-table">
                        <thead>
                        <tr>
                            <th class="col-md-1">#</th>
                            <th class="col-md-5">Permission</th>
                            <th class="col-md-2"># Roles</th>
                            <th class="col-md-2"># Used</th>
                            <th class="col-md-2">Remove</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permissions as $index => $permission)
                            <tr class="clickable-row" data-href="{{ route('eon.admin.permissions.single', $permission->id) }}" data-permissionid="{{ $permission->id }}">
                                <a href="">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->roles->count() }}</td>
                                    <td>{{ $permission->users->count() }}</td>
                                    <td><button type="button" class="remove-permission btn btn-danger btn-xs" data-permissionid="{{ $permission->id }}">Remove</button></td>
                                </a>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script>
        $(document).ready(function($) {
            var _token = $('#tok').val();

            $('.clickable-row').on('click', '.remove-permission', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var permission_id = $(this).data('permissionid');

                var url = '{{ route('eon.admin.permissions.delete') }}';
                url = url.replace('--permission--', permission_id);

                $('.clickable-row[data-permissionid="' + permission_id + '"]').hide();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {_token: _token},
                    success: function(res) {
                        console.log('res', res);
                        if(res.hasOwnProperty('success')) {
                            if(res.success) {
                                $('.clickable-row[data-permissionid="' + permission_id + '"]').remove();
                            } else {
                                $('.clickable-row[data-permissionid="' + permission_id + '"]').hide();
                                alert(res.error_messages);
                            }
                        }
                    },
                    error: function(res) {
                        console.log('res', res);
                        $('.clickable-row[data-permissionid="' + permission_id + '"]').hide();
                    }
                });
            });

            $(".clickable-row").click(function(e) {
                if(e.target.type != 'button') {
                    window.document.location = $(this).data("href");
                }
            });
        });

        function search() {
            // Declare variables
            var input, filter, table, tr, td, i;
            input = document.getElementById("txt_search");
            filter = input.value.toLowerCase();
            table = document.getElementById("permissions-table");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    if (td.innerHTML.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
@endsection
