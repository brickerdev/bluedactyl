@extends('layouts.admin')

@section('title')
    Database Hosts
@endsection

@section('content-header')
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-4xl font-black tracking-tight">Database Hosts</h1>
            <p class="text-base-content/60 mt-1">Manage external MySQL hosts for server databases.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="breadcrumbs text-sm bg-base-200/50 px-4 py-2 rounded-lg border border-base-300">
                <ul>
                    <li><a href="{{ route('admin.index') }}">Admin</a></li>
                    <li class="font-bold">Database Hosts</li>
                </ul>
            </div>
            <button class="btn btn-primary shadow-lg shadow-primary/20" onclick="new_host_modal.showModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 gap-6">
        <div class="card bg-base-100 border border-base-300 shadow-xl overflow-hidden">
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table table-lg">
                        <thead>
                            <tr class="bg-base-200/50 border-b border-base-300">
                                <th class="font-black text-xs uppercase tracking-wider">ID</th>
                                <th class="font-black text-xs uppercase tracking-wider">Host Name</th>
                                <th class="font-black text-xs uppercase tracking-wider">Connection</th>
                                <th class="font-black text-xs uppercase tracking-wider">Username</th>
                                <th class="font-black text-xs uppercase tracking-wider text-center">Databases</th>
                                <th class="font-black text-xs uppercase tracking-wider text-center">Node</th>
                                <th class="font-black text-xs uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-base-300">
                            @foreach ($hosts as $host)
                                <tr class="hover:bg-base-200/30 transition-colors">
                                    <td><span class="font-mono text-xs opacity-50">#{{ $host->id }}</span></td>
                                    <td>
                                        <a href="{{ route('admin.databases.view', $host->id) }}" class="font-bold hover:text-primary transition-colors">
                                            {{ $host->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="flex flex-col">
                                            <code class="text-xs font-bold text-primary">{{ $host->host }}</code>
                                            <span class="text-[10px] opacity-50 uppercase font-black">Port: {{ $host->port }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-ghost font-mono text-xs">{{ $host->username }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="badge badge-neutral font-bold">{{ $host->databases_count }}</div>
                                    </td>
                                    <td class="text-center">
                                        @if (!is_null($host->node))
                                            <a href="{{ route('admin.nodes.view', $host->node->id) }}" class="badge badge-info badge-soft font-bold">
                                                {{ $host->node->name }}
                                            </a>
                                        @else
                                            <span class="badge badge-ghost opacity-50">None</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.databases.view', $host->id) }}" class="btn btn-ghost btn-sm btn-square">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <dialog id="new_host_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box max-w-2xl border border-base-300 shadow-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-black text-2xl mb-2">Create Database Host</h3>
            <p class="text-base-content/60 text-sm mb-6">Configure a new external MySQL host for automated database creation.</p>

            <div id="testResult" class="mb-6 hidden"></div>

            <form action="{{ route('admin.databases') }}" method="POST" id="databaseHostForm">
                {!! csrf_field() !!}

                <div class="grid grid-cols-1 gap-4">
                    <div class="form-control w-full">
                        <label class="label" for="pName">
                            <span class="label-text font-bold text-xs uppercase tracking-widest opacity-70">Host Name</span>
                        </label>
                        <input type="text" name="name" id="pName" class="input input-bordered focus:input-primary w-full" value="{{ old('name') }}" placeholder="Local MySQL" />
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">A short identifier for this host.</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-control w-full md:col-span-2">
                            <label class="label" for="pHost">
                                <span class="label-text font-bold text-xs uppercase tracking-widest opacity-70">Host / IP</span>
                            </label>
                            <input type="text" name="host" id="pHost" class="input input-bordered focus:input-primary w-full font-mono" value="{{ old('host') }}" placeholder="127.0.0.1" />
                        </div>
                        <div class="form-control w-full">
                            <label class="label" for="pPort">
                                <span class="label-text font-bold text-xs uppercase tracking-widest opacity-70">Port</span>
                            </label>
                            <input type="text" name="port" id="pPort" class="input input-bordered focus:input-primary w-full font-mono" value="{{ old('port', '3306') }}" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control w-full">
                            <label class="label" for="pUsername">
                                <span class="label-text font-bold text-xs uppercase tracking-widest opacity-70">Username</span>
                            </label>
                            <input type="text" name="username" id="pUsername" class="input input-bordered focus:input-primary w-full font-mono" value="{{ old('username') }}" />
                        </div>
                        <div class="form-control w-full">
                            <label class="label" for="pPassword">
                                <span class="label-text font-bold text-xs uppercase tracking-widest opacity-70">Password</span>
                            </label>
                            <input type="password" name="password" id="pPassword" class="input input-bordered focus:input-primary w-full font-mono" />
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label class="label" for="pNodeId">
                            <span class="label-text font-bold text-xs uppercase tracking-widest opacity-70">Linked Node</span>
                        </label>
                        <select name="node_id" id="pNodeId" class="select select-bordered focus:select-primary w-full">
                            <option value="">None (Global)</option>
                            @foreach ($locations as $location)
                                <optgroup label="{{ $location->short }}">
                                    @foreach ($location->nodes as $node)
                                        <option value="{{ $node->id }}" {{ old('node_id') == $node->id ? 'selected' : '' }}>{{ $node->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50 text-xs italic">Defaults to this host when adding databases to servers on the selected node.</span>
                        </label>
                    </div>
                </div>

                <div class="alert alert-warning alert-soft mt-6 border-l-4 border-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="text-xs leading-relaxed">
                        The account <strong>must</strong> have <code>WITH GRANT OPTION</code>. 
                        <span class="block mt-1 opacity-70 italic">Do not use the same account as the panel's main database.</span>
                    </div>
                </div>

                <div class="modal-action mt-8">
                    <form method="dialog">
                        <button class="btn btn-ghost">Cancel</button>
                    </form>
                    <button type="button" id="testDatabaseBtn" class="btn btn-info btn-soft">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Test Connection
                    </button>
                    <button type="submit" class="btn btn-primary px-8">Create Host</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-base-900/40 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $(document).ready(function() {
            // Test database connection
            $('#testDatabaseBtn').on('click', function() {
                const button = $(this);
                const originalContent = button.html();
                const resultDiv = $('#testResult');

                // Show loading state
                button.prop('disabled', true).html('<span class="loading loading-spinner loading-xs mr-1"></span> Testing...');
                resultDiv.hide().removeClass('alert alert-error alert-success alert-soft border-l-4 border-error border-success').html('');

                // Get form data
                const formData = {
                    host: $('#pHost').val(),
                    port: $('#pPort').val(),
                    username: $('#pUsername').val(),
                    password: $('#pPassword').val(),
                    _token: '{{ csrf_token() }}'
                };

                // Validate required fields
                if (!formData.host || !formData.port || !formData.username || !formData.password) {
                    resultDiv.html('<div class="flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg><span><strong>Error:</strong> Please fill in all connection fields.</span></div>')
                        .addClass('alert alert-error alert-soft border-l-4 border-error').show();
                    button.prop('disabled', false).html(originalContent);
                    return;
                }

                // Simple AJAX request
                $.ajax({
                    url: '{{ route('admin.databases.test') }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            resultDiv.html('<div class="flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg><span><strong>Success:</strong> ' + response.message + '</span></div>').addClass(
                                'alert alert-success alert-soft border-l-4 border-success').show();
                        } else {
                            resultDiv.html('<div class="flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg><span><strong>Error:</strong> ' + response.message + '</span></div>').addClass(
                                'alert alert-error alert-soft border-l-4 border-error').show();
                        }
                    },
                    error: function(xhr) {
                        let message = 'An unexpected error occurred.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        } else if (xhr.statusText) {
                            message = xhr.statusText;
                        }
                        resultDiv.html('<div class="flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg><span><strong>Error:</strong> ' + message + '</span></div>').addClass(
                            'alert alert-error alert-soft border-l-4 border-error').show();
                    },
                    complete: function() {
                        button.prop('disabled', false).html(originalContent);
                    }
                });
            });

            // Re-open modal if there are old inputs (form was submitted but had errors)
            @if ($errors->any())
                new_host_modal.showModal();
            @endif
        });
    </script>
@endsection
