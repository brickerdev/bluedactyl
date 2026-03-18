@extends('layouts.admin')

@section('title')
    Node — {{ $node->name }}
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-base-content uppercase">{{ $node->name }}</h1>
            <p class="text-base-content/60 text-sm font-medium">A quick overview of your node.</p>
        </div>
        <div class="text-sm breadcrumbs text-base-content/60 font-medium">
            <ul>
                <li><a href="{{ route('admin.index') }}" class="hover:text-primary transition-colors">Admin</a></li>
                <li><a href="{{ route('admin.nodes') }}" class="hover:text-primary transition-colors">Nodes</a></li>
                <li class="text-base-content">{{ $node->name }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="mb-8">
        <div
            class="tabs tabs-box bg-base-200/50 p-1 rounded-xl inline-flex border border-base-300 whitespace-nowrap overflow-x-auto max-w-full">
            <a href="{{ route('admin.nodes.view', $node->id) }}" class="tab tab-active !rounded-lg font-bold">About</a>
            <a href="{{ route('admin.nodes.view.settings', $node->id) }}" class="tab !rounded-lg">Settings</a>
            <a href="{{ route('admin.nodes.view.configuration', $node->id) }}" class="tab !rounded-lg">Configuration</a>
            <a href="{{ route('admin.nodes.view.allocation', $node->id) }}" class="tab !rounded-lg">Allocation</a>
            <a href="{{ route('admin.nodes.view.servers', $node->id) }}" class="tab !rounded-lg">Servers</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <!-- Information -->
            <div class="card bg-base-200/50 shadow-sm backdrop-blur-md border border-base-300">
                <div class="card-body p-0">
                    <div class="p-6 border-b border-base-300">
                        <h3 class="text-xl font-bold tracking-tight text-base-content uppercase">Information</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <tbody>
                                <tr class="hover:bg-base-300/30 transition-colors">
                                    <td class="font-bold uppercase text-xs tracking-widest text-base-content/50 pl-6">Daemon
                                        Version</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <code data-attr="info-version"
                                                class="badge badge-soft badge-primary font-mono text-xs"><span
                                                    class="loading loading-spinner loading-xs"></span></code>
                                            <span
                                                class="text-[10px] font-bold uppercase text-base-content/40 tracking-wider">(Latest:
                                                <code class="text-primary">{{ $version->getDaemon() }}</code>)</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-base-300/30 transition-colors">
                                    <td class="font-bold uppercase text-xs tracking-widest text-base-content/50 pl-6">System
                                        Information</td>
                                    <td data-attr="info-system">
                                        <span class="loading loading-spinner loading-xs text-primary"></span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-base-300/30 transition-colors">
                                    <td class="font-bold uppercase text-xs tracking-widest text-base-content/50 pl-6">Total
                                        CPU Threads</td>
                                    <td data-attr="info-cpus">
                                        <span class="loading loading-spinner loading-xs text-primary"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if ($node->description)
                <div class="card bg-base-200/50 shadow-sm backdrop-blur-md border border-base-300">
                    <div class="card-body p-6">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-base-content/50 mb-4">Description</h3>
                        <div class="bg-base-300/30 p-4 rounded-xl border border-base-300/50">
                            <pre class="whitespace-pre-wrap font-mono text-sm text-base-content/80 leading-relaxed">{{ $node->description }}</pre>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Delete Node -->
            <div class="card bg-error/5 border border-error/20 shadow-sm overflow-hidden">
                <div class="card-body p-6">
                    <h3 class="text-xl font-black text-error tracking-tighter uppercase flex items-center gap-2">
                        <i class="fa fa-exclamation-triangle"></i> Danger Zone
                    </h3>
                    <p class="text-base-content/70 text-sm mt-2 italic">
                        Deleting a node is a irreversible action and will immediately remove this node from the panel.
                        There must be no servers associated with this node in order to continue.
                    </p>
                    <div class="card-actions justify-end mt-6">
                        <form action="{{ route('admin.nodes.view.delete', $node->id) }}" method="POST">
                            {!! csrf_field() !!}
                            {!! method_field('DELETE') !!}
                            <button type="submit" class="btn btn-error btn-sm font-bold uppercase tracking-wider"
                                {{ $node->servers_count < 1 ?: 'disabled' }}>
                                Yes, Delete This Node
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <!-- At-a-Glance -->
            <div class="card bg-base-200/50 shadow-sm backdrop-blur-md border border-base-300">
                <div class="card-body p-6">
                    <h3 class="text-xl font-bold tracking-tight text-base-content uppercase mb-6">At-a-Glance</h3>

                    @if ($node->maintenance_mode)
                        <div class="alert alert-soft alert-warning mb-6 border-none rounded-xl">
                            <i class="fa fa-wrench"></i>
                            <div>
                                <h3 class="font-bold uppercase text-xs tracking-widest">Maintenance Mode</h3>
                                <div class="text-[10px] opacity-70 italic">This node is currently under maintenance.</div>
                            </div>
                        </div>
                    @endif

                    @php
                        $stats = app('Pterodactyl\Repositories\Eloquent\NodeRepository')->getUsageStatsRaw($node);
                        $memoryPercent = ($stats['memory']['value'] / $stats['memory']['base_limit']) * 100;
                        $diskPercent = ($stats['disk']['value'] / $stats['disk']['base_limit']) * 100;

                        $memoryStatus = $memoryPercent < 50 ? 'success' : ($memoryPercent < 80 ? 'warning' : 'error');
                        $diskStatus = $diskPercent < 50 ? 'success' : ($diskPercent < 80 ? 'warning' : 'error');

                        $allocatedMemory = humanizeSize($stats['memory']['value'] * 1024 * 1024);
                        $totalMemory = humanizeSize($stats['memory']['max'] * 1024 * 1024);
                        $allocatedDisk = humanizeSize($stats['disk']['value'] * 1024 * 1024);
                        $totalDisk = humanizeSize($stats['disk']['max'] * 1024 * 1024);
                    @endphp

                    <div class="space-y-8">
                        <!-- Disk -->
                        <div class="space-y-3">
                            <div class="flex justify-between items-end">
                                <span class="text-[10px] font-black uppercase tracking-widest text-base-content/40">Disk
                                    Space</span>
                                <span class="text-xs font-mono font-bold">{{ $allocatedDisk }} /
                                    {{ $totalDisk }}</span>
                            </div>
                            <progress class="progress progress-{{ $diskStatus }} w-full h-2.5 shadow-inner"
                                value="{{ $diskPercent }}" max="100"></progress>
                            <div
                                class="text-right text-[10px] font-black tracking-widest text-{{ $diskStatus }} uppercase">
                                {{ number_format($diskPercent, 1) }}% Used</div>
                        </div>

                        <!-- Memory -->
                        <div class="space-y-3">
                            <div class="flex justify-between items-end">
                                <span
                                    class="text-[10px] font-black uppercase tracking-widest text-base-content/40">Memory</span>
                                <span class="text-xs font-mono font-bold">{{ $allocatedMemory }} /
                                    {{ $totalMemory }}</span>
                            </div>
                            <progress class="progress progress-{{ $memoryStatus }} w-full h-2.5 shadow-inner"
                                value="{{ $memoryPercent }}" max="100"></progress>
                            <div
                                class="text-right text-[10px] font-black tracking-widest text-{{ $memoryStatus }} uppercase">
                                {{ number_format($memoryPercent, 1) }}% Used</div>
                        </div>

                        <!-- Servers -->
                        <div class="stats shadow-sm bg-base-300/30 w-full border border-base-300/50 rounded-2xl">
                            <div class="stat p-4">
                                <div class="stat-figure text-primary/30">
                                    <i class="fa fa-server text-3xl"></i>
                                </div>
                                <div
                                    class="stat-title font-black uppercase tracking-widest text-[10px] text-base-content/40">
                                    Total Servers</div>
                                <div class="stat-value text-primary tracking-tighter text-3xl">{{ $node->servers_count }}
                                </div>
                                <div class="stat-desc text-[10px] font-bold uppercase tracking-wider mt-1 opacity-50">Hosted
                                    on this node</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        function escapeHtml(str) {
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(str));
            return div.innerHTML;
        }

        (function getInformation() {
            $.ajax({
                method: 'GET',
                url: '/admin/nodes/view/{{ $node->id }}/system-information',
                timeout: 5000,
            }).done(function(data) {
                $('[data-attr="info-version"]').html(escapeHtml(data.version));
                $('[data-attr="info-system"]').html(
                    '<span class="badge badge-soft badge-ghost font-bold uppercase text-[10px] tracking-wider">' +
                    escapeHtml(data.system.type) +
                    '</span> <span class="badge badge-soft badge-ghost font-bold uppercase text-[10px] tracking-wider ml-1">' +
                    escapeHtml(data.system.arch) +
                    '</span> <code class="ml-2 text-primary font-mono text-xs">' + escapeHtml(data.system
                        .release) + '</code>');
                $('[data-attr="info-cpus"]').html('<span class="font-bold text-base-content">' + data.system
                    .cpus +
                    '</span> <span class="text-[10px] font-black uppercase tracking-widest text-base-content/40 ml-2">Logical Cores</span>'
                    );
            }).fail(function(jqXHR) {
                $('[data-attr="info-version"], [data-attr="info-system"], [data-attr="info-cpus"]').html(
                    '<span class="badge badge-soft badge-error font-bold uppercase text-[10px] tracking-wider">Error loading data</span>'
                    );
            }).always(function() {
                setTimeout(getInformation, 10000);
            });
        })();
    </script>
@endsection
