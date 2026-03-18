@extends('layouts.admin')

@section('title')
    List Nodes
@endsection

@section('content-header')
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-4xl font-black tracking-tight">Nodes</h1>
            <p class="text-base-content/60 text-lg">Manage all nodes available on the system.</p>
        </div>
        <div class="breadcrumbs text-sm bg-base-200 px-4 py-2 rounded-lg border border-base-300">
            <ul>
                <li><a href="{{ route('admin.index') }}" class="opacity-60">Admin</a></li>
                <li class="font-bold">Nodes</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="card bg-base-100 border border-base-300 shadow-xl overflow-hidden">
        <div class="card-body p-0">
            <!-- Table Header/Actions -->
            <div class="flex flex-col lg:flex-row items-center justify-between p-6 gap-4 bg-base-200/50 border-b border-base-300">
                <div class="flex items-center gap-4">
                    <div class="stats bg-transparent p-0">
                        <div class="stat p-0 pr-4 border-none">
                            <div class="stat-title text-xs font-bold uppercase tracking-widest">Total Nodes</div>
                            <div class="stat-value text-2xl font-black">{{ $nodes->total() }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <form action="{{ route('admin.nodes') }}" method="GET" class="join w-full lg:w-auto">
                        <div class="relative join-item grow">
                            <i class="fa fa-search absolute left-3 top-1/2 -translate-y-1/2 opacity-30"></i>
                            <input type="text" name="filter[name]" class="input input-bordered w-full pl-10"
                                value="{{ request()->input('filter.name') }}" placeholder="Search nodes...">
                        </div>
                        <button type="submit" class="btn btn-primary join-item">Search</button>
                    </form>
                    <a href="{{ route('admin.nodes.new') }}" class="btn btn-success gap-2 shadow-lg shadow-success/20">
                        <i class="fa fa-plus"></i> Create New Node
                    </a>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="table table-zebra table-lg">
                    <thead>
                        <tr class="bg-base-200/30 text-base-content/70">
                            <th class="w-16 text-center font-black uppercase tracking-widest text-[10px]">Status</th>
                            <th class="font-black uppercase tracking-widest text-[10px]">Node Details</th>
                            <th class="font-black uppercase tracking-widest text-[10px]">Location</th>
                            <th class="font-black uppercase tracking-widest text-[10px]">Memory Usage</th>
                            <th class="font-black uppercase tracking-widest text-[10px]">Disk Usage</th>
                            <th class="text-center font-black uppercase tracking-widest text-[10px]">Servers</th>
                            <th class="text-right font-black uppercase tracking-widest text-[10px]">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($nodes as $node)
                            <tr class="hover:bg-base-200/50 transition-colors group">
                                <td class="text-center" data-action="ping"
                                    data-secret="{{ $node->getDecryptedKey() }}"
                                    data-location="{{ $node->scheme }}://{{ $node->fqdn }}:{{ $node->daemonListen }}/api/system">
                                    <div class="flex justify-center">
                                        <div class="p-2 rounded-lg bg-base-300 animate-pulse" id="ping-icon-{{ $node->id }}">
                                            <i class="fa fa-fw fa-refresh fa-spin opacity-30"></i>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.nodes.view', $node->id) }}" class="font-black hover:link link-primary text-lg tracking-tight">
                                                {{ $node->name }}
                                            </a>
                                            @if ($node->maintenance_mode)
                                                <div class="badge badge-warning badge-sm font-black tracking-tighter gap-1">
                                                    <i class="fa fa-wrench"></i> MAINT
                                                </div>
                                            @endif
                                            @if (!$node->public)
                                                <div class="badge badge-ghost badge-sm font-black tracking-tighter gap-1 opacity-50">
                                                    <i class="fa fa-eye-slash"></i> PRIVATE
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-xs font-mono opacity-50">{{ $node->fqdn }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="badge badge-outline font-bold">{{ $node->location->short }}</div>
                                </td>
                                <td>
                                    <div class="flex flex-col gap-1 min-w-[120px]">
                                        <div class="flex justify-between text-xs font-bold">
                                            <span style="color: {{ $node->memory_color }}">{{ $node->memory_percent }}%</span>
                                            <span class="opacity-40">{{ $node->allocated_memory }}MB</span>
                                        </div>
                                        <progress class="progress w-full h-1.5" value="{{ $node->memory_percent }}" max="100" style="--progress-color: {{ $node->memory_color }}"></progress>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col gap-1 min-w-[120px]">
                                        <div class="flex justify-between text-xs font-bold">
                                            <span style="color: {{ $node->disk_color }}">{{ $node->disk_percent }}%</span>
                                            <span class="opacity-40">{{ $node->allocated_disk }}MB</span>
                                        </div>
                                        <progress class="progress w-full h-1.5" value="{{ $node->disk_percent }}" max="100" style="--progress-color: {{ $node->disk_color }}"></progress>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="badge badge-neutral font-black p-3">{{ $node->servers_count }}</div>
                                </td>
                                <td class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.nodes.view', $node->id) }}" class="btn btn-ghost btn-sm btn-square opacity-0 group-hover:opacity-100 transition-opacity">
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
            @if ($nodes->hasPages())
                <div class="p-6 bg-base-200/30 border-t border-base-300 flex justify-center">
                    <div class="join shadow-lg">
                        {!! $nodes->appends(['query' => Request::input('query')])->render() !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        (function pingNodes() {
            $('td[data-action="ping"]').each(function(i, element) {
                const $iconContainer = $(element).find('div > div');
                $.ajax({
                    type: 'GET',
                    url: $(element).data('location'),
                    headers: {
                        'Authorization': 'Bearer ' + $(element).data('secret'),
                    },
                    timeout: 5000
                }).done(function(data) {
                    $iconContainer.removeClass('bg-base-300 animate-pulse').addClass('bg-success/10 text-success tooltip tooltip-right')
                        .attr('data-tip', 'Wings v' + data.version)
                        .find('i').removeClass().addClass('fa fa-fw fa-heartbeat');
                }).fail(function(error) {
                    var errorText = 'Error connecting to node!';
                    try {
                        errorText = error.responseJSON.errors[0].detail || errorText;
                    } catch (ex) {}

                    $iconContainer.removeClass('bg-base-300 animate-pulse').addClass('bg-error/10 text-error tooltip tooltip-right')
                        .attr('data-tip', errorText)
                        .find('i').removeClass().addClass('fa fa-fw fa-heart-o');
                });
            }).promise().done(function() {
                setTimeout(pingNodes, 10000);
            });
        })();
    </script>
@endsection
