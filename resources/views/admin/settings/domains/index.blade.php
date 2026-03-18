@extends('layouts.admin')
@include('partials/admin.settings.nav', ['activeTab' => 'domains'])

@section('title')
    Domain Management
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-4xl font-black tracking-tighter uppercase">Domain Management</h1>
            <p class="text-base-content/60 text-sm">Configure DNS domains for subdomain management.</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.settings') }}">Settings</a></li>
                <li class="text-primary font-bold">Domains</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    @yield('settings::nav')

    <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md overflow-hidden">
        <div class="card-body p-0">
            <div class="p-6 flex items-center justify-between border-b border-base-300 bg-base-100/30">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <i class="ri-global-line text-primary text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold uppercase tracking-tight">Configured Domains</h3>
                        <p class="text-xs text-base-content/50 italic">Manage your DNS domains and their providers.</p>
                    </div>
                </div>
                <a href="{{ route('admin.settings.domains.create') }}"
                    class="btn btn-primary btn-sm font-bold uppercase tracking-wider">
                    <i class="ri-add-line mr-1"></i>
                    Create New Domain
                </a>
            </div>

            <div class="overflow-x-auto">
                @if (count($domains) > 0)
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-base-300/50 text-base-content/70">
                                <th class="font-bold uppercase tracking-wider text-xs">Domain Name</th>
                                <th class="font-bold uppercase tracking-wider text-xs">DNS Provider</th>
                                <th class="font-bold uppercase tracking-wider text-xs text-center">Status</th>
                                <th class="font-bold uppercase tracking-wider text-xs text-center">Default</th>
                                <th class="font-bold uppercase tracking-wider text-xs text-center">Subdomains</th>
                                <th class="font-bold uppercase tracking-wider text-xs">Created</th>
                                <th class="text-right uppercase tracking-wider text-xs">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($domains as $domain)
                                <tr class="hover:bg-base-300/30 transition-colors">
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <i class="ri-link text-primary/50"></i>
                                            <code class="text-primary font-mono font-bold">{{ $domain->name }}</code>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge badge-primary badge-soft font-bold uppercase tracking-tighter text-[10px]">{{ ucfirst($domain->dns_provider) }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($domain->is_active)
                                            <span
                                                class="badge badge-success badge-soft font-bold uppercase tracking-tighter text-[10px]">Active</span>
                                        @else
                                            <span
                                                class="badge badge-error badge-soft font-bold uppercase tracking-tighter text-[10px]">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($domain->is_default)
                                            <span
                                                class="badge badge-info badge-soft font-bold uppercase tracking-tighter text-[10px]">Default</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge badge-ghost font-mono text-xs">{{ $domain->server_subdomains_count ?? 0 }}</span>
                                    </td>
                                    <td class="text-sm opacity-70">{{ $domain->created_at->diffForHumans() }}</td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.settings.domains.edit', $domain) }}"
                                                class="btn btn-ghost btn-xs text-primary font-bold uppercase tracking-wider hover:bg-primary/10">
                                                <i class="ri-edit-line"></i>
                                                Edit
                                            </a>
                                            @if ($domain->server_subdomains_count == 0)
                                                <form action="{{ route('admin.settings.domains.destroy', $domain) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('Are you sure you want to delete this domain?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-ghost btn-xs text-error font-bold uppercase tracking-wider hover:bg-error/10">
                                                        <i class="ri-delete-bin-line"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="py-20 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-base-300/50 mb-6">
                            <i class="ri-global-line text-4xl opacity-20"></i>
                        </div>
                        <h4 class="text-2xl font-black uppercase tracking-tight opacity-50">No domains configured</h4>
                        <p class="text-base-content/60 mt-2 max-w-md mx-auto italic">
                            Configure DNS domains to enable subdomain management for servers.
                        </p>
                        <a href="{{ route('admin.settings.domains.create') }}"
                            class="btn btn-primary btn-sm mt-8 font-bold uppercase tracking-wider shadow-lg shadow-primary/20">
                            <i class="ri-add-line mr-1"></i>
                            Create Your First Domain
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
