@extends('layouts.admin')

@section('title')
    Server — {{ $server->name }}: Databases
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter">{{ $server->name }}</h1>
            <p class="text-base-content/60 text-sm">Manage server databases.</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.servers') }}">Servers</a></li>
                <li><a href="{{ route('admin.servers.view', $server->id) }}">{{ $server->name }}</a></li>
                <li class="text-primary">Databases</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    @include('admin.servers.partials.navigation')

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-7 space-y-6">
            <div class="alert alert-info alert-soft shadow-inner">
                <i class="fa fa-info-circle"></i>
                <div class="text-sm">
                    Database passwords can be viewed when <a href="/server/{{ $server->uuidShort }}/databases"
                        class="link link-primary font-bold">visiting this server</a> on the front-end.
                </div>
            </div>

            <div class="card bg-base-200/50 shadow-xl backdrop-blur-md border border-base-300">
                <div class="card-body p-0">
                    <div class="p-6 border-b border-base-300">
                        <h3 class="text-xl font-bold tracking-tight">Active Databases</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead>
                                <tr>
                                    <th>Database</th>
                                    <th>Username</th>
                                    <th>Connections From</th>
                                    <th>Host</th>
                                    <th>Max</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($server->databases as $database)
                                    <tr class="hover">
                                        <td class="font-bold">{{ $database->database }}</td>
                                        <td class="font-mono text-xs">{{ $database->username }}</td>
                                        <td><code class="badge badge-ghost">{{ $database->remote }}</code></td>
                                        <td><code
                                                class="text-xs">{{ $database->host->host }}:{{ $database->host->port }}</code>
                                        </td>
                                        <td>
                                            @if ($database->max_connections != null)
                                                <span
                                                    class="badge badge-outline badge-sm">{{ $database->max_connections }}</span>
                                            @else
                                                <span
                                                    class="badge badge-success badge-soft badge-sm font-bold uppercase tracking-widest">Unlimited</span>
                                            @endif
                                        </td>
                                        <td class="text-right flex justify-end gap-2">
                                            <button data-action="reset-password" data-id="{{ $database->id }}"
                                                class="btn btn-ghost btn-xs text-primary tooltip" data-tip="Reset Password">
                                                <i class="fa fa-refresh"></i>
                                            </button>
                                            <button data-action="remove" data-id="{{ $database->id }}"
                                                class="btn btn-ghost btn-xs text-error tooltip" data-tip="Delete Database">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($server->databases->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center py-12 text-base-content/30 italic">
                                            No databases found for this server.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-5">
            <div class="card bg-success/5 border border-success/20 shadow-xl sticky top-8">
                <div class="card-body space-y-6">
                    <h3 class="text-xl font-bold text-success tracking-tight flex items-center gap-2">
                        <i class="fa fa-plus-circle"></i> Create New Database
                    </h3>
                    <form action="{{ route('admin.servers.view.database', $server->id) }}" method="POST"
                        class="space-y-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Database Host</span>
                            </label>
                            <select id="pDatabaseHostId" name="database_host_id"
                                class="select select-bordered focus:select-success">
                                @foreach ($hosts as $host)
                                    <option value="{{ $host->id }}">{{ $host->name }}</option>
                                @endforeach
                            </select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Select the host database server.</span>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Database Name</span>
                            </label>
                            <div class="join w-full">
                                <span class="btn btn-disabled join-item">s{{ $server->id }}_</span>
                                <input id="pDatabaseName" type="text" name="database"
                                    class="input input-bordered join-item w-full focus:input-success transition-all"
                                    placeholder="database" required />
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Connections</span>
                            </label>
                            <input id="pRemote" type="text" name="remote"
                                class="input input-bordered focus:input-success transition-all" value="%" required />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 italic">Standard MySQL notation (e.g.
                                    <code>%</code>).</span>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-base-content/70">Concurrent Connections</span>
                            </label>
                            <input id="pmax_connections" type="text" name="max_connections"
                                class="input input-bordered focus:input-success transition-all" placeholder="Unlimited" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 italic">Max concurrent connections. Leave
                                    empty for unlimited.</span>
                            </label>
                        </div>

                        <div class="pt-4 border-t border-success/10">
                            {!! csrf_field() !!}
                            <p class="text-[10px] text-base-content/40 font-bold uppercase tracking-widest mb-4">Username
                                and password will be randomly generated.</p>
                            <button type="submit" class="btn btn-success btn-block">Create Database</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('#pDatabaseHostId').select2();

            $('[data-action="remove"]').click(function(event) {
                event.preventDefault();
                var self = $(this);
                swal({
                    title: 'Delete Database?',
                    text: 'There is no going back, all data will immediately be removed.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    confirmButtonColor: '#d33',
                    showLoaderOnConfirm: true,
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            method: 'DELETE',
                            url: '/admin/servers/view/{{ $server->id }}/database/' + self
                                .data('id') + '/delete',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                        }).done(function() {
                            self.closest('tr').addClass('bg-error/20').fadeOut(500,
                                function() {
                                    $(this).remove();
                                });
                            swal({
                                type: 'success',
                                title: 'Database Deleted!'
                            });
                        }).fail(function(jqXHR) {
                            swal({
                                type: 'error',
                                title: 'Whoops!',
                                text: jqXHR.responseJSON ? jqXHR.responseJSON
                                    .error : 'An error occurred.'
                            });
                        });
                    }
                });
            });

            $('[data-action="reset-password"]').click(function(e) {
                e.preventDefault();
                var block = $(this);
                block.addClass('loading');
                $.ajax({
                    type: 'PATCH',
                    url: '/admin/servers/view/{{ $server->id }}/database',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    data: {
                        database: $(this).data('id')
                    },
                }).done(function(data) {
                    swal({
                        type: 'success',
                        title: 'Success',
                        text: 'The password for this database has been reset.',
                    });
                }).fail(function(jqXHR) {
                    swal({
                        type: 'error',
                        title: 'Whoops!',
                        text: jqXHR.responseJSON ? jqXHR.responseJSON.error :
                            'An error occurred.'
                    });
                }).always(function() {
                    block.removeClass('loading');
                });
            });
        });
    </script>
@endsection
