@extends('layouts.admin')

@section('title')
    Server — {{ $server->name }}: Build Details
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter">{{ $server->name }}</h1>
            <p class="text-base-content/60 text-sm">Control allocations and system resources for this server.</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.servers') }}">Servers</a></li>
                <li><a href="{{ route('admin.servers.view', $server->id) }}">{{ $server->name }}</a></li>
                <li class="text-primary">Build Configuration</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    @include('admin.servers.partials.navigation')

    <form action="{{ route('admin.servers.view.build', $server->id) }}" method="POST">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Resource Management -->
            <div class="lg:col-span-5 space-y-8">
                <div class="card bg-base-200/50 shadow-xl backdrop-blur-md border border-base-300">
                    <div class="card-body space-y-6">
                        <h3 class="text-xl font-bold tracking-tight border-b border-base-300 pb-4">Resource Management</h3>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">CPU Limit</span>
                            </label>
                            <div class="join w-full">
                                <input type="text" name="cpu"
                                    class="input input-bordered join-item w-full focus:input-primary transition-all"
                                    value="{{ old('cpu', $server->cpu) }}" />
                                <span class="btn btn-disabled join-item">%</span>
                            </div>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Each core is <code>100%</code>. Set to
                                    <code>0</code> for unlimited.</span>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">CPU Pinning</span>
                            </label>
                            <input type="text" name="threads"
                                class="input input-bordered focus:input-primary transition-all"
                                value="{{ old('threads', $server->threads) }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 italic">Advanced: Specific cores (e.g.
                                    <code>0,1,3</code>). Leave blank for all.</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Allocated Memory</span>
                                </label>
                                <div class="join w-full">
                                    <input type="text" name="memory" data-multiplicator="true"
                                        class="input input-bordered join-item w-full focus:input-primary transition-all"
                                        value="{{ old('memory', $server->memory) }}" />
                                    <span class="btn btn-disabled join-item">MiB</span>
                                </div>
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Overhead Memory</span>
                                </label>
                                <div class="join w-full">
                                    <input type="text" name="overhead_memory" data-multiplicator="true"
                                        class="input input-bordered join-item w-full focus:input-primary transition-all"
                                        value="{{ old('overhead_memory', $server->overhead_memory) }}" />
                                    <span class="btn btn-disabled join-item">MiB</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Allocated Swap</span>
                                </label>
                                <div class="join w-full">
                                    <input type="text" name="swap" data-multiplicator="true"
                                        class="input input-bordered join-item w-full focus:input-primary transition-all"
                                        value="{{ old('swap', $server->swap) }}" />
                                    <span class="btn btn-disabled join-item">MiB</span>
                                </div>
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Disk Space Limit</span>
                                </label>
                                <div class="join w-full">
                                    <input type="text" name="disk"
                                        class="input input-bordered join-item w-full focus:input-primary transition-all"
                                        value="{{ old('disk', $server->disk) }}" />
                                    <span class="btn btn-disabled join-item">MiB</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Block IO Proportion</span>
                            </label>
                            <input type="text" name="io"
                                class="input input-bordered focus:input-primary transition-all"
                                value="{{ old('io', $server->io) }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 italic">Advanced: Value between
                                    <code>10</code> and <code>1000</code>.</span>
                            </label>
                        </div>

                        <div class="divider">Safety & Provisioning</div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">OOM Killer</span>
                            </label>
                            <div class="flex gap-6">
                                <label class="label cursor-pointer flex items-center gap-2">
                                    <input type="radio" value="0" name="oom_disabled" class="radio radio-error"
                                        @if (!$server->oom_disabled) checked @endif>
                                    <span class="label-text">Enabled</span>
                                </label>
                                <label class="label cursor-pointer flex items-center gap-2">
                                    <input type="radio" value="1" name="oom_disabled" class="radio radio-success"
                                        @if ($server->oom_disabled) checked @endif>
                                    <span class="label-text">Disabled</span>
                                </label>
                            </div>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 italic">Enabling OOM killer may cause
                                    processes to exit unexpectedly.</span>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Resource Calculation</span>
                            </label>
                            <div class="flex gap-6">
                                <label class="label cursor-pointer flex items-center gap-2">
                                    <input type="radio" value="0" name="exclude_from_resource_calculation"
                                        class="radio radio-success" @if (!$server->exclude_from_resource_calculation) checked @endif>
                                    <span class="label-text">Included</span>
                                </label>
                                <label class="label cursor-pointer flex items-center gap-2">
                                    <input type="radio" value="1" name="exclude_from_resource_calculation"
                                        class="radio radio-warning" @if ($server->exclude_from_resource_calculation) checked @endif>
                                    <span class="label-text">Excluded</span>
                                </label>
                            </div>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 italic">Excluded servers won't count
                                    towards node capacity during provisioning.</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Limits & Allocation -->
            <div class="lg:col-span-7 space-y-8">
                <div class="card bg-base-200/50 shadow-xl backdrop-blur-md border border-base-300">
                    <div class="card-body space-y-6">
                        <h3 class="text-xl font-bold tracking-tight border-b border-base-300 pb-4">Application Feature
                            Limits</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Database Limit</span>
                                </label>
                                <input type="text" name="database_limit"
                                    class="input input-bordered focus:input-primary transition-all"
                                    value="{{ old('database_limit', $server->database_limit) }}" />
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Allocation Limit</span>
                                </label>
                                <input type="text" name="allocation_limit"
                                    class="input input-bordered focus:input-primary transition-all"
                                    value="{{ old('allocation_limit', $server->allocation_limit) }}" />
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Backup Limit</span>
                                </label>
                                <input type="text" name="backup_limit"
                                    class="input input-bordered focus:input-primary transition-all"
                                    value="{{ old('backup_limit', $server->backup_limit) }}" />
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Backup Storage Limit</span>
                                </label>
                                <div class="join w-full">
                                    <input type="text" name="backup_storage_limit" data-multiplicator="true"
                                        class="input input-bordered join-item w-full focus:input-primary transition-all"
                                        value="{{ old('backup_storage_limit', $server->backup_storage_limit) }}" />
                                    <span class="btn btn-disabled join-item">MiB</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-200/50 shadow-xl backdrop-blur-md border border-base-300">
                    <div class="card-body space-y-6">
                        <h3 class="text-xl font-bold tracking-tight border-b border-base-300 pb-4">Allocation Management
                        </h3>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Game Port</span>
                            </label>
                            <select id="pAllocation" name="allocation_id"
                                class="select select-bordered focus:select-primary">
                                @foreach ($assigned as $assignment)
                                    <option value="{{ $assignment->id }}"
                                        @if ($assignment->id === $server->allocation_id) selected="selected" @endif>
                                        {{ $assignment->alias }}:{{ $assignment->port }}
                                    </option>
                                @endforeach
                            </select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 italic">The default connection address for
                                    this server.</span>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Assign Additional Ports</span>
                            </label>
                            <select name="add_allocations[]" class="select2-daisy w-full" multiple id="pAddAllocations">
                                @foreach ($unassigned as $assignment)
                                    <option value="{{ $assignment->id }}">
                                        {{ $assignment->alias }}:{{ $assignment->port }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-error">Remove Additional Ports</span>
                            </label>
                            <select name="remove_allocations[]" class="select2-daisy w-full" multiple
                                id="pRemoveAllocations">
                                @foreach ($assigned as $assignment)
                                    <option value="{{ $assignment->id }}">
                                        {{ $assignment->alias }}:{{ $assignment->port }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-4 flex justify-end border-t border-base-300">
                            {!! csrf_field() !!}
                            <button type="submit" class="btn btn-primary px-8">Update Build Configuration</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('footer-scripts')
    @parent
    <style>
        .select2-container--default .select2-selection--multiple {
            @apply bg-base-100 border-base-300 rounded-lg min-h-[3rem] p-1;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            @apply border-primary ring-1 ring-primary;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            @apply bg-primary text-primary-content border-none rounded px-2 py-0.5 mt-1;
        }

        .select2-dropdown {
            @apply bg-base-200 border-base-300 shadow-2xl rounded-lg overflow-hidden;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#pAddAllocations').select2({
                placeholder: 'Select ports to add...'
            });
            $('#pRemoveAllocations').select2({
                placeholder: 'Select ports to remove...'
            });
            $('#pAllocation').select2();
        });
    </script>
@endsection
