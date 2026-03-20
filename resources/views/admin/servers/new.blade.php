@extends('layouts.admin')

@section('title')
    New Server
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter uppercase">Create Server</h1>
            <p class="text-base-content/60 text-sm">Add a new server to the panel.</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.servers') }}">Servers</a></li>
                <li class="text-primary font-bold">Create Server</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.servers.new') }}" method="POST">
        <div class="grid grid-cols-1 gap-6">
            {{-- Core Details --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <i class="ri-information-line text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold uppercase tracking-tight">Core Details</h3>
                            <p class="text-xs text-base-content/50 italic">Basic identity and ownership settings for the
                                server.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Server Name</span>
                            </label>
                            <input type="text" class="input input-bordered focus:input-primary w-full transition-all"
                                id="pName" name="name" value="{{ old('name') }}" placeholder="Server Name">
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Character limits:
                                    <code>a-z A-Z 0-9 _ - .</code> and <code>[Space]</code>.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Server Description</span>
                            </label>
                            <textarea id="pDescription" name="description" rows="3"
                                class="textarea textarea-bordered focus:textarea-primary w-full transition-all">{{ old('description') }}</textarea>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">A brief description of this server.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Server Owner</span>
                            </label>
                            <select id="pUserId" name="owner_id" class="w-full"></select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Email address of the Server Owner.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label cursor-pointer justify-start gap-4">
                                <input id="pStartOnCreation" name="start_on_completion" type="checkbox"
                                    class="checkbox checkbox-primary"
                                    {{ \Pterodactyl\Helpers\Utilities::checked('start_on_completion', 1) }} />
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Start Server when
                                    Installed</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Allocation Management --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                <div class="card-body p-6 relative">
                    <div class="overlay absolute inset-0 bg-base-200/50 backdrop-blur-sm z-10 flex items-center justify-center rounded-xl"
                        id="allocationLoader" style="display:none;">
                        <span class="loading loading-spinner loading-lg text-primary"></span>
                    </div>

                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <i class="ri-router-line text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold uppercase tracking-tight">Allocation Management</h3>
                            <p class="text-xs text-base-content/50 italic">Control how this server connects to the network.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Node</span>
                            </label>
                            <select name="node_id" id="pNodeId" class="w-full">
                                @foreach ($locations as $location)
                                    <optgroup label="{{ $location->long }} ({{ $location->short }})">
                                        @foreach ($location->nodes as $node)
                                            <option value="{{ $node->id }}"
                                                @if ($location->id === old('location_id')) selected @endif>{{ $node->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">The node which this server will be
                                    deployed to.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Default Allocation</span>
                            </label>
                            <select id="pAllocation" name="allocation_id" class="w-full"></select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">The main allocation that will be assigned
                                    to this server.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Additional
                                    Allocation(s)</span>
                            </label>
                            <select id="pAllocationAdditional" name="allocation_additional[]" class="w-full"
                                multiple></select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Additional allocations to assign to this
                                    server on creation.</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Application Feature Limits --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <i class="ri-equalizer-line text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold uppercase tracking-tight">Application Feature Limits</h3>
                            <p class="text-xs text-base-content/50 italic">Limits for databases, allocations, and backups.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Database Limit</span>
                            </label>
                            <input type="text" id="pDatabaseLimit" name="database_limit"
                                class="input input-bordered focus:input-primary w-full transition-all"
                                value="{{ old('database_limit') }}" placeholder="Leave blank for unlimited" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 text-xs">Total databases allowed.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Allocation Limit</span>
                            </label>
                            <input type="text" id="pAllocationLimit" name="allocation_limit"
                                class="input input-bordered focus:input-primary w-full transition-all"
                                value="{{ old('allocation_limit') }}" placeholder="Leave blank for unlimited" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 text-xs">Total allocations allowed.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Backup Limit</span>
                            </label>
                            <input type="text" id="pBackupLimit" name="backup_limit"
                                class="input input-bordered focus:input-primary w-full transition-all"
                                value="{{ old('backup_limit') }}" placeholder="Leave blank for unlimited" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 text-xs">Total backups allowed.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Backup Storage
                                    Limit</span>
                            </label>
                            <div class="join w-full">
                                <input type="text" id="pBackupStorageLimit" name="backup_storage_limit"
                                    data-multiplicator="true"
                                    class="input input-bordered focus:input-primary join-item w-full transition-all"
                                    value="{{ old('backup_storage_limit') }}" placeholder="Leave blank for unlimited" />
                                <span
                                    class="join-item btn btn-disabled bg-base-300 border-base-300 text-base-content/50">MiB</span>
                            </div>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 text-xs">Total storage for backups.</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Resource Management --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <i class="ri-cpu-line text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold uppercase tracking-tight">Resource Management</h3>
                            <p class="text-xs text-base-content/50 italic">Configure CPU, Memory, and Disk limits.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">CPU Limit</span>
                            </label>
                            <div class="join w-full">
                                <input type="text" id="pCPU" name="cpu"
                                    class="input input-bordered focus:input-primary join-item w-full transition-all"
                                    value="{{ old('cpu', 0) }}" />
                                <span
                                    class="join-item btn btn-disabled bg-base-300 border-base-300 text-base-content/50">%</span>
                            </div>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 text-xs">0 for unlimited. Threads *
                                    100.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">CPU Pinning</span>
                            </label>
                            <input type="text" id="pThreads" name="threads"
                                class="input input-bordered focus:input-primary w-full transition-all"
                                value="{{ old('threads') }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 text-xs">Advanced: Specific CPU threads
                                    (e.g. 0,1,3).</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Memory</span>
                            </label>
                            <div class="join w-full">
                                <input type="text" id="pMemory" name="memory"
                                    class="input input-bordered focus:input-primary join-item w-full transition-all"
                                    value="{{ old('memory') }}" />
                                <span
                                    class="join-item btn btn-disabled bg-base-300 border-base-300 text-base-content/50">MiB</span>
                            </div>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 text-xs">Maximum memory allowed. 0 for
                                    unlimited.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Overhead Memory</span>
                            </label>
                            <div class="join w-full">
                                <input type="text" id="pOverheadMemory" name="overhead_memory"
                                    class="input input-bordered focus:input-primary join-item w-full transition-all"
                                    value="{{ old('overhead_memory', 0) }}" />
                                <span
                                    class="join-item btn btn-disabled bg-base-300 border-base-300 text-base-content/50">MiB</span>
                            </div>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 text-xs">Additional memory for the
                                    container.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Swap</span>
                            </label>
                            <div class="join w-full">
                                <input type="text" id="pSwap" name="swap"
                                    class="input input-bordered focus:input-primary join-item w-full transition-all"
                                    value="{{ old('swap', 0) }}" />
                                <span
                                    class="join-item btn btn-disabled bg-base-300 border-base-300 text-base-content/50">MiB</span>
                            </div>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 text-xs">0 to disable, -1 for
                                    unlimited.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Disk Space</span>
                            </label>
                            <div class="join w-full">
                                <input type="text" id="pDisk" name="disk"
                                    class="input input-bordered focus:input-primary join-item w-full transition-all"
                                    value="{{ old('disk') }}" />
                                <span
                                    class="join-item btn btn-disabled bg-base-300 border-base-300 text-base-content/50">MiB</span>
                            </div>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 text-xs">0 for unlimited disk usage.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Block IO Weight</span>
                            </label>
                            <input type="text" id="pIO" name="io"
                                class="input input-bordered focus:input-primary w-full transition-all"
                                value="{{ old('io', 500) }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 text-xs">Advanced: IO performance
                                    (10-1000).</span>
                            </label>
                        </div>

                        <div class="flex flex-col gap-4">
                            <div class="form-control">
                                <label class="label cursor-pointer justify-start gap-4">
                                    <input type="checkbox" id="pOomDisabled" name="oom_disabled" value="0"
                                        class="checkbox checkbox-primary"
                                        {{ \Pterodactyl\Helpers\Utilities::checked('oom_disabled', 0) }} />
                                    <span class="label-text font-bold uppercase tracking-wide text-xs">Enable OOM
                                        Killer</span>
                                </label>
                                <span class="text-xs text-base-content/50 ml-10">Terminates the server if it breaches memory
                                    limits.</span>
                            </div>

                            <div class="form-control">
                                <label class="label cursor-pointer justify-start gap-4">
                                    <input type="checkbox" id="pExcludeFromResourceCalculation"
                                        name="exclude_from_resource_calculation" value="1"
                                        class="checkbox checkbox-primary"
                                        {{ \Pterodactyl\Helpers\Utilities::checked('exclude_from_resource_calculation', 0) }} />
                                    <span class="label-text font-bold uppercase tracking-wide text-xs">Exclude from Resource
                                        Calculation</span>
                                </label>
                                <span class="text-xs text-base-content/50 ml-10">Exclude from node resource
                                    calculations.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nest & Docker Configuration --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                    <div class="card-body p-6">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                <i class="ri-folder-zip-line text-primary text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold uppercase tracking-tight">Nest Configuration</h3>
                                <p class="text-xs text-base-content/50 italic">Select the Nest and Egg for this server.</p>
                            </div>
                        </div>

                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Nest</span>
                            </label>
                            <select id="pNestId" name="nest_id" class="w-full">
                                @foreach ($nests as $nest)
                                    <option value="{{ $nest->id }}"
                                        @if ($nest->id === old('nest_id')) selected="selected" @endif>{{ $nest->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Egg</span>
                            </label>
                            <select id="pEggId" name="egg_id" class="w-full"></select>
                        </div>

                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-4">
                                <input type="checkbox" id="pSkipScripting" name="skip_scripts" value="1"
                                    class="checkbox checkbox-primary"
                                    {{ \Pterodactyl\Helpers\Utilities::checked('skip_scripts', 0) }} />
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Skip Egg Install
                                    Script</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                    <div class="card-body p-6">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                <i class="ri-docker-line text-primary text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold uppercase tracking-tight">Docker Configuration</h3>
                                <p class="text-xs text-base-content/50 italic">Configure the Docker image for this server.
                                </p>
                            </div>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Docker Image</span>
                            </label>
                            <select id="pDefaultContainer" name="image" class="w-full mb-4"></select>
                            <input id="pDefaultContainerCustom" name="custom_image" value="{{ old('custom_image') }}"
                                class="input input-bordered focus:input-primary w-full transition-all"
                                placeholder="Or enter a custom image..." />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50 text-xs">The default Docker image used to
                                    run this server.</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Startup Configuration --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <i class="ri-terminal-box-line text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold uppercase tracking-tight">Startup Configuration</h3>
                            <p class="text-xs text-base-content/50 italic">Configure the startup command and variables.</p>
                        </div>
                    </div>

                    <div class="form-control w-full mb-8">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Startup Command</span>
                        </label>
                        <input type="text" id="pStartup" name="startup" value="{{ old('startup') }}"
                            class="input input-bordered focus:input-primary w-full transition-all" />
                        <label class="label">
                            <span class="label-text-alt text-base-content/50 text-xs">Available substitutes:
                                <code>@{{ SERVER_MEMORY }}</code>, <code>@{{ SERVER_IP }}</code>, and
                                <code>@{{ SERVER_PORT }}</code>.</span>
                        </label>
                    </div>

                    <div class="divider uppercase font-bold text-xs opacity-50">Service Variables</div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8" id="appendVariablesTo"></div>

                    <div class="card-actions justify-end mt-12 pt-8 border-t border-base-300">
                        {!! csrf_field() !!}
                        <button type="submit" class="btn btn-primary px-12 font-bold uppercase tracking-wider">
                            <i class="ri-add-line mr-2"></i>
                            Create Server
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('footer-scripts')
    @parent
    {!! Theme::js('vendor/lodash/lodash.js') !!}

    <style>
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple {
            @apply bg-base-100 border-base-300 rounded-lg h-12 flex items-center transition-all;
        }

        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            @apply border-primary ring-1 ring-primary;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            @apply bg-primary text-primary-content border-none rounded-md px-2 py-0.5 text-xs font-bold uppercase tracking-wide mt-1;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            @apply text-primary-content hover:text-white mr-1;
        }

        .select2-dropdown {
            @apply bg-base-100 border-base-300 rounded-lg shadow-2xl overflow-hidden;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            @apply bg-primary text-primary-content;
        }

        .select2-results__option {
            @apply text-base-content px-4 py-2;
        }

        .select2-results__group {
            @apply font-black text-[10px] uppercase tracking-widest text-base-content/40 px-3 py-2 bg-base-300/20;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            @apply text-base-content leading-tight;
        }
    </style>

    <script type="application/javascript">
        // Persist 'Service Variables'
        function serviceVariablesUpdated(eggId, ids) {
            @if (old('egg_id'))
                // Check if the egg id matches.
                if (eggId != '{{ old('egg_id') }}') {
                    return;
                }

                @if (old('environment'))
                    @foreach (old('environment') as $key => $value)
                        $('#' + ids['{{ $key }}']).val('{{ $value }}');
                    @endforeach
                @endif
            @endif
            @if (old('image'))
                $('#pDefaultContainer').val('{{ old('image') }}');
            @endif
        }
        // END Persist 'Service Variables'
    </script>

    {!! Theme::js('js/admin/new-server.js?v=20220530') !!}

    <script type="application/javascript">
        $(document).ready(function() {
            // Initialize Select2 for all standard selects that need it
            $('#pNodeId, #pNestId, #pEggId, #pDefaultContainer, #pAllocation, #pAllocationAdditional').select2();

            // Persist 'Server Owner' select2
            @if (old('owner_id'))
                $.ajax({
                    url: '/admin/users/accounts.json?user_id={{ old('owner_id') }}',
                    dataType: 'json',
                }).then(function(data) {
                    initUserIdSelect([data]);
                });
            @else
                initUserIdSelect();
            @endif
            // END Persist 'Server Owner' select2

            // Persist 'Node' select2
            @if (old('node_id'))
                $('#pNodeId').val('{{ old('node_id') }}').change();

                // Persist 'Default Allocation' select2
                @if (old('allocation_id'))
                    $('#pAllocation').val('{{ old('allocation_id') }}').change();
                @endif
                // END Persist 'Default Allocation' select2

                // Persist 'Additional Allocations' select2
                @if (old('allocation_additional'))
                    const additional_allocations = [];

                    @for ($i = 0; $i < count(old('allocation_additional')); $i++)
                        additional_allocations.push('{{ old('allocation_additional.' . $i) }}');
                    @endfor

                    $('#pAllocationAdditional').val(additional_allocations).change();
                @endif
                // END Persist 'Additional Allocations' select2
            @endif
            // END Persist 'Node' select2

            // Persist 'Nest' select2
            @if (old('nest_id'))
                $('#pNestId').val('{{ old('nest_id') }}').change();

                // Persist 'Egg' select2
                @if (old('egg_id'))
                    $('#pEggId').val('{{ old('egg_id') }}').change();
                @endif
                // END Persist 'Egg' select2
            @endif
            // END Persist 'Nest' select2
        });
    </script>
@endsection
