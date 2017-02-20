@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Permissions</div>
                    <table class="panel-body table table-hover table-striped">
                        <thead>
                        <tr>
                            <th class="col-md-1">#</th>
                            <th class="col-md-7">Permission</th>
                            <th class="col-md-2"># Roles</th>
                            <th class="col-md-2"># Used</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permissions as $index => $permission)
                            <tr class="clickable-row" data-href="{{ route('eon.admin.permissions.single', $permission->id) }}">
                                <a href="">
                                    <th>{{ $index + 1 }}</th>
                                    <th>{{ $permission->name }}</th>
                                    <th>{{ $permission->roles->count() }}</th>
                                    <th>{{ $permission->users->count() }}</th>
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
                        <a href="{{ route('eon.admin.permissions') }}" class="list-group-item active">
                            Permissions
                        </a>
                        <a href="{{ route('eon.admin.roles.users') }}" class="list-group-item">Users' Roles</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script>
        $(document).ready(function($) {
            $(".clickable-row").click(function() {
                window.document.location = $(this).data("href");
            });
        });
    </script>
@endsection