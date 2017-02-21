@extends('layouts.app')

@section('custom-styles')
    <link rel="stylesheet" href="/vendor/roles/css/select2.css">
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">User</div>
                    <div class="panel-body">
                        <input type="text" id="txt_role" class="form-control" value="{{ $user->name }}" />
                        <label class="hidden text" id="response"></label>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Roles <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-add-permission"><span class="glyphicon glyphicon-plus"></span></button> <div class="col-md-6 pull-right"><input type="text" id="txt_search" class="form-control" onkeyup="search()" placeholder="Search Roles.."></div><div class="clearfix"></div></div>
                    <table class="panel-body table table-hover table-striped" id="roles-table">
                        <thead>
                        <tr>
                            <th class="col-md-10">Role</th>
                            <th class="col-md-2">Remove</th>
                        </tr>
                        </thead>
                        <tbody id="tbody">
                        @foreach($roles as $index => $role)
                            <tr disabled="disabled" class="" data-roleid="{{ $role->id }}" data-departmentid="{{ $role->pivot->department_id }}" data-href="{{ route('eon.admin.roles.users.single', $user->id) }}">
                                <a href="">
                                    <td data-item="name" data-roleid="{{ $role->id }}">{{ $role->name }} <small class="label label-success">{!! $departments[$role->pivot->department_id] !!}</small></td>
                                    <td><button type="button" class="remove-role btn btn-danger btn-xs" data-roleid="{{ $role->id }}" data-departmentid="{{ $role->pivot->department_id }}">Remove</button></td>
                                </a>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <input type="hidden" id="tok" value="{{ csrf_token() }}" />
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
                        <a href="{{ route('eon.admin.roles.users') }}" class="list-group-item active">Users' Roles</a>
                        <a href="{{ route('eon.admin.departments') }}" class="list-group-item">Departments</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-add-permission">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add a role to <strong>{{ $user->name }}</strong></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Role(s):</label>
                            <select id="add-permission" class="form-control col-md-12" multiple="multiple">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <label>Department(s):</label>
                            <select id="add-department" class="form-control col-md-12"></select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-permissions">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('custom-scripts')
    <script src="/vendor/roles/js/select2.js"></script>
    <script>
        $(document).ready(function($) {
            var _token = $('#tok').val();
            var user_id = '{{ $user->id }}';

            var permissions = JSON.parse('{!! json_encode($all_roles) !!}');
            console.log('permissions', permissions);

            var held = JSON.parse('{!! json_encode($held) !!}');
            console.log('held', held);

            var unheld = JSON.parse('{!! json_encode($unheld) !!}');
            console.log('unheld', unheld);

            var departments = JSON.parse('{!! json_encode($departments) !!}');
            console.log('departments', departments);

            set_permissions();
            set_departments();

            function set_permissions() {
                $('#add-permission').select2({
                    placeholder: 'Select a Role',
                    data: generate_dropdown(permissions)
                });
            }

            function set_departments() {
                $('#add-department').select2({
                    placeholder: 'Select a Department',
                    data: generate_dropdown(departments)
                });
            }

            function generate_dropdown(arr) {
                var arr_response = [];
                for(var item_id in arr) {
                    arr_response.push({id: item_id, text: arr[item_id]});
                }
                return arr_response;
            }

            $(document).on('click', '.clickable-row', function() {
                window.document.location = $(this).data("href");
            });

            $(document).on('click', '.remove-role', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var role_id = $(this).data('roleid');
                var department_id = $(this).data('departmentid');


                var url = '{{ route('eon.admin.roles.users.role') }}';
                url = url.replace('--user--', user_id);
                url = url.replace('--role--', role_id);
                url = url.replace('--department--', department_id);
                console.log('url', url);

                $('.clickable-row[data-roleid="' + role_id + '"][data-departmentid="' + department_id + '"]').hide();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {_token: _token},
                    success: function(res) {
                        console.log('res', res);
                        if(res.hasOwnProperty('success')) {
                            if(res.success) {
                                $('.clickable-row[data-roleid="' + role_id + '"][data-departmentid="' + department_id + '"]').remove();
                            } else {
                                $('.clickable-row[data-roleid="' + role_id + '"][data-departmentid="' + department_id + '"]').show();
                            }
                        }
                    },
                    error: function(res) {
                        console.log('res', res);
                        $('.clickable-row[data-roleid="' + role_id + '"][data-departmentid="' + department_id + '"]').show();
                    }
                });
            });

            $(document).on('click', '#save-permissions', function() {
                var ids = $('#add-permission').val();
                var department_id = $('#add-department').val();
                console.log('ids', ids);
                console.log('department_id', department_id);

                $('#modal-add-permission').modal('hide');

                if(ids.length > 0) {
                    for(var index in ids) {
                        var id = ids[index];

                        if((permissions.hasOwnProperty(id) && !held.hasOwnProperty(id)) || (permissions.hasOwnProperty(id) && held.hasOwnProperty(id) && held[id].pivot.department_id != department_id)) {
                            create_row(id, permissions[id], department_id);
                            held[id] = permissions[id];
                            set_permissions();

                            $('#add-permission').val('');

                            $.ajax({
                                url: '/admin/users/' + user_id + '/' + id + '/' + department_id,
                                type: 'POST',
                                data: {_token: _token, department_id: department_id},
                                success: function(res) {
                                    console.log('res', res);
                                },
                                error: function(res) {
                                    console.log('error res', res);
                                }
                            });
                        }
                    }
                }
            });

            function create_row(id, name, department_id) {
                console.log('departments[department_id]', departments[department_id]);
                var html = '<tr disabled="disabled" class="clickable-row" data-roleid="' + id + '" data-departmentid="' + department_id + '"><a href=""><td data-item="name" data-roleid="' + id + '" data-departmentid="' + department_id + '">' + name + ' <small class="label label-success">' + departments[department_id] + '</small></td><td><button type="button" class="remove-permission btn btn-danger btn-xs" data-departmentid="' + department_id + '" data-roleid="' + id + '">Remove</button></td></a></tr>';
                $('#tbody').append(html);
            }
        });

        function search() {
            // Declare variables
            var input, filter, roles_table, permissions_table, roles_tr, permissions_tr, td, i;
            input = document.getElementById("txt_search");
            filter = input.value.toLowerCase();
            roles_table = document.getElementById("roles-table");
            roles_tr = roles_table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < roles_tr.length; i++) {
                td = roles_tr[i].getElementsByTagName("td")[0];
                if (td) {
                    if (td.innerHTML.toLowerCase().indexOf(filter) > -1) {
                        roles_tr[i].style.display = "";
                    } else {
                        roles_tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
@endsection