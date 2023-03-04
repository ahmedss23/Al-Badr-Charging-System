@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>

            @can('read users')
            <div class="card">
                <div class="card-header">{{ __('Users') }}</div>

                <div class="card-body">

                    <div class="clearfix mb-2">
                        @can('create users')
                            <a href="{{route('admin.users.create')}}" class="float-end btn btn-primary">
                                Create Users
                            </a>
                        @endcan
                    </div>
                    <table id="datatable" class="table datatable">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Addres</th>
                                <th style="min-width:30%">Operations</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection

@section('js')
    @can('read users')
    <script src="{{asset('js/dataTables.bundle.js')}}"></script>
    <script>
        const usersDT = $('#datatable').DataTable({
            lengthMenu: [[10, 25, 50], [10, 25, 50]],
            processing: true,
            serverSide: true,
            response:true,
            columnDefs: [{ targets: [-1, -3], className: 'dt-body-right' },],
            ajax: {
                url: "{{route('admin.users.index')}}",
                type: 'GET',
                delay: 300
            },
            columns: [
                    {data: 'full_name', orderable: false},
                    {data: "email"},
                    {data: "address"},
                    {data: "operations", searchable: false, orderable: false},
                ],
            language: {!!json_encode(__('datatable'), JSON_UNESCAPED_UNICODE) !!},
            delay: 250,
            "aaSorting": [],
        });

        $(document).on('click', '.delete-user-btn', function(e){
            e.preventDefault();
            $.ajax({
                url: this.href,
                method: 'DELETE',
                success: (res)=>{
                    if(res.msg == 'success')
                        usersDT.ajax.reload();
                }
            });
        })

        $(document).on('click', '.active-user-btn', function(e){
            e.preventDefault();
            $.ajax({
                url: this.href,
                method: 'GET',
                success: (res)=>{
                    if(res.msg == 'success')
                        usersDT.ajax.reload();
                }
            });
        })
    </script>
    @endcan
@endsection
