@extends('layouts.admin')

@section('title')
    Database Hosts &rarr; View &rarr; {{ $host->name }}
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter uppercase">{{ $host->name }}</h1>
            <p class="text-base-content/60 text-sm">Viewing associated databases and details for this database host.</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.databases') }}">Database Hosts</a></li>
                <li class="text-primary font-bold">{{ $host->name }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.databases.view', $host->id) }}" method="POST">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Host Details --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <i class="ri-database-2-line text-primary text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold uppercase tracking-tight">Host Details</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="form-control w-full">
                            <label for="pName" class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Name</span>
                            </label>
                            <input type="text" id="pName" name="name" class="input input-bordered w-full focus:input-primary transition-all" value="{{ old('name', $host->name) }}" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="form-control md:col-span-2">
                                <label for="pHost" class="label">
                                    <span class="label-text font-bold uppercase tracking-wide text-xs">Host</span>
                                </label>
                                <input type="text" id="pHost" name="host" class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm" value="{{ old('host', $host->host) }}" />
                            </div>
                            <div class="form-control">
                                <label for="pPort" class="label">
                                    <span class="label-text font-bold uppercase tracking-wide text-xs">Port</span>
                                </label>
                                <input type="text" id="pPort" name="port" class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm" value="{{ old('port', $host->port) }}" />
                            </div>
                        </div>
                        <div class="form-control w-full">
                            <label for="pNodeId" class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Linked Node</span>
                            </label>
                            <select name="node_id" id="pNodeId" class="select select-bordered w-full focus:select-primary transition-all">
                                <option value="">None</option>
                                @foreach($locations as $location)
                                    <optgroup label="{{ $location->short }}">
                                        @foreach($location->nodes as $node)
                                            <option value="{{ $node->id }}" {{ $host->node_id !== $node->id ?: 'selected' }}>{{ $node->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 italic">Defaults to this host when adding a database to a server on the selected node.</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- User Details --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md h-fit">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center">
                            <i class="ri-key-2-line text-secondary text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold uppercase tracking-tight">User Details</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="form-control w-full">
                            <label for="pUsername" class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Username</span>
                            </label>
                            <input type="text" name="username" id="pUsername" class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm" value="{{ old('username', $host->username) }}" />
                        </div>
                        <div class="form-control w-full">
                            <label for="pPassword" class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Password</span>
                            </label>
                            <input type="password" name="password" id="pPassword" class="input input-bordered w-full focus:input-primary transition-all" placeholder="Leave blank to keep current" />
                        </div>

                        <div class="alert alert-soft alert-warning mt-4">
                            <i class="ri-error-warning-line text-xl"></i>
                            <div class="text-xs">
                                <p class="font-bold uppercase tracking-wide">Important</p>
                                <p>Account MUST have <code>WITH GRANT OPTION</code>. Do NOT use the panel's own MySQL account.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-actions justify-between mt-8 pt-6 border-t border-base-300">
                        <button name="_method" value="DELETE" class="btn btn-ghost btn-error btn-sm font-bold uppercase tracking-wider" onclick="return confirm('Are you sure you want to delete this database host?')">
                            <i class="ri-delete-bin-line mr-2"></i>
                            Delete
                        </button>
                        <div class="flex gap-3">
                            {!! csrf_field() !!}
                            <button name="_method" value="PATCH" class="btn btn-primary px-8 font-bold uppercase tracking-wider">
                                <i class="ri-save-line mr-2"></i>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="mt-12">
        <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md overflow-hidden">
            <div class="p-4 border-b border-base-300 bg-base-300/30 flex items-center justify-between">
                <h3 class="font-black uppercase tracking-tighter">Databases on this Host</h3>
                <span class="badge badge-soft badge-primary font-bold">{{ $databases->total() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-base-300/50">
                            <th class="text-[10px] uppercase tracking-widest font-black">Server</th>
                            <th class="text-[10px] uppercase tracking-widest font-black">Database Name</th>
                            <th class="text-[10px] uppercase tracking-widest font-black">Username</th>
                            <th class="text-[10px] uppercase tracking-widest font-black">Connections From</th>
                            <th class="text-[10px] uppercase tracking-widest font-black text-center">Max Connections</th>
                            <th class="text-[10px] uppercase tracking-widest font-black text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($databases as $database)
                            <tr class="hover:bg-base-300/30 transition-colors text-sm">
                                <td>
                                    <a href="{{ route('admin.servers.view', $database->getRelation('server')->id) }}" class="link link-primary font-bold hover:no-underline">
                                        {{ $database->getRelation('server')->name }}
                                    </a>
                                </td>
                                <td><code class="text-xs font-bold">{{ $database->database }}</code></td>
                                <td><code class="text-xs font-bold">{{ $database->username }}</code></td>
                                <td><code class="text-xs font-bold">{{ $database->remote }}</code></td>
                                <td class="text-center">
                                    @if($database->max_connections != null)
                                        <span class="badge badge-soft badge-sm font-mono font-bold">{{ $database->max_connections }}</span>
                                    @else
                                        <span class="badge badge-soft badge-sm font-bold uppercase tracking-wider">Unlimited</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.servers.view.database', $database->getRelation('server')->id) }}" class="btn btn-ghost btn-xs text-primary font-bold uppercase tracking-wider">Manage</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($databases->hasPages())
                <div class="p-4 border-t border-base-300 bg-base-300/30 flex justify-center">
                    {!! $databases->render() !!}
                </div>
            @endif
        </div>
    </div>
@endsection
