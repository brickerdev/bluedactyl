@extends('layouts.admin')

@section('title')
    Locations &rarr; View &rarr; {{ $location->short }}
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter uppercase">{{ $location->short }}</h1>
            <p class="text-base-content/60 text-sm">{{ str_limit($location->long, 75) }}</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.locations') }}">Locations</a></li>
                <li class="text-primary font-bold">{{ $location->short }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    @php
        $totalMemory = 0;
        $allocatedMemory = 0;
        $totalDisk = 0;
        $allocatedDisk = 0;

        foreach ($location->nodes as $node) {
            $memoryLimit = $node->memory * (1 + $node->memory_overallocate / 100);
            $diskLimit = $node->disk * (1 + $node->disk_overallocate / 100);

            $totalMemory += $memoryLimit;
            $totalDisk += $diskLimit;

            $nodeAllocatedMemory = $node->servers->where('exclude_from_resource_calculation', false)->sum('memory');
            $nodeAllocatedDisk = $node->servers->where('exclude_from_resource_calculation', false)->sum('disk');

            $allocatedMemory += $nodeAllocatedMemory;
            $allocatedDisk += $nodeAllocatedDisk;
        }

        $memoryPercent = $totalMemory > 0 ? ($allocatedMemory / $totalMemory) * 100 : 0;
        $diskPercent = $totalDisk > 0 ? ($allocatedDisk / $totalDisk) * 100 : 0;

        $memoryStatus = $memoryPercent < 50 ? 'success' : ($memoryPercent < 70 ? 'warning' : 'error');
        $diskStatus = $diskPercent < 50 ? 'success' : ($diskPercent < 70 ? 'warning' : 'error');
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Location Details --}}
        <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
            <div class="card-body p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <i class="ri-map-pin-line text-primary text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold uppercase tracking-tight">Location Details</h3>
                </div>

                <form action="{{ route('admin.locations.view', $location->id) }}" method="POST">
                    <div class="space-y-4">
                        <div class="form-control w-full">
                            <label for="pShort" class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Short Code</span>
                            </label>
                            <input type="text" id="pShort" name="short" class="input input-bordered w-full focus:input-primary transition-all" value="{{ $location->short }}" />
                        </div>
                        <div class="form-control w-full">
                            <label for="pLong" class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Description</span>
                            </label>
                            <textarea id="pLong" name="long" class="textarea textarea-bordered w-full h-32 focus:textarea-primary transition-all">{{ $location->long }}</textarea>
                        </div>
                    </div>

                    <div class="card-actions justify-between mt-8 pt-6 border-t border-base-300">
                        <button name="action" value="delete" class="btn btn-ghost btn-error btn-sm font-bold uppercase tracking-wider" onclick="return confirm('Are you sure you want to delete this location?')">
                            <i class="ri-delete-bin-line mr-2"></i>
                            Delete
                        </button>
                        <div class="flex gap-3">
                            {!! csrf_field() !!}
                            {!! method_field('PATCH') !!}
                            <button name="action" value="edit" class="btn btn-primary px-8 font-bold uppercase tracking-wider">
                                <i class="ri-save-line mr-2"></i>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex flex-col gap-6">
            {{-- Resource Allocation --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center">
                            <i class="ri-pie-chart-line text-secondary text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold uppercase tracking-tight">Resource Allocation</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <h4 class="text-xs font-bold uppercase tracking-widest text-base-content/60">Memory</h4>
                                <span class="text-sm font-black text-{{ $memoryStatus }}">{{ round($memoryPercent) }}%</span>
                            </div>
                            <progress class="progress progress-{{ $memoryStatus }} w-full h-3" value="{{ $allocatedMemory }}" max="{{ $totalMemory ?: 1 }}"></progress>
                            <div class="mt-2 text-[10px] uppercase font-bold tracking-wider opacity-50 flex justify-between">
                                <span>Used: {{ humanizeSize($allocatedMemory * 1024 * 1024) }}</span>
                                <span>Total: {{ humanizeSize($totalMemory * 1024 * 1024) }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <h4 class="text-xs font-bold uppercase tracking-widest text-base-content/60">Disk</h4>
                                <span class="text-sm font-black text-{{ $diskStatus }}">{{ round($diskPercent) }}%</span>
                            </div>
                            <progress class="progress progress-{{ $diskStatus }} w-full h-3" value="{{ $allocatedDisk }}" max="{{ $totalDisk ?: 1 }}"></progress>
                            <div class="mt-2 text-[10px] uppercase font-bold tracking-wider opacity-50 flex justify-between">
                                <span>Used: {{ humanizeSize($allocatedDisk * 1024 * 1024) }}</span>
                                <span>Total: {{ humanizeSize($totalDisk * 1024 * 1024) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nodes List --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md overflow-hidden">
                <div class="p-4 border-b border-base-300 bg-base-300/30 flex items-center justify-between">
                    <h3 class="font-black uppercase tracking-tighter">Nodes in this Location</h3>
                    <span class="badge badge-soft badge-primary font-bold">{{ $location->nodes->count() }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-base-300/50">
                                <th class="text-[10px] uppercase tracking-widest font-black">ID</th>
                                <th class="text-[10px] uppercase tracking-widest font-black">Name</th>
                                <th class="text-[10px] uppercase tracking-widest font-black">Memory</th>
                                <th class="text-[10px] uppercase tracking-widest font-black">Disk</th>
                                <th class="text-[10px] uppercase tracking-widest font-black text-center">Servers</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($location->nodes as $node)
                                @php
                                    $nodeMemoryLimit = $node->memory * (1 + $node->memory_overallocate / 100);
                                    $nodeAllocatedMemory = $node->servers
                                        ->where('exclude_from_resource_calculation', false)
                                        ->sum('memory');
                                    $nodeMemoryPercent = $nodeMemoryLimit > 0 ? ($nodeAllocatedMemory / $nodeMemoryLimit) * 100 : 0;

                                    $nodeDiskLimit = $node->disk * (1 + $node->disk_overallocate / 100);
                                    $nodeAllocatedDisk = $node->servers
                                        ->where('exclude_from_resource_calculation', false)
                                        ->sum('disk');
                                    $nodeDiskPercent = $nodeDiskLimit > 0 ? ($nodeAllocatedDisk / $nodeDiskLimit) * 100 : 0;

                                    $nodeMemStatus = $nodeMemoryPercent < 50 ? 'success' : ($nodeMemoryPercent < 70 ? 'warning' : 'error');
                                    $nodeDiskStatus = $nodeDiskPercent < 50 ? 'success' : ($nodeDiskPercent < 70 ? 'warning' : 'error');
                                @endphp
                                <tr class="hover:bg-base-300/30 transition-colors">
                                    <td><code class="text-xs font-bold">{{ $node->id }}</code></td>
                                    <td>
                                        <a href="{{ route('admin.nodes.view', $node->id) }}" class="link link-primary font-bold hover:no-underline">
                                            {{ $node->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <div class="w-10 text-right text-[10px] font-black text-{{ $nodeMemStatus }}">
                                                {{ round($nodeMemoryPercent) }}%
                                            </div>
                                            <progress class="progress progress-{{ $nodeMemStatus }} w-16 h-1.5" value="{{ $nodeMemoryPercent }}" max="100"></progress>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <div class="w-10 text-right text-[10px] font-black text-{{ $nodeDiskStatus }}">
                                                {{ round($nodeDiskPercent) }}%
                                            </div>
                                            <progress class="progress progress-{{ $nodeDiskStatus }} w-16 h-1.5" value="{{ $nodeDiskPercent }}" max="100"></progress>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-soft badge-sm font-black">{{ $node->servers->count() }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
