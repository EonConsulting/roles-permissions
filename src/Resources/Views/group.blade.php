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
                    <div class="panel-heading">Group</div>
                    <div class="panel-body">
                        <input type="text" id="txt_group" class="form-control" value="{{ $group->name }}" />
                        <label class="hidden text" id="response"></label>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">User Roles <div class="col-md-6 pull-right"><input type="text" id="txt_search" class="form-control" onkeyup="search()" placeholder="Search Users.."></div><div class="clearfix"></div></div>
                    <table class="panel-body table table-hover table-striped" id="users-table">
                        <thead>
                        <tr>
                            <th class="col-md-7">User</th>
                            <th class="col-md-2">Role</th>
                            <th class="col-md-2">Remove</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $index => $user)
                            <tr class="clickable-row" data-href="{{ route('eon.admin.roles.users.single', $user->id) }}" data-userid="{{ $user->id }}">
                                <a href="">
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $roles[$user->pivot->role_id] }}</td>
                                    <td><button type="button" class="remove-role btn btn-danger btn-xs" data-roleid="{{ $user->pivot->role_id }}" data-userid="{{ $user->id }}">Remove</button></td>
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
                        <a href="{{ route('eon.admin.roles.users') }}" class="list-group-item">Users' Roles</a>
                        <a href="{{ route('eon.admin.groups') }}" class="list-group-item active">Groups</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script src="/vendor/roles/js/select2.js"></script>
    <script>
        $(document).ready(function($) {
            var _token = $('#tok').val();
            var group_id = '{{ $group->id }}';

            $(document).on('change', '#txt_group', function() {
                var val = $(this).val();
                console.log('val', val);

                $('#response').addClass('text-default').removeClass('hidden').removeClass('text-danger').removeClass('text-success').html('<br />Saving...');

                $.ajax({
                    url: '/admin/groups/' + group_id,
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

            $(document).on('click', '.remove-role', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var role_id = $(this).data('roleid');
                var user_id = $(this).data('userid');


                var url = '{{ route('eon.admin.roles.users.role') }}';
                url = url.replace('--user--', user_id);
                url = url.replace('--role--', role_id);
                url = url.replace('--group--', group_id);
                console.log('url', url);

                $('.clickable-row[data-roleid="' + role_id + '"][data-userid="' + user_id + '"]').hide();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {_token: _token},
                    success: function(res) {
                        console.log('res', res);
                        if(res.hasOwnProperty('success')) {
                            if(res.success) {
                                $('.clickable-row[data-roleid="' + role_id + '"][data-userid="' + user_id + '"]').remove();
                            } else {
                                $('.clickable-row[data-roleid="' + role_id + '"][data-userid="' + user_id + '"]').show();
                            }
                        }
                    },
                    error: function(res) {
                        console.log('res', res);
                        $('.clickable-row[data-roleid="' + role_id + '"][data-userid="' + user_id + '"]').show();
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
            table = document.getElementById("users-table");
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