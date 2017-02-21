@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <input type="hidden" id="tok" value="{{ csrf_token() }}" />
                <div class="panel panel-default">
                    <div class="panel-heading">Departments <a href="{{ route('eon.admin.departments.create') }}" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus"></span></a><div class="col-md-6 pull-right"><input type="text" id="txt_search" class="form-control" onkeyup="search()" placeholder="Search Departments.."></div><div class="clearfix"></div></div>
                    <table class="panel-body table table-hover table-striped" id="department-table">
                        <thead>
                        <tr>
                            <th class="col-md-1">#</th>
                            <th class="col-md-7">Department</th>
                            <th class="col-md-2"># Roles</th>
                            <th class="col-md-2">Remove</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($departments as $index => $department)
                            <tr class="clickable-row" data-href="{{ route('eon.admin.departments.single', $department->id) }}" data-departmentid="{{ $department->id }}">
                                <a href="">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $department->name }}</td>
                                    <td>{{ $department->users_roles->count() }}</td>
                                    <td><button type="button" class="remove-department btn btn-danger btn-xs" data-departmentid="{{ $department->id }}">Remove</button></td>
                                </a>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="list-group">
                        <a href="{{ route('eon.admin.roles') }}" class="list-group-item">
                            Roles
                        </a>
                        <a href="{{ route('eon.admin.permissions') }}" class="list-group-item">
                            Permissions
                        </a>
                        <a href="{{ route('eon.admin.roles.users') }}" class="list-group-item">Users' Roles</a>
                        <a href="{{ route('eon.admin.departments') }}" class="list-group-item active">Departments</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script>
        $(document).ready(function($) {
            var _token = $('#tok').val();

            $('.clickable-row').on('click', '.remove-department', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var department_id = $(this).data('departmentid');

                var url = '{{ route('eon.admin.departments.delete') }}';
                url = url.replace('--department--', department_id);

                $('.clickable-row[data-departmentid="' + department_id + '"]').hide();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {_token: _token},
                    success: function(res) {
                        console.log('res', res);
                        if(res.hasOwnProperty('success')) {
                            if(res.success) {
                                $('.clickable-row[data-departmentid="' + department_id + '"]').remove();
                            } else {
                                $('.clickable-row[data-departmentid="' + department_id + '"]').show();
                                alert(res.error_messages);
                            }
                        }
                    },
                    error: function(res) {
                        console.log('res', res);
                        $('.clickable-row[data-departmentid="' + department_id + '"]').show();
                    }
                });
            });

            $(".clickable-row").click(function() {
                window.document.location = $(this).data("href");
            });
        });
        function search() {
            // Declare variables
            var input, filter, table, tr, td, i;
            input = document.getElementById("txt_search");
            filter = input.value.toLowerCase();
            table = document.getElementById("roles-table");
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