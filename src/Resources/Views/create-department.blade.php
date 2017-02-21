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
                    <div class="panel-heading">Department</div>
                    <div class="panel-body">
                        <input type="text" id="txt_department" class="form-control" placeholder="Department Name" />
                        <label class="hidden text" id="response"></label>
                    </div>
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
                        <a href="{{ route('eon.admin.departments') }}" class="list-group-item active">Departments</a>
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

            $(document).on('change', '#txt_department', function() {
                var val = $(this).val();
                console.log('val', val);

                $('#response').addClass('text-default').removeClass('hidden').removeClass('text-danger').removeClass('text-success').html('<br />Saving...');

                $.ajax({
                    url: '/admin/departments/create',
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
        });
    </script>
@endsection