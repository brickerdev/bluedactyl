@extends('layouts.admin')

@section('title')
    Nests
@endsection

@section('content-header')
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-4xl font-black tracking-tight text-base-content">Nests</h1>
            <p class="text-base-content/60 mt-1 text-sm">Manage server types and configurations.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="breadcrumbs text-sm bg-base-200/50 px-4 py-2 rounded-lg border border-base-300">
                <ul>
                    <li><a href="{{ route('admin.index') }}">Admin</a></li>
                    <li class="font-bold">Nests</li>
                </ul>
            </div>
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-primary shadow-lg shadow-primary/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Import Egg
                </div>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow-2xl bg-base-100 rounded-box w-52 border border-base-300 mt-2">
                    <li><button onclick="import_egg_modal.showModal()">From File</button></li>
                    <li><button onclick="import_egg_url_modal.showModal()">From URL</button></li>
                </ul>
            </div>
            <a href="{{ route('admin.nests.new') }}" class="btn btn-neutral">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="alert alert-warning alert-soft mb-8 border-l-4 border-warning">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <div class="text-xs leading-relaxed">
            <span class="font-black uppercase tracking-widest block mb-1">Warning: Advanced Feature</span>
            Eggs allow for extreme flexibility. Modifying an egg incorrectly can brick servers. Avoid editing default eggs provided by <code>support@pterodactyl.io</code> unless you are certain.
        </div>
    </div>

    <div class="card bg-base-100 border border-base-300 shadow-xl overflow-hidden">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table table-lg">
                    <thead>
                        <tr class="bg-base-200/50 border-b border-base-300">
                            <th class="font-black text-xs uppercase tracking-wider">ID</th>
                            <th class="font-black text-xs uppercase tracking-wider">Nest Name</th>
                            <th class="font-black text-xs uppercase tracking-wider">Description</th>
                            <th class="font-black text-xs uppercase tracking-wider text-center">Eggs</th>
                            <th class="font-black text-xs uppercase tracking-wider text-center">Servers</th>
                            <th class="font-black text-xs uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-base-300">
                        @foreach($nests as $nest)
                            <tr class="hover:bg-base-200/30 transition-colors">
                                <td><span class="font-mono text-xs opacity-50">#{{ $nest->id }}</span></td>
                                <td>
                                    <div class="flex flex-col">
                                        <a href="{{ route('admin.nests.view', $nest->id) }}" class="font-bold hover:text-primary transition-colors">
                                            {{ $nest->name }}
                                        </a>
                                        <span class="text-[10px] uppercase font-black opacity-40 tracking-tighter">Author: {{ $nest->author }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-sm text-base-content/70 line-clamp-1 max-w-xs" title="{{ $nest->description }}">
                                        {{ $nest->description }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="badge badge-neutral font-bold">{{ $nest->eggs_count }}</div>
                                </td>
                                <td class="text-center">
                                    <div class="badge badge-primary badge-soft font-bold">{{ $nest->servers_count }}</div>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.nests.view', $nest->id) }}" class="btn btn-ghost btn-sm btn-square">
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

    {{-- Import Egg Modal --}}
    <dialog id="import_egg_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box border border-base-300 shadow-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-black text-2xl mb-6">Import an Egg</h3>
            <form action="{{ route('admin.nests.egg.import') }}" enctype="multipart/form-data" method="POST">
                {!! csrf_field() !!}
                <div class="space-y-4">
                    <div class="form-control w-full">
                        <label class="label" for="pImportFile">
                            <span class="label-text font-bold text-xs uppercase tracking-widest opacity-70">Egg File (.json)</span>
                        </label>
                        <input id="pImportFile" type="file" name="import_file" class="file-input file-input-bordered w-full" accept="application/json" required />
                    </div>
                    
                    <div class="form-control w-full">
                        <label class="label" for="pImportToNest">
                            <span class="label-text font-bold text-xs uppercase tracking-widest opacity-70">Associated Nest</span>
                        </label>
                        <select id="pImportToNest" name="import_to_nest" class="select select-bordered w-full">
                            @foreach($nests as $nest)
                               <option value="{{ $nest->id }}">{{ $nest->name }} <{{ $nest->author }}></option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="modal-action mt-8">
                    <form method="dialog">
                        <button class="btn btn-ghost">Cancel</button>
                    </form>
                    <button type="submit" class="btn btn-primary px-8">Import Egg</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-base-900/40 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>

    {{-- Import Egg from URL Modal --}}
    <dialog id="import_egg_url_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box border border-base-300 shadow-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-black text-2xl mb-6">Import Egg from URL</h3>
            <form action="{{ route('admin.nests.egg.import_url') }}" enctype="multipart/form-data" method="POST">
                {!! csrf_field() !!}
                <div class="space-y-4">
                    <div class="form-control w-full">
                        <label class="label" for="pImportFileUrl">
                            <span class="label-text font-bold text-xs uppercase tracking-widest opacity-70">Egg URL</span>
                        </label>
                        <input id="pImportFileUrl" type="url" name="import_file_url" class="input input-bordered w-full" placeholder="https://raw.githubusercontent.com/..." required />
                    </div>
                    
                    <div class="form-control w-full">
                        <label class="label" for="pImportToNestUrl">
                            <span class="label-text font-bold text-xs uppercase tracking-widest opacity-70">Associated Nest</span>
                        </label>
                        <select id="pImportToNestUrl" name="import_to_nest" class="select select-bordered w-full">
                            @foreach($nests as $nest)
                               <option value="{{ $nest->id }}">{{ $nest->name }} <{{ $nest->author }}></option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="modal-action mt-8">
                    <form method="dialog">
                        <button class="btn btn-ghost">Cancel</button>
                    </form>
                    <button type="submit" class="btn btn-primary px-8">Import Egg</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-base-900/40 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>
@endsection
