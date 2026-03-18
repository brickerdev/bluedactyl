@extends('layouts.admin')

@section('title')
    Mounts
@endsection

@section('content-header')
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-4xl font-black tracking-tight text-base-content">Mounts</h1>
            <p class="text-base-content/60 mt-1 text-sm">Configure additional storage mount points for server containers.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="breadcrumbs text-sm bg-base-200/50 px-4 py-2 rounded-lg border border-base-300">
                <ul>
                    <li><a href="{{ route('admin.index') }}">Admin</a></li>
                    <li class="font-bold">Mounts</li>
                </ul>
            </div>
            <button class="btn btn-primary shadow-lg shadow-primary/20" onclick="new_mount_modal.showModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="card bg-base-100 border border-base-300 shadow-xl overflow-hidden">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table table-lg">
                    <thead>
                        <tr class="bg-base-200/50 border-b border-base-300">
                            <th class="font-black text-xs uppercase tracking-wider">ID</th>
                            <th class="font-black text-xs uppercase tracking-wider">Mount Name</th>
                            <th class="font-black text-xs uppercase tracking-wider">Source Path</th>
                            <th class="font-black text-xs uppercase tracking-wider">Target Path</th>
                            <th class="font-black text-xs uppercase tracking-wider text-center">Stats</th>
                            <th class="font-black text-xs uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-base-300">
                        @foreach ($mounts as $mount)
                            <tr class="hover:bg-base-200/30 transition-colors">
                                <td><span class="font-mono text-xs opacity-50">#{{ $mount->id }}</span></td>
                                <td>
                                    <a href="{{ route('admin.mounts.view', $mount->id) }}" class="font-bold hover:text-primary transition-colors">
                                        {{ $mount->name }}
                                    </a>
                                </td>
                                <td><code class="text-xs bg-base-300 px-2 py-1 rounded font-mono">{{ $mount->source }}</code></td>
                                <td><code class="text-xs bg-base-300 px-2 py-1 rounded font-mono">{{ $mount->target }}</code></td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="tooltip" data-tip="Eggs">
                                            <div class="badge badge-neutral font-bold">{{ $mount->eggs_count }}</div>
                                        </div>
                                        <div class="tooltip" data-tip="Nodes">
                                            <div class="badge badge-primary badge-soft font-bold">{{ $mount->nodes_count }}</div>
                                        </div>
                                        <div class="tooltip" data-tip="Servers">
                                            <div class="badge badge-secondary badge-soft font-bold">{{ $mount->servers_count }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.mounts.view', $mount->id) }}" class="btn btn-ghost btn-sm btn-square">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        @if ($mounts->isEmpty())
                            <tr>
                                <td colspan="6">
                                    <div class="flex flex-col items-center justify-center py-12 opacity-40">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                        </svg>
                                        <span class="text-sm font-bold uppercase tracking-widest">No mounts configured</span>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Mount Modal --}}
    <dialog id="new_mount_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box max-w-2xl border border-base-300 shadow-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-black text-2xl mb-6">Create New Mount</h3>

            <form action="{{ route('admin.mounts') }}" method="POST">
                {!! csrf_field() !!}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control w-full md:col-span-2">
                        <label for="pName" class="label">
                            <span class="label-text font-black text-xs uppercase tracking-widest opacity-70">Mount Name</span>
                        </label>
                        <input type="text" id="pName" name="name" class="input input-bordered focus:input-primary w-full" placeholder="Extra Storage" required />
                        <label class="label">
                            <span class="label-text-alt text-base-content/50 italic">Unique name to identify this mount.</span>
                        </label>
                    </div>

                    <div class="form-control w-full md:col-span-2">
                        <label for="pDescription" class="label">
                            <span class="label-text font-black text-xs uppercase tracking-widest opacity-70">Description</span>
                        </label>
                        <textarea id="pDescription" name="description" class="textarea textarea-bordered focus:textarea-primary w-full" rows="3" placeholder="Additional storage for game assets..."></textarea>
                    </div>

                    <div class="form-control w-full">
                        <label for="pSource" class="label">
                            <span class="label-text font-black text-xs uppercase tracking-widest opacity-70">Source Path</span>
                        </label>
                        <input type="text" id="pSource" name="source" class="input input-bordered focus:input-primary w-full font-mono text-sm" placeholder="/mnt/storage" required />
                        <label class="label">
                            <span class="label-text-alt text-base-content/50 italic">Path on the host system.</span>
                        </label>
                    </div>

                    <div class="form-control w-full">
                        <label for="pTarget" class="label">
                            <span class="label-text font-black text-xs uppercase tracking-widest opacity-70">Target Path</span>
                        </label>
                        <input type="text" id="pTarget" name="target" class="input input-bordered focus:input-primary w-full font-mono text-sm" placeholder="/home/container/storage" required />
                        <label class="label">
                            <span class="label-text-alt text-base-content/50 italic">Path inside the container.</span>
                        </label>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-black text-xs uppercase tracking-widest opacity-70">Read Only</span>
                        </label>
                        <div class="flex gap-6 mt-2">
                            <label class="label cursor-pointer flex gap-3">
                                <input type="radio" id="pReadOnlyFalse" name="read_only" value="0" class="radio radio-primary border-2" checked>
                                <span class="label-text font-bold">False</span>
                            </label>
                            <label class="label cursor-pointer flex gap-3">
                                <input type="radio" id="pReadOnly" name="read_only" value="1" class="radio radio-primary border-2">
                                <span class="label-text font-bold">True</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-black text-xs uppercase tracking-widest opacity-70">User Mountable</span>
                        </label>
                        <div class="flex gap-6 mt-2">
                            <label class="label cursor-pointer flex gap-3">
                                <input type="radio" id="pUserMountableFalse" name="user_mountable" value="0" class="radio radio-primary border-2" checked>
                                <span class="label-text font-bold">False</span>
                            </label>
                            <label class="label cursor-pointer flex gap-3">
                                <input type="radio" id="pUserMountable" name="user_mountable" value="1" class="radio radio-primary border-2">
                                <span class="label-text font-bold">True</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-action mt-8">
                    <form method="dialog">
                        <button class="btn btn-ghost">Cancel</button>
                    </form>
                    <button type="submit" class="btn btn-primary px-8 shadow-lg shadow-primary/20">Create Mount</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-base-900/40 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>
@endsection
