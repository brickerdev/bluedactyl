@extends('layouts.admin')

@section('title')
    List Users
@endsection

@section('content-header')
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-4xl font-black tracking-tight">Users</h1>
            <p class="text-base-content/60 text-lg">Manage all registered users on the system.</p>
        </div>
        <div class="breadcrumbs text-sm bg-base-200 px-4 py-2 rounded-lg border border-base-300">
            <ul>
                <li><a href="{{ route('admin.index') }}" class="opacity-60">Admin</a></li>
                <li class="font-bold">Users</li>
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
                            <div class="stat-title text-xs font-bold uppercase tracking-widest">Total Users</div>
                            <div class="stat-value text-2xl font-black">{{ $users->total() }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <form action="{{ route('admin.users') }}" method="GET" class="join w-full lg:w-auto">
                        <div class="relative join-item grow">
                            <i class="fa fa-search absolute left-3 top-1/2 -translate-y-1/2 opacity-30"></i>
                            <input type="text" name="filter[email]" class="input input-bordered w-full pl-10"
                                value="{{ request()->input('filter.email') }}" placeholder="Search by email...">
                        </div>
                        <button type="submit" class="btn btn-primary join-item">Search</button>
                    </form>
                    <a href="{{ route('admin.users.new') }}" class="btn btn-success gap-2 shadow-lg shadow-success/20">
                        <i class="fa fa-plus"></i> Create New User
                    </a>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="table table-zebra table-lg">
                    <thead>
                        <tr class="bg-base-200/30 text-base-content/70">
                            <th class="font-black uppercase tracking-widest text-[10px]">ID</th>
                            <th class="font-black uppercase tracking-widest text-[10px]">User Details</th>
                            <th class="font-black uppercase tracking-widest text-[10px]">Username</th>
                            <th class="text-center font-black uppercase tracking-widest text-[10px]">Security</th>
                            <th class="text-center font-black uppercase tracking-widest text-[10px]">Resources</th>
                            <th class="text-right font-black uppercase tracking-widest text-[10px]">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="hover:bg-base-200/50 transition-colors group">
                                <td class="font-mono text-xs opacity-50">{{ $user->id }}</td>
                                <td>
                                    <div class="flex items-center gap-4">
                                        <div class="avatar shadow-md rounded-full ring ring-base-300 ring-offset-base-100 ring-offset-2">
                                            <div class="w-10 rounded-full">
                                                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower($user->email)) }}?s=100" alt="Avatar" />
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.users.view', $user->id) }}" class="font-black hover:link link-primary text-lg tracking-tight">
                                                    {{ $user->email }}
                                                </a>
                                                @if ($user->root_admin)
                                                    <div class="badge badge-warning badge-sm font-black tracking-tighter gap-1">
                                                        <i class="fa fa-shield"></i> ROOT
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-xs opacity-50 font-medium">{{ $user->name_first }} {{ $user->name_last }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="font-bold text-base-content/70">{{ $user->username }}</td>
                                <td class="text-center">
                                    @if ($user->use_totp)
                                        <div class="tooltip" data-tip="2FA Enabled">
                                            <div class="badge badge-success badge-soft p-3">
                                                <i class="fa fa-lock text-success"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="tooltip" data-tip="2FA Disabled">
                                            <div class="badge badge-error badge-soft p-3">
                                                <i class="fa fa-unlock text-error opacity-50"></i>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.servers', ['filter[owner_id]' => $user->id]) }}" 
                                           class="tooltip" data-tip="Owned Servers">
                                            <div class="badge badge-neutral font-bold p-3 gap-2">
                                                <i class="bi bi-server opacity-50"></i> {{ $user->servers_count }}
                                            </div>
                                        </a>
                                        <div class="tooltip" data-tip="Subuser Access">
                                            <div class="badge badge-ghost font-bold p-3 gap-2 border border-base-300">
                                                <i class="bi bi-people opacity-50"></i> {{ $user->subuser_of_count }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.users.view', $user->id) }}" class="btn btn-ghost btn-sm btn-square opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="fa fa-edit text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($users->hasPages())
                <div class="p-6 bg-base-200/30 border-t border-base-300 flex justify-center">
                    <div class="join shadow-lg">
                        {!! $users->appends(['query' => Request::input('query')])->render() !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
