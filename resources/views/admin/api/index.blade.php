@extends('layouts.admin')

@section('title')
    Application API
@endsection

@section('content-header')
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-4xl font-black tracking-tight text-base-content">Application API</h1>
            <p class="text-base-content/60 mt-1 text-sm">Manage access credentials for the Panel API.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="breadcrumbs text-sm bg-base-200/50 px-4 py-2 rounded-lg border border-base-300">
                <ul>
                    <li><a href="{{ route('admin.index') }}">Admin</a></li>
                    <li class="font-bold">Application API</li>
                </ul>
            </div>
            <a href="{{ route('admin.api.new') }}" class="btn btn-primary shadow-lg shadow-primary/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New
            </a>
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
                            <th class="font-black text-xs uppercase tracking-wider">API Key</th>
                            <th class="font-black text-xs uppercase tracking-wider">Memo</th>
                            <th class="font-black text-xs uppercase tracking-wider">Last Used</th>
                            <th class="font-black text-xs uppercase tracking-wider">Created</th>
                            <th class="font-black text-xs uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-base-300">
                        @foreach ($keys as $key)
                            <tr class="hover:bg-base-200/30 transition-colors">
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="bg-base-300 px-3 py-1.5 rounded-md border border-base-content/10">
                                            <code
                                                class="text-xs font-mono font-bold text-primary">{{ $key->identifier }}{{ decrypt($key->token) }}</code>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-sm font-medium">{{ $key->memo }}</span>
                                </td>
                                <td>
                                    @if (!is_null($key->last_used_at))
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-bold">{{ $key->last_used_at->format('M j, Y') }}</span>
                                            <span
                                                class="text-[10px] uppercase opacity-50 font-black">{{ $key->last_used_at->format('g:i A') }}</span>
                                        </div>
                                    @else
                                        <span class="badge badge-ghost badge-sm opacity-50">Never</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold">{{ $key->created_at->format('M j, Y') }}</span>
                                        <span
                                            class="text-[10px] uppercase opacity-50 font-black">{{ $key->created_at->format('g:i A') }}</span>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <button class="btn btn-ghost btn-sm btn-square text-error hover:bg-error/10"
                                        data-action="revoke-key" data-attr="{{ $key->identifier }}" title="Revoke Key">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        @if ($keys->isEmpty())
                            <tr>
                                <td colspan="5">
                                    <div class="flex flex-col items-center justify-center py-12 opacity-40">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                        <span class="text-sm font-bold uppercase tracking-widest">No API credentials
                                            found</span>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('[data-action="revoke-key"]').click(function(event) {
                var self = $(this);
                event.preventDefault();

                if (confirm(
                        'Once this API key is revoked any applications currently using it will stop working. Are you sure?'
                        )) {
                    $.ajax({
                        method: 'DELETE',
                        url: '/admin/api/revoke/' + self.data('attr'),
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).done(function() {
                        self.closest('tr').fadeOut(function() {
                            $(this).remove();
                        });
                    }).fail(function(jqXHR) {
                        console.error(jqXHR);
                        alert('An error occurred while attempting to revoke this key.');
                    });
                }
            });
        });
    </script>
@endsection
