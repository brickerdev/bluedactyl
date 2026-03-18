@extends('layouts.admin')

@section('title')
    List Servers
@endsection

@section('content-header')
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-4xl font-black tracking-tight">Servers</h1>
            <p class="text-base-content/60 text-lg">Manage all servers available on the system.</p>
        </div>
        <div class="breadcrumbs text-sm bg-base-200 px-4 py-2 rounded-lg border border-base-300">
            <ul>
                <li><a href="{{ route('admin.index') }}" class="opacity-60">Admin</a></li>
                <li class="font-bold">Servers</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="card bg-base-100 border border-base-300 shadow-xl overflow-hidden">
        <div class="card-body p-0">
            <!-- Table Header/Actions -->
            <div
                class="flex flex-col lg:flex-row items-center justify-between p-6 gap-4 bg-base-200/50 border-b border-base-300">
                <div class="flex items-center gap-4">
                    <div class="stats bg-transparent p-0">
                        <div class="stat p-0 pr-4 border-none">
                            <div class="stat-title text-xs font-bold uppercase tracking-widest">Total Servers</div>
                            <div class="stat-value text-2xl font-black">{{ $servers->total() }}</div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <form action="{{ route('admin.servers') }}" method="GET" class="join w-full lg:w-auto">
                        <div class="relative join-item grow">
                            <i class="fa fa-search absolute left-3 top-1/2 -translate-y-1/2 opacity-30"></i>
                            <input type="text" name="filter[*]" class="input input-bordered w-full pl-10"
                                value="{{ request()->input()['filter']['*'] ?? '' }}" placeholder="Search servers...">
                        </div>
                        <button type="submit" class="btn btn-primary join-item">Search</button>
                    </form>
                    <a href="{{ route('admin.servers.new') }}" class="btn btn-success gap-2 shadow-lg shadow-success/20">
                        <i class="fa fa-plus"></i> Create New Server
                    </a>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="table table-zebra table-lg">
                    <thead>
                        <tr class="bg-base-200/30 text-base-content/70">
                            <th class="font-black uppercase tracking-widest text-[10px]">Server Details</th>
                            <th class="font-black uppercase tracking-widest text-[10px]">Owner</th>
                            <th class="font-black uppercase tracking-widest text-[10px]">Node</th>
                            <th class="font-black uppercase tracking-widest text-[10px]">Connection</th>
                            <th class="text-center font-black uppercase tracking-widest text-[10px]">Status</th>
                            <th class="text-right font-black uppercase tracking-widest text-[10px]">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($servers as $server)
                            <tr class="hover:bg-base-200/50 transition-colors group">
                                <td>
                                    <div class="flex flex-col">
                                        <a href="{{ route('admin.servers.view', $server->id) }}"
                                            class="font-black hover:link link-primary text-lg tracking-tight">
                                            {{ $server->name }}
                                        </a>
                                        <div class="flex items-center gap-2 mt-1">
                                            <code class="text-[10px] opacity-40 font-mono">{{ $server->uuidShort }}</code>
                                            @if ($server->exclude_from_resource_calculation)
                                                <div
                                                    class="badge badge-ghost badge-xs opacity-50 font-bold tracking-tighter">
                                                    EXCLUDED</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar">
                                            <div
                                                class="w-8 rounded-full ring ring-base-300 ring-offset-base-100 ring-offset-1">
                                                <img
                                                    src="https://www.gravatar.com/avatar/{{ md5(strtolower($server->user->email)) }}?s=100" />
                                            </div>
                                        </div>
                                        <div class="flex flex-col">
                                            <a href="{{ route('admin.users.view', $server->user->id) }}"
                                                class="font-bold hover:link link-primary text-sm">
                                                {{ $server->user->username }}
                                            </a>
                                            <span class="text-[10px] opacity-50">{{ $server->user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.nodes.view', $server->node->id) }}"
                                        class="badge badge-neutral font-bold p-3">
                                        {{ $server->node->name }}
                                    </a>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <code
                                            class="text-xs font-bold text-base-content/70">{{ $server->allocation->alias }}</code>
                                        <code
                                            class="text-[10px] opacity-40 font-mono">{{ $server->allocation->ip }}:{{ $server->allocation->port }}</code>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if ($server->isSuspended())
                                        <div
                                            class="badge badge-error font-black tracking-tighter p-3 gap-2 shadow-lg shadow-error/10">
                                            <div class="w-2 h-2 rounded-full bg-white animate-pulse"></div> SUSPENDED
                                        </div>
                                    @elseif(!$server->isInstalled())
                                        <div
                                            class="badge badge-warning font-black tracking-tighter p-3 gap-2 shadow-lg shadow-warning/10">
                                            <span class="loading loading-spinner loading-xs"></span> INSTALLING
                                        </div>
                                    @else
                                        <div
                                            class="badge badge-success font-black tracking-tighter p-3 gap-2 shadow-lg shadow-success/10">
                                            <div class="w-2 h-2 rounded-full bg-white"></div> ACTIVE
                                        </div>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="/server/{{ $server->uuidShort }}" target="_blank"
                                            class="btn btn-ghost btn-sm btn-square opacity-0 group-hover:opacity-100 transition-opacity tooltip"
                                            data-tip="Manage Server">
                                            <i class="fa fa-wrench text-lg"></i>
                                        </a>
                                        <a href="{{ route('admin.servers.view', $server->id) }}"
                                            class="btn btn-ghost btn-sm btn-square opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class="fa fa-edit text-lg"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($servers->hasPages())
                <div class="p-6 bg-base-200/30 border-t border-base-300 flex justify-center">
                    <div class="join shadow-lg">
                        {!! $servers->appends(['filter' => Request::input('filter')])->render() !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
