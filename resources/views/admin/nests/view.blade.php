@extends('layouts.admin')

@section('title')
    Nests — {{ $nest->name }}
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-base-content uppercase">{{ $nest->name }}</h1>
            <p class="text-base-content/60 text-sm font-medium">{{ str_limit($nest->description, 100) }}</p>
        </div>
        <div class="text-sm breadcrumbs text-base-content/60 font-medium">
            <ul>
                <li><a href="{{ route('admin.index') }}" class="hover:text-primary transition-colors">Admin</a></li>
                <li><a href="{{ route('admin.nests') }}" class="hover:text-primary transition-colors">Nests</a></li>
                <li class="text-base-content">{{ $nest->name }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <form action="{{ route('admin.nests.view', $nest->id) }}" method="POST" class="contents">
            <div class="card bg-base-200/50 shadow-sm backdrop-blur-md border border-base-300">
                <div class="card-body p-6 space-y-6">
                    <h3
                        class="text-xl font-black tracking-tighter text-base-content uppercase border-b border-base-300 pb-4">
                        Nest Settings</h3>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Nest
                                Name</span>
                        </label>
                        <input type="text" name="name"
                            class="input input-bordered focus:input-primary transition-all bg-base-100"
                            value="{{ $nest->name }}" required />
                        <label class="label">
                            <span class="label-text-alt text-base-content/40 italic text-[10px]">A descriptive category name
                                for this nest.</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span
                                class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Description</span>
                        </label>
                        <textarea name="description" class="textarea textarea-bordered focus:textarea-primary transition-all h-40 bg-base-100">{{ $nest->description }}</textarea>
                    </div>

                    <div class="card-actions justify-between items-center mt-4 pt-4 border-t border-base-300/50">
                        <button id="deleteButton" type="submit" name="_method" value="DELETE"
                            class="btn btn-error btn-outline btn-sm font-bold uppercase tracking-wider group">
                            <i class="fa fa-trash-o mr-2"></i> <span class="group-hover:inline hidden">Delete Nest</span>
                        </button>
                        <div class="flex gap-2">
                            {!! csrf_field() !!}
                            <button type="submit" name="_method" value="PATCH"
                                class="btn btn-primary btn-sm px-8 font-bold uppercase tracking-wider shadow-lg shadow-primary/20">Save
                                Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="card bg-base-200/50 shadow-sm backdrop-blur-md border border-base-300">
            <div class="card-body p-6 space-y-6">
                <h3 class="text-xl font-black tracking-tighter text-base-content uppercase border-b border-base-300 pb-4">
                    Metadata</h3>

                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Nest
                                ID</span>
                        </label>
                        <input type="text" readonly class="input input-bordered bg-base-300/50 font-mono text-xs"
                            value="{{ $nest->id }}" />
                        <label class="label">
                            <span class="label-text-alt text-base-content/40 italic text-[10px]">Unique internal and API
                                identifier.</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span
                                class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Author</span>
                        </label>
                        <input type="text" readonly class="input input-bordered bg-base-300/50 font-mono text-xs"
                            value="{{ $nest->author }}" />
                        <label class="label">
                            <span class="label-text-alt text-base-content/40 italic text-[10px]">The author of this service
                                option.</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span
                                class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">UUID</span>
                        </label>
                        <input type="text" readonly class="input input-bordered bg-base-300/50 font-mono text-xs"
                            value="{{ $nest->uuid }}" />
                        <label class="label">
                            <span class="label-text-alt text-base-content/40 italic text-[10px]">Universal identifier for
                                this nest.</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-base-200/50 shadow-sm backdrop-blur-md border border-base-300 overflow-hidden">
        <div class="card-body p-0">
            <div class="p-6 border-b border-base-300 flex items-center justify-between">
                <h3 class="text-xl font-black tracking-tighter text-base-content uppercase">Nest Eggs</h3>
                <a href="{{ route('admin.nests.egg.new') }}"
                    class="btn btn-success btn-sm font-bold uppercase tracking-wider shadow-lg shadow-success/20">
                    <i class="fa fa-plus mr-1"></i> New Egg
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="text-base-content/50 uppercase text-[10px] tracking-[0.15em] border-b border-base-300">
                            <th class="pl-6">ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th class="text-center">Servers</th>
                            <th class="text-right pr-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($nest->eggs as $egg)
                            <tr class="hover:bg-base-300/30 transition-colors">
                                <td class="pl-6"><code
                                        class="badge badge-soft badge-ghost font-mono text-[10px]">{{ $egg->id }}</code>
                                </td>
                                <td>
                                    <a href="{{ route('admin.nests.egg.view', $egg->id) }}"
                                        class="font-bold text-primary hover:underline decoration-2 underline-offset-4 text-xs uppercase tracking-tight"
                                        title="{{ $egg->author }}">
                                        {{ $egg->name }}
                                    </a>
                                </td>
                                <td>
                                    <span class="text-xs text-base-content/60 italic line-clamp-1 max-w-md"
                                        title="{{ $egg->description }}">
                                        {{ $egg->description }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="badge badge-soft badge-neutral font-black text-[10px]">
                                        {{ $egg->servers->count() }}</div>
                                </td>
                                <td class="text-right pr-6">
                                    <a href="{{ route('admin.nests.egg.export', ['egg' => $egg->id]) }}"
                                        class="btn btn-ghost btn-square btn-xs text-primary hover:bg-primary/10 tooltip"
                                        data-tip="Export Egg">
                                        <i class="fa fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
