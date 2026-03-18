@extends('layouts.admin')

@section('title')
    Server — {{ $server->name }}: Manage
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-base-content uppercase">{{ $server->name }}</h1>
            <p class="text-base-content/60 text-sm font-medium">Additional actions to control this server.</p>
        </div>
        <div class="text-sm breadcrumbs text-base-content/60 font-medium">
            <ul>
                <li><a href="{{ route('admin.index') }}" class="hover:text-primary transition-colors">Admin</a></li>
                <li><a href="{{ route('admin.servers') }}" class="hover:text-primary transition-colors">Servers</a></li>
                <li><a href="{{ route('admin.servers.view', $server->id) }}"
                        class="hover:text-primary transition-colors">{{ $server->name }}</a></li>
                <li class="text-base-content">Manage</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    @include('admin.servers.partials.navigation')

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        {{-- Reinstall Server --}}
        <div class="card bg-base-200/50 backdrop-blur-md border border-base-300 shadow-sm overflow-hidden">
            <div class="card-body p-6">
                <h3 class="card-title text-error font-bold tracking-tight uppercase text-sm">
                    <i class="fa fa-refresh mr-2"></i> Reinstall Server
                </h3>
                <p class="text-sm text-base-content/70 mt-2">
                    This will reinstall the server with the assigned service scripts. <span
                        class="text-error font-bold">Danger!</span> This could overwrite server data.
                </p>
                <div class="card-actions justify-end mt-6">
                    @if ($server->isInstalled())
                        <form action="{{ route('admin.servers.view.manage.reinstall', $server->id) }}" method="POST">
                            {!! csrf_field() !!}
                            <button type="submit" class="btn btn-error btn-sm font-bold uppercase tracking-wider">Reinstall
                                Server</button>
                        </form>
                    @else
                        <button class="btn btn-error btn-sm btn-disabled font-bold uppercase tracking-wider">Server Must
                            Install Properly</button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Install Status --}}
        <div class="card bg-base-200/50 backdrop-blur-md border border-base-300 shadow-sm overflow-hidden">
            <div class="card-body p-6">
                <h3 class="card-title text-primary font-bold tracking-tight uppercase text-sm">
                    <i class="fa fa-info-circle mr-2"></i> Install Status
                </h3>
                <p class="text-sm text-base-content/70 mt-2">
                    If you need to change the install status from uninstalled to installed, or vice versa, you may do so
                    with the button below.
                </p>
                <div class="card-actions justify-end mt-6">
                    <form action="{{ route('admin.servers.view.manage.toggle', $server->id) }}" method="POST">
                        {!! csrf_field() !!}
                        <button type="submit" class="btn btn-primary btn-sm font-bold uppercase tracking-wider">Toggle
                            Install Status</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Suspension --}}
        @if (!$server->isSuspended())
            <div class="card bg-base-200/50 backdrop-blur-md border border-base-300 shadow-sm overflow-hidden">
                <div class="card-body p-6">
                    <h3 class="card-title text-warning font-bold tracking-tight uppercase text-sm">
                        <i class="fa fa-pause mr-2"></i> Suspend Server
                    </h3>
                    <p class="text-sm text-base-content/70 mt-2">
                        This will suspend the server, stop any running processes, and immediately block the user from being
                        able to access their files.
                    </p>
                    <div class="card-actions justify-end mt-6">
                        <form action="{{ route('admin.servers.view.manage.suspension', $server->id) }}" method="POST">
                            {!! csrf_field() !!}
                            <input type="hidden" name="action" value="suspend" />
                            <button type="submit"
                                class="btn btn-warning btn-sm font-bold uppercase tracking-wider @if (!is_null($server->transfer)) btn-disabled @endif">Suspend
                                Server</button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="card bg-base-200/50 backdrop-blur-md border border-base-300 shadow-sm overflow-hidden">
                <div class="card-body p-6">
                    <h3 class="card-title text-success font-bold tracking-tight uppercase text-sm">
                        <i class="fa fa-play mr-2"></i> Unsuspend Server
                    </h3>
                    <p class="text-sm text-base-content/70 mt-2">
                        This will unsuspend the server and restore normal user access.
                    </p>
                    <div class="card-actions justify-end mt-6">
                        <form action="{{ route('admin.servers.view.manage.suspension', $server->id) }}" method="POST">
                            {!! csrf_field() !!}
                            <input type="hidden" name="action" value="unsuspend" />
                            <button type="submit"
                                class="btn btn-success btn-sm font-bold uppercase tracking-wider">Unsuspend Server</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        {{-- Transfer Server --}}
        @if (is_null($server->transfer))
            <div class="card bg-base-200/50 backdrop-blur-md border border-base-300 shadow-sm overflow-hidden">
                <div class="card-body p-6">
                    <h3 class="card-title text-success font-bold tracking-tight uppercase text-sm">
                        <i class="fa fa-exchange mr-2"></i> Transfer Server
                    </h3>
                    <p class="text-sm text-base-content/70 mt-2">
                        Transfer this server to another node connected to this panel. <span
                            class="text-warning font-bold italic">Warning!</span> This feature is experimental.
                    </p>
                    <div class="card-actions justify-end mt-6">
                        @if ($canTransfer)
                            <button class="btn btn-success btn-sm font-bold uppercase tracking-wider"
                                onclick="transferServerModal.showModal()">Transfer Server</button>
                        @else
                            <button class="btn btn-success btn-sm btn-disabled font-bold uppercase tracking-wider">Transfer
                                Server</button>
                            <p class="text-[10px] text-base-content/40 mt-2 w-full text-right italic">Requires more than one
                                node.</p>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div
                class="card bg-base-200/50 backdrop-blur-md border border-base-300 shadow-sm overflow-hidden border-success/30">
                <div class="card-body p-6">
                    <h3 class="card-title text-success font-bold tracking-tight uppercase text-sm">
                        <i class="fa fa-spinner fa-spin mr-2"></i> Transferring...
                    </h3>
                    <p class="text-sm text-base-content/70 mt-2">
                        This server is currently being transferred to another node.
                        Initiated at <span class="font-mono text-xs">{{ $server->transfer->created_at }}</span>
                    </p>
                    <div class="card-actions justify-end mt-6">
                        <button class="btn btn-success btn-sm btn-disabled font-bold uppercase tracking-wider">Transfer in
                            Progress</button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Transfer Modal --}}
    <dialog id="transferServerModal" class="modal">
        <div class="modal-box bg-base-200 border border-base-300 shadow-2xl max-w-2xl">
            <form action="{{ route('admin.servers.view.manage.transfer', $server->id) }}" method="POST">
                <h3 class="text-2xl font-black tracking-tighter uppercase mb-6">Transfer Server</h3>

                <div class="space-y-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Target
                                Node</span>
                        </label>
                        <select name="node_id" id="pNodeId" class="select select-bordered w-full bg-base-100">
                            @foreach ($locations as $location)
                                <optgroup label="{{ $location->long }} ({{ $location->short }})">
                                    @foreach ($location->nodes as $node)
                                        @if ($node->id != $server->node_id)
                                            <option value="{{ $node->id }}"
                                                @if ($location->id === old('location_id')) selected @endif>
                                                {{ $node->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">The node which this server will be transferred
                                to.</span>
                        </label>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span
                                class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Default
                                Allocation</span>
                        </label>
                        <select name="allocation_id" id="pAllocation"
                            class="select select-bordered w-full bg-base-100"></select>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">The main allocation that will be assigned to
                                this server.</span>
                        </label>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span
                                class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Additional
                                Allocation(s)</span>
                        </label>
                        <select name="allocation_additional[]" id="pAllocationAdditional"
                            class="select select-bordered w-full bg-base-100" multiple></select>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">Additional allocations to assign to this
                                server.</span>
                        </label>
                    </div>
                </div>

                <div class="modal-action mt-8">
                    {!! csrf_field() !!}
                    <button type="button" class="btn btn-ghost font-bold uppercase tracking-wider"
                        onclick="transferServerModal.close()">Cancel</button>
                    <button type="submit" class="btn btn-success font-bold uppercase tracking-wider px-8">Confirm
                        Transfer</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
@endsection

@section('footer-scripts')
    @parent
    {!! Theme::js('vendor/lodash/lodash.js') !!}

    @if ($canTransfer)
        {!! Theme::js('js/admin/server/transfer.js') !!}
    @endif

    <style>
        /* Select2 overrides for daisyUI */
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple {
            @apply bg-base-100 border-base-300 rounded-lg h-12 flex items-center px-2;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            @apply text-base-content leading-tight;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            @apply bg-primary text-primary-content border-none rounded px-2 py-0.5 text-xs font-bold uppercase;
        }

        .select2-dropdown {
            @apply bg-base-100 border-base-300 shadow-xl rounded-lg overflow-hidden;
        }

        .select2-results__option {
            @apply text-base-content px-4 py-2;
        }

        .select2-results__option--highlighted[aria-selected] {
            @apply bg-primary text-primary-content;
        }
    </style>
@endsection
