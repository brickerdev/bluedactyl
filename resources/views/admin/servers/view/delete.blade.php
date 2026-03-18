@extends('layouts.admin')

@section('title')
    Server — {{ $server->name }}: Delete
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-base-content uppercase">{{ $server->name }}</h1>
            <p class="text-base-content/60 text-sm font-medium">Delete this server from the panel.</p>
        </div>
        <div class="text-sm breadcrumbs text-base-content/60 font-medium">
            <ul>
                <li><a href="{{ route('admin.index') }}" class="hover:text-primary transition-colors">Admin</a></li>
                <li><a href="{{ route('admin.servers') }}" class="hover:text-primary transition-colors">Servers</a></li>
                <li><a href="{{ route('admin.servers.view', $server->id) }}"
                        class="hover:text-primary transition-colors">{{ $server->name }}</a></li>
                <li class="text-base-content">Delete</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    @include('admin.servers.partials.navigation')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        {{-- Safely Delete Server --}}
        <div class="card bg-base-200/50 backdrop-blur-md border border-base-300 shadow-sm overflow-hidden">
            <div class="card-body p-6">
                <h3 class="card-title text-error font-bold tracking-tight uppercase text-sm">
                    <i class="fa fa-shield mr-2"></i> Safely Delete Server
                </h3>
                <div class="space-y-4 mt-4 text-sm text-base-content/70">
                    <p>This action will attempt to delete the server from both the panel and daemon. If either one reports
                        an error the action will be cancelled.</p>
                    <div class="alert alert-soft alert-error border-none py-2 px-4 rounded-lg">
                        <p class="font-bold uppercase text-[10px] tracking-wider">Irreversible Action</p>
                        <p class="text-xs">All server data (including files and users) will be removed from the system.</p>
                    </div>
                </div>
                <div class="card-actions justify-end mt-8">
                    <form id="deleteform" action="{{ route('admin.servers.view.delete', $server->id) }}" method="POST">
                        {!! csrf_field() !!}
                        <button id="deletebtn" class="btn btn-error btn-sm font-bold uppercase tracking-wider">Safely Delete
                            This Server</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Force Delete Server --}}
        <div class="card bg-base-200/50 backdrop-blur-md border border-base-300 shadow-sm overflow-hidden border-error/20">
            <div class="card-body p-6">
                <h3 class="card-title text-error font-bold tracking-tight uppercase text-sm">
                    <i class="fa fa-bolt mr-2"></i> Force Delete Server
                </h3>
                <div class="space-y-4 mt-4 text-sm text-base-content/70">
                    <p>This action will attempt to delete the server from both the panel and daemon. If the daemon does not
                        respond, or reports an error the deletion will continue.</p>
                    <div class="alert alert-soft alert-error border-none py-2 px-4 rounded-lg">
                        <p class="font-bold uppercase text-[10px] tracking-wider">Warning</p>
                        <p class="text-xs">This method may leave dangling files on your daemon if it reports an error.</p>
                    </div>
                </div>
                <div class="card-actions justify-end mt-8">
                    <form id="forcedeleteform" action="{{ route('admin.servers.view.delete', $server->id) }}"
                        method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" name="force_delete" value="1" />
                        <button id="forcedeletebtn"
                            class="btn btn-error btn-outline btn-sm font-bold uppercase tracking-wider">Forcibly Delete This
                            Server</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $('#deletebtn').click(function(event) {
            event.preventDefault();
            swal({
                title: 'Confirm Server Deletion',
                type: 'warning',
                text: 'Are you sure that you want to delete this server? There is no going back, all data will immediately be removed.',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                confirmButtonColor: '#d9534f',
                closeOnConfirm: false
            }, function() {
                $('#deleteform').submit()
            });
        });

        $('#forcedeletebtn').click(function(event) {
            event.preventDefault();
            swal({
                title: 'Confirm Force Deletion',
                type: 'warning',
                text: 'Are you sure that you want to delete this server? There is no going back, all data will immediately be removed.',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                confirmButtonColor: '#d9534f',
                closeOnConfirm: false
            }, function() {
                $('#forcedeleteform').submit()
            });
        });
    </script>
@endsection
