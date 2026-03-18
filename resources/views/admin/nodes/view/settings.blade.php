@extends('layouts.admin')

@section('title')
    {{ $node->name }}: Settings
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-base-content uppercase">{{ $node->name }}</h1>
            <p class="text-base-content/60 text-sm font-medium">Configure your node settings.</p>
        </div>
        <div class="text-sm breadcrumbs text-base-content/60 font-medium">
            <ul>
                <li><a href="{{ route('admin.index') }}" class="hover:text-primary transition-colors">Admin</a></li>
                <li><a href="{{ route('admin.nodes') }}" class="hover:text-primary transition-colors">Nodes</a></li>
                <li><a href="{{ route('admin.nodes.view', $node->id) }}"
                        class="hover:text-primary transition-colors">{{ $node->name }}</a></li>
                <li class="text-base-content">Settings</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="mb-8">
        <div
            class="tabs tabs-box bg-base-200/50 p-1 rounded-xl inline-flex border border-base-300 whitespace-nowrap overflow-x-auto max-w-full">
            <a href="{{ route('admin.nodes.view', $node->id) }}" class="tab !rounded-lg">About</a>
            <a href="{{ route('admin.nodes.view.settings', $node->id) }}"
                class="tab tab-active !rounded-lg font-bold">Settings</a>
            <a href="{{ route('admin.nodes.view.configuration', $node->id) }}" class="tab !rounded-lg">Configuration</a>
            <a href="{{ route('admin.nodes.view.allocation', $node->id) }}" class="tab !rounded-lg">Allocation</a>
            <a href="{{ route('admin.nodes.view.servers', $node->id) }}" class="tab !rounded-lg">Servers</a>
        </div>
    </div>

    <form action="{{ route('admin.nodes.view.settings', $node->id) }}" method="POST">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Settings Card -->
            <div class="card bg-base-200/50 shadow-sm backdrop-blur-md border border-base-300">
                <div class="card-body p-6 space-y-6">
                    <h3
                        class="text-xl font-black tracking-tighter text-base-content uppercase border-b border-base-300 pb-4">
                        Settings</h3>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Node
                                Name</span>
                        </label>
                        <input type="text" autocomplete="off" name="name"
                            class="input input-bordered focus:input-primary transition-all bg-base-100"
                            value="{{ old('name', $node->name) }}" required />
                        <label class="label">
                            <span class="label-text-alt text-base-content/40 italic">Character limits:
                                <code>a-zA-Z0-9_.-</code> and <code>[Space]</code>.</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span
                                class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Description</span>
                        </label>
                        <textarea name="description" id="description" rows="4"
                            class="textarea textarea-bordered focus:textarea-primary transition-all h-24 bg-base-100">{{ $node->description }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span
                                    class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Location</span>
                            </label>
                            <select name="location_id" class="select select-bordered focus:select-primary bg-base-100">
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ old('location_id', $node->location_id) === $location->id ? 'selected' : '' }}>
                                        {{ $location->long }} ({{ $location->short }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span
                                    class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Daemon
                                    Type</span>
                            </label>
                            <select name="daemonType" id="pDaemonType"
                                class="select select-bordered focus:select-primary bg-base-100">
                                @foreach ($daemonTypes as $daemon)
                                    <option value="{{ $daemon }}"
                                        {{ $daemon == old('daemon_type', $node->daemonType) ? 'selected' : '' }}>
                                        {{ $daemon }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-4">
                                <span
                                    class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Auto
                                    Allocation</span>
                                <div class="tooltip" data-tip="Allow automatic allocation to this Node?">
                                    <i class="fa fa-question-circle opacity-30"></i>
                                </div>
                            </label>
                            <div class="flex gap-4">
                                <label class="label cursor-pointer flex items-center gap-2">
                                    <input type="radio" name="public" value="1" class="radio radio-primary radio-sm"
                                        {{ old('public', $node->public) ? 'checked' : '' }} id="public_1">
                                    <span class="label-text text-xs font-bold uppercase">Yes</span>
                                </label>
                                <label class="label cursor-pointer flex items-center gap-2">
                                    <input type="radio" name="public" value="0" class="radio radio-primary radio-sm"
                                        {{ old('public', $node->public) ? '' : 'checked' }} id="public_0">
                                    <span class="label-text text-xs font-bold uppercase">No</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-4">
                                <span
                                    class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Trust
                                    Alias</span>
                                <div class="tooltip"
                                    data-tip="Allow Ip Aliases to be used instead of allocation ip for domain management">
                                    <i class="fa fa-question-circle opacity-30"></i>
                                </div>
                            </label>
                            <div class="flex gap-4">
                                <label class="label cursor-pointer flex items-center gap-2">
                                    <input type="radio" name="trust_alias" value="1"
                                        class="radio radio-primary radio-sm"
                                        {{ old('trustalias', $node->trust_alias) ? 'checked' : '' }} id="trust_alias_1">
                                    <span class="label-text text-xs font-bold uppercase">Yes</span>
                                </label>
                                <label class="label cursor-pointer flex items-center gap-2">
                                    <input type="radio" name="trust_alias" value="0"
                                        class="radio radio-primary radio-sm"
                                        {{ old('trustalias', $node->trust_alias) ? '' : 'checked' }} id="trust_alias_0">
                                    <span class="label-text text-xs font-bold uppercase">No</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span
                                class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Public
                                FQDN</span>
                        </label>
                        <input type="text" autocomplete="off" name="fqdn"
                            class="input input-bordered focus:input-primary transition-all bg-base-100"
                            value="{{ old('fqdn', $node->fqdn) }}" />
                        <label class="label">
                            <span class="label-text-alt text-base-content/40 italic">Domain name browsers use to connect to
                                {{ $node->daemonType }}.</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span
                                class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Internal
                                FQDN <span class="opacity-50 font-normal">(Optional)</span></span>
                        </label>
                        <input type="text" autocomplete="off" name="internal_fqdn"
                            class="input input-bordered focus:input-primary transition-all bg-base-100"
                            value="{{ old('internal_fqdn', $node->internal_fqdn) }}" />
                    </div>

                    <div class="divider uppercase text-[10px] font-black tracking-[0.2em] opacity-30">Connection & Status
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">SSL</span></label>
                            <div class="flex flex-col gap-2">
                                <label class="label cursor-pointer flex items-center gap-2 justify-start">
                                    <input type="radio" id="pSSLTrue" value="https" name="scheme"
                                        class="radio radio-success radio-sm"
                                        {{ old('scheme', $node->scheme) === 'https' ? 'checked' : '' }}>
                                    <span class="label-text text-xs font-bold uppercase">HTTPS</span>
                                </label>
                                <label class="label cursor-pointer flex items-center gap-2 justify-start">
                                    <input type="radio" id="pSSLFalse" value="http" name="scheme"
                                        class="radio radio-error radio-sm"
                                        {{ old('scheme', $node->scheme) !== 'https' ? 'checked' : '' }}>
                                    <span class="label-text text-xs font-bold uppercase">HTTP</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Proxy</span></label>
                            <div class="flex flex-col gap-2">
                                <label class="label cursor-pointer flex items-center gap-2 justify-start">
                                    <input type="radio" id="pProxyFalse" value="0" name="behind_proxy"
                                        class="radio radio-primary radio-sm"
                                        {{ old('behind_proxy', $node->behind_proxy) == false ? 'checked' : '' }}>
                                    <span class="label-text text-xs font-bold uppercase">None</span>
                                </label>
                                <label class="label cursor-pointer flex items-center gap-2 justify-start">
                                    <input type="radio" id="pProxyTrue" value="1" name="behind_proxy"
                                        class="radio radio-info radio-sm"
                                        {{ old('behind_proxy', $node->behind_proxy) == true ? 'checked' : '' }}>
                                    <span class="label-text text-xs font-bold uppercase">Behind Proxy</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Maintenance</span></label>
                            <div class="flex flex-col gap-2">
                                <label class="label cursor-pointer flex items-center gap-2 justify-start">
                                    <input type="radio" id="pMaintenanceFalse" value="0" name="maintenance_mode"
                                        class="radio radio-success radio-sm"
                                        {{ old('maintenance_mode', $node->maintenance_mode) == false ? 'checked' : '' }}>
                                    <span class="label-text text-xs font-bold uppercase">Disabled</span>
                                </label>
                                <label class="label cursor-pointer flex items-center gap-2 justify-start">
                                    <input type="radio" id="pMaintenanceTrue" value="1" name="maintenance_mode"
                                        class="radio radio-warning radio-sm"
                                        {{ old('maintenance_mode', $node->maintenance_mode) == true ? 'checked' : '' }}>
                                    <span class="label-text text-xs font-bold uppercase">Enabled</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Allocation Limits -->
                <div class="card bg-base-200/50 shadow-sm backdrop-blur-md border border-base-300">
                    <div class="card-body p-6 space-y-6">
                        <h3
                            class="text-xl font-black tracking-tighter text-base-content uppercase border-b border-base-300 pb-4">
                            Allocation Limits</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span
                                        class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Total
                                        Memory</span>
                                </label>
                                <div class="join w-full">
                                    <input type="text" name="memory"
                                        class="input input-bordered join-item w-full focus:input-primary transition-all bg-base-100"
                                        data-multiplicator="true" value="{{ old('memory', $node->memory) }}" />
                                    <span class="btn btn-disabled join-item bg-base-300 text-xs font-bold">MiB</span>
                                </div>
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span
                                        class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Overallocate</span>
                                </label>
                                <div class="join w-full">
                                    <input type="text" name="memory_overallocate"
                                        class="input input-bordered join-item w-full focus:input-primary transition-all bg-base-100"
                                        value="{{ old('memory_overallocate', $node->memory_overallocate) }}" />
                                    <span class="btn btn-disabled join-item bg-base-300 text-xs font-bold">%</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span
                                        class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Disk
                                        Space</span>
                                </label>
                                <div class="join w-full">
                                    <input type="text" name="disk"
                                        class="input input-bordered join-item w-full focus:input-primary transition-all bg-base-100"
                                        data-multiplicator="true" value="{{ old('disk', $node->disk) }}" />
                                    <span class="btn btn-disabled join-item bg-base-300 text-xs font-bold">MiB</span>
                                </div>
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span
                                        class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Overallocate</span>
                                </label>
                                <div class="join w-full">
                                    <input type="text" name="disk_overallocate"
                                        class="input input-bordered join-item w-full focus:input-primary transition-all bg-base-100"
                                        value="{{ old('disk_overallocate', $node->disk_overallocate) }}" />
                                    <span class="btn btn-disabled join-item bg-base-300 text-xs font-bold">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- General Configuration -->
                <div class="card bg-base-200/50 shadow-sm backdrop-blur-md border border-base-300">
                    <div class="card-body p-6 space-y-6">
                        <h3
                            class="text-xl font-black tracking-tighter text-base-content uppercase border-b border-base-300 pb-4">
                            General Configuration</h3>

                        <div class="form-control">
                            <label class="label">
                                <span
                                    class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Max
                                    Web Upload Size</span>
                            </label>
                            <div class="join w-full">
                                <input type="text" name="upload_size"
                                    class="input input-bordered join-item w-full focus:input-primary transition-all bg-base-100"
                                    value="{{ old('upload_size', $node->upload_size) }}" />
                                <span class="btn btn-disabled join-item bg-base-300 text-xs font-bold">MiB</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span
                                        class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Daemon
                                        Port</span>
                                </label>
                                <input type="text" name="daemonListen"
                                    class="input input-bordered focus:input-primary transition-all bg-base-100"
                                    value="{{ old('daemonListen', $node->daemonListen) }}" />
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span
                                        class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">SFTP
                                        Port</span>
                                </label>
                                <input type="text" name="daemonSFTP"
                                    class="input input-bordered focus:input-primary transition-all bg-base-100"
                                    value="{{ old('daemonSFTP', $node->daemonSFTP) }}" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Backup Config -->
                <div class="card bg-base-200/50 shadow-sm backdrop-blur-md border border-base-300">
                    <div class="card-body p-6 space-y-6">
                        <h3
                            class="text-xl font-black tracking-tighter text-base-content uppercase border-b border-base-300 pb-4">
                            Backup Config</h3>
                        <div class="form-control">
                            <label class="label">
                                <span
                                    class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Backup
                                    Disk</span>
                            </label>
                            <select name="backupDisk" id="pBackupDisk"
                                class="select select-bordered focus:select-primary bg-base-100">
                                <!-- Populated via Script-->
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Save Settings -->
                <div class="card bg-primary/5 border border-primary/20 shadow-sm">
                    <div class="card-body p-6">
                        <h3 class="text-xl font-black tracking-tighter text-primary uppercase mb-4">Save Settings</h3>
                        <div class="form-control mb-6">
                            <label class="label cursor-pointer justify-start gap-4">
                                <input type="checkbox" name="reset_secret" id="reset_secret"
                                    class="checkbox checkbox-primary checkbox-sm" />
                                <span
                                    class="label-text font-bold uppercase text-xs tracking-widest text-base-content/70">Reset
                                    Daemon Master Key</span>
                            </label>
                            <p class="text-[10px] text-base-content/40 mt-2 italic">
                                Resetting the key will void any request coming from the old key. Suggest changing regularly
                                for security.
                            </p>
                        </div>
                        <div class="card-actions justify-end">
                            {!! method_field('PATCH') !!}
                            {!! csrf_field() !!}
                            <button type="submit"
                                class="btn btn-primary px-12 font-bold uppercase tracking-wider shadow-lg shadow-primary/20">Save
                                Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $(document).ready(function() {
            const daemonSelect = document.getElementById('pDaemonType');
            const backupDiskSelect = document.getElementById('pBackupDisk');

            function updateBackupDisks() {
                const daemonValue = daemonSelect.value;
                const disks = {!! json_encode($backupDisks ?? []) !!}[daemonValue] || [];

                backupDiskSelect.innerHTML = '';

                disks.forEach(disk => {
                    const option = document.createElement('option');
                    option.value = disk;
                    option.textContent = disk;

                    if (disk === '{{ old('backupDisk', $node->backupDisk) }}') {
                        option.selected = true;
                    }

                    backupDiskSelect.appendChild(option);
                });
            }

            updateBackupDisks();
            daemonSelect.addEventListener('change', updateBackupDisks);

            // Initialize select2 for location
            $('select[name="location_id"]').select2({
                theme: 'daisyui'
            });
        });
    </script>
    <style>
        .select2-container--default .select2-selection--single {
            @apply bg-base-100 border-base-300 rounded-lg h-12 flex items-center px-2;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            @apply text-base-content leading-tight;
        }

        .select2-dropdown {
            @apply bg-base-100 border-base-300 shadow-xl rounded-lg overflow-hidden;
        }

        .select2-results__option--highlighted[aria-selected] {
            @apply bg-primary text-primary-content;
        }
    </style>
@endsection
