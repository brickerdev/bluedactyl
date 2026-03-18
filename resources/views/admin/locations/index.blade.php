@extends('layouts.admin')

@section('title')
    Locations
@endsection

@section('content-header')
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-4xl font-black tracking-tight">Locations</h1>
            <p class="text-base-content/60 mt-1">Manage geographical regions for node categorization.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="breadcrumbs text-sm bg-base-200/50 px-4 py-2 rounded-lg border border-base-300">
                <ul>
                    <li><a href="{{ route('admin.index') }}">Admin</a></li>
                    <li class="font-bold">Locations</li>
                </ul>
            </div>
            <button class="btn btn-primary shadow-lg shadow-primary/20" onclick="new_location_modal.showModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
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
                                <th class="font-black text-xs uppercase tracking-wider">Location</th>
                                <th class="font-black text-xs uppercase tracking-wider">Description</th>
                                <th class="font-black text-xs uppercase tracking-wider">Resource Allocation</th>
                                <th class="font-black text-xs uppercase tracking-wider text-center">Stats</th>
                                <th class="font-black text-xs uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-base-300">
                            @foreach ($locations as $location)
                                @php
                                    $memoryColor =
                                        $location->memory_percent < 50
                                            ? 'progress-success'
                                            : ($location->memory_percent < 80
                                                ? 'progress-warning'
                                                : 'progress-error');
                                    $diskColor =
                                        $location->disk_percent < 50
                                            ? 'progress-success'
                                            : ($location->disk_percent < 80
                                                ? 'progress-warning'
                                                : 'progress-error');
                                @endphp
                                <tr class="hover:bg-base-200/30 transition-colors">
                                    <td><span class="font-mono text-xs opacity-50">#{{ $location->id }}</span></td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar placeholder">
                                                <div class="bg-neutral text-neutral-content rounded-lg w-10">
                                                    <span
                                                        class="text-xs font-bold">{{ strtoupper(substr($location->short, 0, 2)) }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.locations.view', $location->id) }}"
                                                    class="font-bold hover:text-primary transition-colors">
                                                    {{ $location->short }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-sm text-base-content/70 line-clamp-1 max-w-xs"
                                            title="{{ $location->long }}">
                                            {{ $location->long }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex flex-col gap-3 w-48">
                                            <div class="space-y-1">
                                                <div
                                                    class="flex justify-between text-[10px] font-bold uppercase opacity-60">
                                                    <span>Memory</span>
                                                    <span>{{ round($location->memory_percent) }}%</span>
                                                </div>
                                                <progress class="progress {{ $memoryColor }} h-1.5"
                                                    value="{{ $location->memory_percent }}" max="100"></progress>
                                            </div>
                                            <div class="space-y-1">
                                                <div
                                                    class="flex justify-between text-[10px] font-bold uppercase opacity-60">
                                                    <span>Disk</span>
                                                    <span>{{ round($location->disk_percent) }}%</span>
                                                </div>
                                                <progress class="progress {{ $diskColor }} h-1.5"
                                                    value="{{ $location->disk_percent }}" max="100"></progress>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <div class="tooltip" data-tip="Nodes">
                                                <div class="badge badge-neutral badge-md font-bold">
                                                    {{ $location->nodes_count }}</div>
                                            </div>
                                            <div class="tooltip" data-tip="Servers">
                                                <div class="badge badge-primary badge-soft badge-md font-bold">
                                                    {{ $location->servers_count }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.locations.view', $location->id) }}"
                                            class="btn btn-ghost btn-sm btn-square">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
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

    <dialog id="new_location_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box border border-base-300 shadow-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-black text-2xl mb-6">Create New Location</h3>
            <form action="{{ route('admin.locations') }}" method="POST">
                {!! csrf_field() !!}
                <div class="space-y-4">
                    <div class="form-control w-full">
                        <label class="label" for="pShortModal">
                            <span class="label-text font-bold">Short Code</span>
                        </label>
                        <input type="text" name="short" id="pShortModal"
                            class="input input-bordered focus:input-primary w-full" placeholder="us.nyc.lvl3" />
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">A short identifier (1-60 chars).</span>
                        </label>
                    </div>
                    <div class="form-control w-full">
                        <label class="label" for="pLongModal">
                            <span class="label-text font-bold">Description</span>
                        </label>
                        <textarea name="long" id="pLongModal" class="textarea textarea-bordered focus:textarea-primary h-24 w-full"
                            placeholder="New York City, Level 3 Datacenter"></textarea>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">A longer description (max 191 chars).</span>
                        </label>
                    </div>
                </div>
                <div class="modal-action mt-8">
                    <form method="dialog">
                        <button class="btn btn-ghost">Cancel</button>
                    </form>
                    <button type="submit" class="btn btn-primary px-8">Create Location</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-base-900/40 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>
@endsection
