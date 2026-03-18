@extends('layouts.admin')
@include('partials/admin.settings.nav', ['activeTab' => 'advanced'])

@section('title')
    Advanced Settings
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-4xl font-black tracking-tighter uppercase">Advanced Settings</h1>
            <p class="text-base-content/60 text-sm">Configure advanced system-level settings for your panel.</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.settings') }}">Settings</a></li>
                <li class="text-primary font-bold">Advanced</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    @yield('settings::nav')

    <form action="{{ route('admin.settings.advanced') }}" method="POST">
        <div class="grid grid-cols-1 gap-8">
            {{-- HTTP Connections --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <i class="ri-links-line text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold uppercase tracking-tight">HTTP Connections</h3>
                            <p class="text-xs text-base-content/50 italic">Configure how the panel connects to external
                                services and nodes.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Connection Timeout</span>
                            </label>
                            <input type="number" required
                                class="input input-bordered focus:input-primary w-full transition-all"
                                name="pterodactyl:guzzle:connect_timeout"
                                value="{{ old('pterodactyl:guzzle:connect_timeout', config('pterodactyl.guzzle.connect_timeout')) }}">
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Seconds to wait for a connection to be
                                    opened.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Request Timeout</span>
                            </label>
                            <input type="number" required
                                class="input input-bordered focus:input-primary w-full transition-all"
                                name="pterodactyl:guzzle:timeout"
                                value="{{ old('pterodactyl:guzzle:timeout', config('pterodactyl.guzzle.timeout')) }}">
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Seconds to wait for a request to be
                                    completed.</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Automatic Allocation Creation --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <i class="ri-node-tree text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold uppercase tracking-tight">Automatic Allocation Creation</h3>
                            <p class="text-xs text-base-content/50 italic">Allow users to automatically create new
                                allocations via the frontend.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Status</span>
                            </label>
                            <select class="select select-bordered focus:select-primary w-full transition-all"
                                name="pterodactyl:client_features:allocations:enabled">
                                <option value="false">Disabled</option>
                                <option value="true" @if (old('pterodactyl:client_features:allocations:enabled', config('pterodactyl.client_features.allocations.enabled'))) selected @endif>Enabled</option>
                            </select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Enable or disable this feature
                                    globally.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Starting Port</span>
                            </label>
                            <input type="number" class="input input-bordered focus:input-primary w-full transition-all"
                                name="pterodactyl:client_features:allocations:range_start"
                                value="{{ old('pterodactyl:client_features:allocations:range_start', config('pterodactyl.client_features.allocations.range_start')) }}">
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">The starting port in the range.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Ending Port</span>
                            </label>
                            <input type="number" class="input input-bordered focus:input-primary w-full transition-all"
                                name="pterodactyl:client_features:allocations:range_end"
                                value="{{ old('pterodactyl:client_features:allocations:range_end', config('pterodactyl.client_features.allocations.range_end')) }}">
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">The ending port in the range.</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-4">
                {!! csrf_field() !!}
                <button type="submit" name="_method" value="PATCH"
                    class="btn btn-primary px-12 font-bold uppercase tracking-wider shadow-lg shadow-primary/20">
                    <i class="ri-save-line mr-2"></i>
                    Save Changes
                </button>
            </div>
        </div>
    </form>
@endsection
