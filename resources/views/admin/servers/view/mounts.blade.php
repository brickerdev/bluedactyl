@extends('layouts.admin')

@section('title')
    Server — {{ $server->name }}: Mounts
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-base-content uppercase">{{ $server->name }}</h1>
            <p class="text-base-content/60 text-sm font-medium">Manage server mounts.</p>
        </div>
        <div class="text-sm breadcrumbs text-base-content/60 font-medium">
            <ul>
                <li><a href="{{ route('admin.index') }}" class="hover:text-primary transition-colors">Admin</a></li>
                <li><a href="{{ route('admin.servers') }}" class="hover:text-primary transition-colors">Servers</a></li>
                <li><a href="{{ route('admin.servers.view', $server->id) }}"
                        class="hover:text-primary transition-colors">{{ $server->name }}</a></li>
                <li class="text-base-content">Mounts</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    @include('admin.servers.partials.navigation')

    <div class="mt-6">
        <div class="card bg-base-200/50 backdrop-blur-md border border-base-300 shadow-sm">
            <div class="card-body p-0">
                <div class="flex items-center justify-between p-6 pb-4">
                    <h3 class="text-xl font-bold tracking-tight text-base-content">Available Mounts</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="text-base-content/70 border-b border-base-300">
                                <th class="pl-6">ID</th>
                                <th>Name</th>
                                <th>Source</th>
                                <th>Target</th>
                                <th>Status</th>
                                <th class="text-right pr-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mounts as $mount)
                                <tr class="hover:bg-base-300/30 transition-colors">
                                    <td class="pl-6 font-mono text-xs text-base-content/60">{{ $mount->id }}</td>
                                    <td>
                                        <a href="{{ route('admin.mounts.view', $mount->id) }}"
                                            class="font-bold text-primary hover:underline decoration-2 underline-offset-4">
                                            {{ $mount->name }}
                                        </a>
                                    </td>
                                    <td><code class="badge badge-ghost font-mono text-xs">{{ $mount->source }}</code></td>
                                    <td><code class="badge badge-ghost font-mono text-xs">{{ $mount->target }}</code></td>

                                    <td>
                                        @if (!in_array($mount->id, $server->mounts->pluck('id')->toArray()))
                                            <span
                                                class="badge badge-soft badge-primary font-bold uppercase text-[10px] tracking-wider">Unmounted</span>
                                        @else
                                            <span
                                                class="badge badge-soft badge-success font-bold uppercase text-[10px] tracking-wider">Mounted</span>
                                        @endif
                                    </td>

                                    <td class="text-right pr-6">
                                        @if (!in_array($mount->id, $server->mounts->pluck('id')->toArray()))
                                            <form
                                                action="{{ route('admin.servers.view.mounts.store', ['server' => $server->id]) }}"
                                                method="POST" class="inline">
                                                {!! csrf_field() !!}
                                                <input type="hidden" value="{{ $mount->id }}" name="mount_id" />
                                                <button type="submit"
                                                    class="btn btn-ghost btn-square btn-sm text-success hover:bg-success/10"
                                                    title="Mount">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form
                                                action="{{ route('admin.servers.view.mounts.delete', ['server' => $server->id, 'mount' => $mount->id]) }}"
                                                method="POST" class="inline">
                                                @method('DELETE')
                                                {!! csrf_field() !!}
                                                <button type="submit"
                                                    class="btn btn-ghost btn-square btn-sm text-error hover:bg-error/10"
                                                    title="Unmount">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @if ($mounts->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center py-12 text-base-content/50 italic">
                                        No mounts available for this server.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
