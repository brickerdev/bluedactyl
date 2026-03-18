@extends('layouts.admin')

@section('title')
    Manage User: {{ $user->username }}
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-base-content uppercase">{{ $user->name_first }} {{ $user->name_last }}</h1>
            <p class="text-base-content/60 text-sm font-medium">{{ $user->username }} ({{ $user->email }})</p>
        </div>
        <div class="text-sm breadcrumbs text-base-content/60 font-medium">
            <ul>
                <li><a href="{{ route('admin.index') }}" class="hover:text-primary transition-colors">Admin</a></li>
                <li><a href="{{ route('admin.users') }}" class="hover:text-primary transition-colors">Users</a></li>
                <li class="text-base-content">{{ $user->username }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.users.view', $user->id) }}" method="post">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Identity Section --}}
            <div class="card bg-base-200/50 backdrop-blur-md border border-base-300 shadow-sm">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                            <i class="fa fa-id-card text-xl"></i>
                        </div>
                        <h2 class="text-xl font-black tracking-tight uppercase">Identity</h2>
                    </div>

                    <div class="space-y-4">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Email Address</span>
                            </label>
                            <input type="email" name="email" value="{{ $user->email }}" class="input input-bordered w-full bg-base-100 focus:border-primary transition-all" required>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Username</span>
                            </label>
                            <input type="text" name="username" value="{{ $user->username }}" class="input input-bordered w-full bg-base-100 focus:border-primary transition-all" required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">First Name</span>
                                </label>
                                <input type="text" name="name_first" value="{{ $user->name_first }}" class="input input-bordered w-full bg-base-100 focus:border-primary transition-all" required>
                            </div>
                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Last Name</span>
                                </label>
                                <input type="text" name="name_last" value="{{ $user->name_last }}" class="input input-bordered w-full bg-base-100 focus:border-primary transition-all" required>
                            </div>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Default Language</span>
                            </label>
                            <select name="language" class="select select-bordered w-full bg-base-100">
                                @foreach ($languages as $key => $value)
                                    <option value="{{ $key }}" @if ($user->language === $key) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/40 italic">The default language for this user's panel.</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                {{-- Password Section --}}
                <div class="card bg-base-200/50 backdrop-blur-md border border-base-300 shadow-sm">
                    <div class="card-body p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                                <i class="fa fa-key text-xl"></i>
                            </div>
                            <h2 class="text-xl font-black tracking-tight uppercase">Password</h2>
                        </div>

                        <div id="gen_pass" class="alert alert-soft alert-success mb-4 hidden"></div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">New Password</span>
                                <span class="badge badge-soft badge-ghost font-bold uppercase text-[10px]">Optional</span>
                            </label>
                            <input type="password" id="password" name="password" class="input input-bordered w-full bg-base-100 focus:border-primary transition-all">
                            <label class="label">
                                <span class="label-text-alt text-base-content/40 italic">Leave blank to keep the current password.</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Permissions Section --}}
                <div class="card bg-base-200/50 backdrop-blur-md border border-base-300 shadow-sm">
                    <div class="card-body p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                                <i class="fa fa-shield text-xl"></i>
                            </div>
                            <h2 class="text-xl font-black tracking-tight uppercase">Permissions</h2>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Administrator Status</span>
                            </label>
                            <select name="root_admin" class="select select-bordered w-full bg-base-100">
                                <option value="0">@lang('strings.no')</option>
                                <option value="1" {{ $user->root_admin ? 'selected="selected"' : '' }}>@lang('strings.yes')</option>
                            </select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/40 italic">Gives the user full administrative access to the panel.</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3">
            {!! csrf_field() !!}
            {!! method_field('PATCH') !!}
            <a href="{{ route('admin.users') }}" class="btn btn-ghost font-bold uppercase tracking-wider">Cancel</a>
            <button type="submit" class="btn btn-primary px-12 font-bold uppercase tracking-wider shadow-lg shadow-primary/20">Update User Account</button>
        </div>
    </form>

    {{-- Danger Zone --}}
    <div class="mt-12 pt-12 border-t border-base-300">
        <div class="card bg-error/5 border border-error/20 overflow-hidden">
            <div class="card-body p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-error/10 flex items-center justify-center text-error">
                        <i class="fa fa-exclamation-triangle text-xl"></i>
                    </div>
                    <h2 class="text-xl font-black tracking-tight uppercase text-error">Danger Zone</h2>
                </div>

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="max-w-xl">
                        <h3 class="font-bold text-base-content uppercase text-sm tracking-tight">Delete User Account</h3>
                        <p class="text-sm text-base-content/60 mt-1 italic">
                            There must be no servers associated with this account in order for it to be deleted. This action is permanent.
                        </p>
                    </div>
                    <form action="{{ route('admin.users.view', $user->id) }}" method="POST" class="shrink-0">
                        {!! csrf_field() !!}
                        {!! method_field('DELETE') !!}
                        <button id="delete" type="submit" class="btn btn-error btn-outline font-bold uppercase tracking-wider w-full md:w-auto" {{ $user->servers->count() < 1 ?: 'disabled' }}>
                            Delete User
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
