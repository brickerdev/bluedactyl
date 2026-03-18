@extends('layouts.admin')

@section('title')
    {{ $node->name }}: Servers
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-base-content uppercase">{{ $node->name }}</h1>
            <p class="text-base-content/60 text-sm font-medium">All servers currently assigned to this node.</p>
        </div>
        <div class="text-sm breadcrumbs text-base-content/60 font-medium">
            <ul>
                <li><a href="{{ route('admin.index') }}" class="hover:text-primary transition-colors">Admin</a></li>
                <li><a href="{{ route('admin.nodes') }}" class="hover:text-primary transition-colors">Nodes</a></li>
                <li><a href="{{ route('admin.nodes.view', $node->id) }}" class="hover:text-primary transition-colors">{{ $node->name }}</a></li>
                <li class="text-base-content">Servers</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="mb-8">
        <div class="tabs tabs-box bg-base-200/50 p-1 rounded-xl inline-flex border border-base-300 whitespace-nowrap overflow-x-auto max-w-full">
            <a href="{{ route('admin.nodes.view', $node->id) }}" class="tab !rounded-lg">About</a>
            <a href="{{ route('admin.nodes.view.settings', $node->id) }}" class="tab !rounded-lg">Settings</a>
            <a href="{{ route('admin.nodes.view.configuration', $node->id) }}" class="tab !rounded-lg">Configuration</a>
            <a href="{{ route('admin.nodes.view.allocation', $node->id) }}" class="tab !rounded-lg">Allocation</a>
            <a href="{{ route('admin.nodes.view.servers', $node->id) }}" class="tab tab-active !rounded-lg font-bold">Servers</a>
        </div>
    </div>

    <div class="card bg-base-200/50 shadow-sm backdrop-blur-md border border-base-300">
        <div class="card-body p-0">
            <div class="p-6 border-b border-base-300">
                <h3 class="text-xl font-black tracking-tighter text-base-content uppercase">Process Manager</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="text-base-content/50 uppercase text-[10px] tracking-[0.15em] border-b border-base-300">
                            <th class="pl-6">ID</th>
                            <th>Server Name</th>
                            <th>Owner</th>
                            <th class="pr-6">Service</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($servers as $server)
                            <tr class="hover:bg-base-300/30 transition-colors" data-server="{{ $server->uuid }}">
                                <td class="pl-6"><code class="badge badge-soft badge-ghost font-mono text-[10px]">{{ $server->uuidShort }}</code></td>
                                <td>
                                    <a href="{{ route('admin.servers.view', $server->id) }}" class="link link-primary font-bold tracking-tight uppercase text-xs decoration-2 underline-offset-4">
                                        {{ $server->name }}
                                    </a>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <a href="{{ route('admin.users.view', $server->owner_id) }}" class="link link-hover font-bold text-xs uppercase tracking-tight">
                                            {{ $server->user->username }}
                                        </a>
                                        <span class="text-[10px] text-base-content/40 font-medium">{{ $server->user->email }}</span>
                                    </div>
                                </td>
                                <td class="pr-6">
                                    <div class="flex items-center gap-2">
                                        <span class="badge badge-soft badge-primary font-black uppercase text-[10px] tracking-widest">{{ $server->nest->name }}</span>
                                        <span class="text-base-content/40 text-[10px] font-black tracking-[0.2em] uppercase">{{ $server->egg->name }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if ($servers->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center py-20">
                                    <div class="flex flex-col items-center gap-4 text-base-content/20">
                                        <i class="fa fa-server text-6xl opacity-20"></i>
                                        <p class="font-black tracking-[0.3em] uppercase text-xs">No servers found on this node</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @if ($servers->hasPages())
                <div class="p-6 border-t border-base-300 flex justify-center">
                    <div class="join">
                        {{ $servers->links('vendor.pagination.daisyui') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
