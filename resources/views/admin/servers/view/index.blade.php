@extends('layouts.admin')

@section('title')
    Server — {{ $server->name }}
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter">{{ $server->name }}</h1>
            <p class="text-base-content/60 text-sm">{{ str_limit($server->description) }}</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.servers') }}">Servers</a></li>
                <li class="text-primary">{{ $server->name }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    @include('admin.servers.partials.navigation')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <!-- Information Card -->
            <div class="card bg-base-200/50 shadow-xl backdrop-blur-md border border-base-300">
                <div class="card-body p-0">
                    <div class="p-6 border-b border-base-300">
                        <h3 class="text-xl font-bold tracking-tight">Information</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <tbody>
                                <tr class="hover">
                                    <td class="font-semibold text-base-content/70">Internal Identifier</td>
                                    <td><code class="badge badge-ghost">{{ $server->id }}</code></td>
                                </tr>
                                <tr class="hover">
                                    <td class="font-semibold text-base-content/70">External Identifier</td>
                                    <td>
                                        @if (is_null($server->external_id))
                                            <span class="badge badge-outline opacity-50">Not Set</span>
                                        @else
                                            <code class="badge badge-ghost">{{ $server->external_id }}</code>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="hover">
                                    <td class="font-semibold text-base-content/70">UUID / Docker Container ID</td>
                                    <td><code class="text-xs break-all">{{ $server->uuid }}</code></td>
                                </tr>
                                <tr class="hover">
                                    <td class="font-semibold text-base-content/70">Current Egg</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.nests.view', $server->nest_id) }}"
                                                class="link link-primary font-bold">{{ $server->nest->name }}</a>
                                            <span class="text-base-content/30">::</span>
                                            <a href="{{ route('admin.nests.egg.view', $server->egg_id) }}"
                                                class="link link-primary font-bold">{{ $server->egg->name }}</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover">
                                    <td class="font-semibold text-base-content/70">Server Name</td>
                                    <td class="font-bold">{{ $server->name }}</td>
                                </tr>
                                <tr class="hover">
                                    <td class="font-semibold text-base-content/70">CPU Limit</td>
                                    <td>
                                        @if ($server->cpu === 0)
                                            <span class="badge badge-success badge-soft font-bold">Unlimited</span>
                                        @else
                                            <code
                                                class="badge badge-primary badge-soft font-bold">{{ $server->cpu }}%</code>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="hover">
                                    <td class="font-semibold text-base-content/70">CPU Pinning</td>
                                    <td>
                                        @if ($server->threads != null)
                                            <code class="badge badge-ghost">{{ $server->threads }}</code>
                                        @else
                                            <span class="badge badge-outline opacity-50">Not Set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="hover">
                                    <td class="font-semibold text-base-content/70">Memory</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            @if ($server->memory === 0)
                                                <span class="badge badge-success badge-soft font-bold">Unlimited</span>
                                            @else
                                                <code
                                                    class="badge badge-primary badge-soft font-bold">{{ $server->memory }}
                                                    MiB</code>
                                            @endif
                                            <span class="text-base-content/30">/</span>
                                            @if ($server->swap === 0)
                                                <span class="badge badge-outline badge-sm opacity-50 tooltip"
                                                    data-tip="Swap Space">Not Set</span>
                                            @elseif($server->swap === -1)
                                                <span class="badge badge-success badge-soft badge-sm font-bold tooltip"
                                                    data-tip="Swap Space">Unlimited Swap</span>
                                            @else
                                                <code class="badge badge-ghost badge-sm tooltip"
                                                    data-tip="Swap Space">{{ $server->swap }} MiB Swap</code>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover">
                                    <td class="font-semibold text-base-content/70">Disk Space</td>
                                    <td>
                                        @if ($server->disk === 0)
                                            <span class="badge badge-success badge-soft font-bold">Unlimited</span>
                                        @else
                                            <code class="badge badge-primary badge-soft font-bold">{{ $server->disk }}
                                                MiB</code>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="hover">
                                    <td class="font-semibold text-base-content/70">Block IO Weight</td>
                                    <td><code class="badge badge-ghost">{{ $server->io }}</code></td>
                                </tr>
                                <tr class="hover">
                                    <td class="font-semibold text-base-content/70">Default Connection</td>
                                    <td><code
                                            class="text-primary font-bold">{{ $server->allocation->ip }}:{{ $server->allocation->port }}</code>
                                    </td>
                                </tr>
                                <tr class="hover">
                                    <td class="font-semibold text-base-content/70">Connection Alias</td>
                                    <td>
                                        @if ($server->allocation->alias !== $server->allocation->ip)
                                            <code
                                                class="text-success font-bold">{{ $server->allocation->alias }}:{{ $server->allocation->port }}</code>
                                        @else
                                            <span class="badge badge-outline opacity-50">No Alias Assigned</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <!-- Status Cards -->
            @if ($server->isSuspended())
                <div class="alert alert-warning shadow-lg border-warning/20">
                    <i class="fa fa-pause-circle text-2xl"></i>
                    <div>
                        <h3 class="font-black tracking-tighter text-lg uppercase">Suspended</h3>
                        <div class="text-xs opacity-70 font-bold uppercase tracking-widest">This server is currently
                            suspended.</div>
                    </div>
                </div>
            @endif

            @if (!$server->isInstalled())
                <div
                    class="alert {{ !$server->isInstalled() ? 'alert-info' : 'alert-error' }} shadow-lg border-current/20">
                    <i
                        class="fa {{ !$server->isInstalled() ? 'fa-spinner fa-spin' : 'fa-exclamation-triangle' }} text-2xl"></i>
                    <div>
                        <h3 class="font-black tracking-tighter text-lg uppercase">
                            {{ !$server->isInstalled() ? 'Installing' : 'Install Failed' }}</h3>
                        <div class="text-xs opacity-70 font-bold uppercase tracking-widest">
                            {{ !$server->isInstalled() ? 'The server is currently being installed.' : 'The installation process has failed.' }}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Owner Card -->
            <div class="card bg-base-200/50 shadow-xl backdrop-blur-md border border-base-300 overflow-hidden group">
                <div class="card-body p-0">
                    <div class="p-6 flex items-center gap-4">
                        <div class="avatar placeholder">
                            <div
                                class="bg-neutral text-neutral-content rounded-full w-12 group-hover:scale-110 transition-transform">
                                <span class="text-xl font-black">{{ substr($server->user->username, 0, 1) }}</span>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-black tracking-tighter">{{ $server->user->username }}</h3>
                            <p class="text-xs text-base-content/50 font-bold uppercase tracking-widest">Server Owner</p>
                        </div>
                    </div>
                    <div class="px-6 pb-6 space-y-2">
                        <div class="flex items-center gap-2 text-sm opacity-70">
                            <i class="fa fa-envelope w-4"></i>
                            <span>{{ $server->user->email }}</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.view', $server->user->id) }}"
                        class="btn btn-primary btn-block rounded-none border-none">
                        View User Details <i class="fa fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Node Card -->
            <div class="card bg-base-200/50 shadow-xl backdrop-blur-md border border-base-300 overflow-hidden group">
                <div class="card-body p-0">
                    <div class="p-6 flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                            <i class="fa fa-server text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black tracking-tighter">{{ $server->node->name }}</h3>
                            <p class="text-xs text-base-content/50 font-bold uppercase tracking-widest">Server Node</p>
                        </div>
                    </div>
                    <div class="px-6 pb-6 space-y-2">
                        <div class="flex items-center gap-2 text-sm opacity-70">
                            <i class="fa fa-globe w-4"></i>
                            <span>{{ $server->node->fqdn }}</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.nodes.view', $server->node->id) }}"
                        class="btn btn-secondary btn-block rounded-none border-none">
                        View Node Details <i class="fa fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
