@extends('layouts.admin')
@include('partials/admin.settings.nav', ['activeTab' => 'basic'])

@section('title')
    Settings
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter uppercase">Panel Settings</h1>
            <p class="text-base-content/60 text-sm">Configure your Bluedactyl instance to your liking.</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li class="text-primary font-bold">Settings</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    @yield('settings::nav')

    <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
        <div class="card-body p-6">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                    <i class="ri-settings-4-line text-primary text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold uppercase tracking-tight">General Configuration</h3>
                    <p class="text-xs text-base-content/50 italic">Basic identity and security settings for the panel.</p>
                </div>
            </div>

            <form action="{{ route('admin.settings') }}" method="POST">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Company Name</span>
                        </label>
                        <input type="text" class="input input-bordered focus:input-primary w-full transition-all"
                            name="app:name" value="{{ old('app:name', config('app.name')) }}" placeholder="Bluedactyl" />
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">Used throughout the panel and in automated
                                emails.</span>
                        </label>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">2FA Requirement</span>
                        </label>
                        <div class="join w-full border border-base-300 rounded-xl overflow-hidden bg-base-300/30">
                            @php
                                $level = old('pterodactyl:auth:2fa_required', config('pterodactyl.auth.2fa_required'));
                            @endphp
                            <input
                                class="join-item btn btn-sm flex-1 font-bold uppercase tracking-wider {{ $level == 0 ? 'btn-primary' : 'btn-ghost opacity-60' }}"
                                type="radio" name="pterodactyl:auth:2fa_required" value="0" aria-label="None"
                                @if ($level == 0) checked @endif />
                            <input
                                class="join-item btn btn-sm flex-1 font-bold uppercase tracking-wider {{ $level == 1 ? 'btn-primary' : 'btn-ghost opacity-60' }}"
                                type="radio" name="pterodactyl:auth:2fa_required" value="1" aria-label="Admins"
                                @if ($level == 1) checked @endif />
                            <input
                                class="join-item btn btn-sm flex-1 font-bold uppercase tracking-wider {{ $level == 2 ? 'btn-primary' : 'btn-ghost opacity-60' }}"
                                type="radio" name="pterodactyl:auth:2fa_required" value="2" aria-label="All"
                                @if ($level == 2) checked @endif />
                        </div>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">Force users to enable 2-Factor
                                authentication.</span>
                        </label>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Default Language</span>
                        </label>
                        <select name="app:locale" class="select select-bordered focus:select-primary w-full transition-all">
                            @foreach ($languages as $key => $value)
                                <option value="{{ $key }}" @if (config('app.locale') === $key) selected @endif>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">The default locale for the user
                                interface.</span>
                        </label>
                    </div>
                </div>

                <div class="card-actions justify-end mt-12 pt-8 border-t border-base-300">
                    {!! csrf_field() !!}
                    <button type="submit" name="_method" value="PATCH"
                        class="btn btn-primary px-12 font-bold uppercase tracking-wider">
                        <i class="ri-save-line mr-2"></i>
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
