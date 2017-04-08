@extends('layouts.admin')

@section('site-title')
    Groups
@endsection

@section('custom-styles')
    <link rel="stylesheet" type="text/css" href="/vendor/roles/css/font-awesome.css" />
@endsection

@section('content')

        <div class="row">
            <div class="col-md-8">
                <input type="hidden" id="tok" value="{{ csrf_token() }}" />
                <div class="panel panel-default">
                    <div class="panel-heading">Groups <a href="{{ route('eon.admin.groups.create') }}" class="btn btn-primary btn-xs"><span class="fa fa-plus"></span></a><div class="col-md-6 pull-right"><input type="text" id="txt_search" class="form-control" onkeyup="search()" placeholder="Search Groups.."></div><div class="clearfix"></div></div>
                    <table class="panel-body table table-hover table-striped" id="group-table">
                        <thead>
                        <tr>
                            <th class="col-md-1">#</th>
                            <th class="col-md-7">Group</th>
                            <th class="col-md-2"># Roles</th>
                            <th class="col-md-2">Remove</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($groups as $index => $group)
                            <tr class="clickable-row" data-href="{{ route('eon.admin.groups.single', $group->id) }}" data-groupid="{{ $group->id }}">
                                <a href="">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $group->name }}</td>
                                    <td>{{ $group->users_roles->count() }}</td>
                                    <td><button type="button" class="remove-group btn btn-danger btn-xs" data-groupid="{{ $group->id }}">Remove</button></td>
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
                        <a href="{{ route('eon.admin.groups') }}" class="list-group-item active">Groups</a>
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

            $('.clickable-row').on('click', '.remove-group', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var group_id = $(this).data('groupid');

                var url = '{{ route('eon.admin.groups.delete') }}';
                url = url.replace('--group--', group_id);

                $('.clickable-row[data-groupid="' + group_id + '"]').hide();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {_token: _token},
                    success: function(res) {
                        console.log('res', res);
                        if(res.hasOwnProperty('success')) {
                            if(res.success) {
                                $('.clickable-row[data-groupid="' + group_id + '"]').remove();
                            } else {
                                $('.clickable-row[data-groupid="' + group_id + '"]').show();
                                alert(res.error_messages);
                            }
                        }
                    },
                    error: function(res) {
                        console.log('res', res);
                        $('.clickable-row[data-groupid="' + group_id + '"]').show();
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