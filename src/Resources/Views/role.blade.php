@extends('layouts.app')

@section('custom-styles')
    <link rel="stylesheet" href="/vendor/roles/css/select2.css">
    <link rel="stylesheet" type="text/css" href="/vendor/roles/css/font-awesome.css" />
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Role</div>
                    <div class="panel-body">
                        <input type="text" id="txt_role" class="form-control" value="{{ $role->name }}" />
                        <label class="hidden text" id="response"></label>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Permissions <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-add-permission"><span class="fa fa-plus"></span></button> <div class="col-md-6 pull-right"><input type="text" id="txt_search" class="form-control" onkeyup="search()" placeholder="Search Permissions.."></div><div class="clearfix"></div></div>
                    <table class="panel-body table table-hover table-striped" id="permissions-table">
                        <thead>
                        <tr>
                            <th class="col-md-10">Permission</th>
                            <th class="col-md-2">Remove</th>
                        </tr>
                        </thead>
                        <tbody id="tbody">
                        @foreach($permissions as $index => $permission)
                            <tr disabled="disabled" class="clickable-row" data-roleid="{{ $role->id }}" data-permissionid="{{ $permission->id }}" data-href="{{ route('eon.admin.permissions.single', $permission->id) }}">
                                <a href="">
                                    <td data-item="name" data-roleid="{{ $role->id }}" data-permissionid="{{ $permission->id }}">{{ $permission->name }}</td>
                                    <td><button type="button" class="remove-permission btn btn-danger btn-xs" data-roleid="{{ $role->id }}" data-permissionid="{{ $permission->id }}">Remove</button></td>
                                </a>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <input type="hidden" id="tok" value="{{ csrf_token() }}" />
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-add-permission">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add a permission to the <strong>{{ $role->name }}</strong> role</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-md-12">
                            <select id="add-permission" class="form-control col-md-12" multiple="multiple">
                                <option value=""></option>
                            </select>
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
            var role_id = '{{ $role->id }}';

            var permissions = JSON.parse('{!! json_encode($all_permissions) !!}');
            console.log('permissions', permissions);

            var held = JSON.parse('{!! json_encode($permissions) !!}');
            console.log('held', held);

            var unheld = JSON.parse('{!! json_encode($unheld) !!}');
            console.log('unheld', unheld);

            set_permissions();

            function set_permissions() {
                $('#add-permission').select2({
                    placeholder: 'Select a Permission',
                    data: generate_dropdown(unheld)
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

            $(document).on('change', '#txt_role', function() {
                var val = $(this).val();
                console.log('val', val);

                $('#response').addClass('text-default').removeClass('hidden').removeClass('text-danger').removeClass('text-success').html('<br />Saving...');

                $.ajax({
                    url: '/admin/roles/' + role_id,
                    type: 'POST',
                    data: {_token: _token, name: val},
                    success: function(res) {
                        if(res.hasOwnProperty('success')) {
                            if(res.success) {
                                $('#response').addClass('text-success').removeClass('text-danger').html('<br />Saved.');
                            } else {
                                $('#response').removeClass('text-success').addClass('text-danger').html('<br />Failed to save.');
                            }
                        } else {
                            $('#response').removeClass('text-success').addClass('text-danger').html('<br />Failed to save.');
                        }
                    },
                    error: function(res) {
                        console.log('error res', res);
                        $('#response').removeClass('text-success').addClass('text-danger').html('<br />Failed to save.');
                    }
                });
            });

            $(document).on('click', '.remove-permission', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var role_id = $(this).data('roleid');
                var permission_id = $(this).data('permissionid');


                var url = '{{ route('eon.admin.roles.permission') }}';
                url = url.replace('--role--', role_id);
                url = url.replace('--permission--', permission_id);

                $('.clickable-row[data-roleid="' + role_id + '"][data-permissionid="' + permission_id + '"]').hide();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {_token: _token, role_id: role_id, permission_id: permission_id},
                    success: function(res) {
                        console.log('res', res);
                        if(res.hasOwnProperty('success')) {
                            if(res.success) {
                                $('.clickable-row[data-roleid="' + role_id + '"][data-permissionid="' + permission_id + '"]').remove();
                            } else {
                                $('.clickable-row[data-roleid="' + role_id + '"][data-permissionid="' + permission_id + '"]').show();
                            }
                        }
                    },
                    error: function(res) {
                        console.log('res', res);
                        $('.clickable-row[data-roleid="' + role_id + '"][data-permissionid="' + permission_id + '"]').show();
                    }
                });
            });

            $(document).on('click', '#save-permissions', function() {
                var ids = $('#add-permission').val();
                console.log('ids', ids);

                $('#modal-add-permission').modal('hide');

                if(ids.length > 0) {
                    for(var index in ids) {
                        var id = ids[index];

                        if(permissions.hasOwnProperty(id) && !held.hasOwnProperty(id)) {
                            create_row(id, permissions[id]);
                            held[id] = permissions[id];
                            set_permissions();

                            $('#add-permission').val('');

                            $.ajax({
                                url: '/admin/roles/' + role_id + '/' + id,
                                type: 'POST',
                                data: {_token: _token},
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

            function create_row(id, name) {
                var html = '<tr disabled="disabled" class="clickable-row" data-roleid="' + role_id + '" data-permissionid="' + id + '"><a href=""><th data-item="name" data-roleid="' + role_id + '" data-permissionid="' + id + '">' + name + '</th><th><button type="button" class="remove-permission btn btn-danger btn-xs" data-roleid="' + role_id + '" data-permissionid="' + id + '">Remove</button></th></a></tr>';
                $('#tbody').append(html);
            }
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
                td = tr[i].getElementsByTagName("td")[0];
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
