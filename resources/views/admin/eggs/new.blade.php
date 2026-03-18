@extends('layouts.admin')

@section('title')
    Nests &rarr; New Egg
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter uppercase">New Egg</h1>
            <p class="text-base-content/60 text-sm">Create a new Egg to assign to servers.</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.nests') }}">Nests</a></li>
                <li class="text-primary font-bold">New Egg</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
<form action="{{ route('admin.nests.egg.new') }}" method="POST">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Configuration Card --}}
        <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
            <div class="card-body p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <i class="ri-add-box-line text-primary text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold uppercase tracking-tight">Configuration</h3>
                </div>

                <div class="space-y-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Associated Nest</span>
                        </label>
                        <select name="nest_id" id="pNestId" class="select2-nest w-full">
                            @foreach($nests as $nest)
                                <option value="{{ $nest->id }}" {{ old('nest_id') != $nest->id ?: 'selected' }}>{{ $nest->name }} <{{ $nest->author }}></option>
                            @endforeach
                        </select>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">Think of a Nest as a category. Related Eggs should be grouped in the same Nest.</span>
                        </label>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Name</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" class="input input-bordered w-full focus:input-primary transition-all" placeholder="e.g. BungeeCord" />
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">A simple, human-readable name for this Egg. Users will see this as the server type.</span>
                        </label>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Description</span>
                        </label>
                        <textarea name="description" class="textarea textarea-bordered w-full h-32 focus:textarea-primary transition-all">{{ old('description') }}</textarea>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">A brief description of this Egg.</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label cursor-pointer justify-start gap-4 p-4 bg-base-300/30 rounded-xl border border-base-300/50 hover:bg-base-300/50 transition-all">
                            <input type="checkbox" name="force_outgoing_ip" value="1" class="checkbox checkbox-primary" {{ \Pterodactyl\Helpers\Utilities::checked('force_outgoing_ip', 0) }} />
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Force Outgoing IP</span>
                        </label>
                        <div class="mt-2 px-2">
                            <p class="text-xs text-base-content/60">Forces all outgoing network traffic to have its Source IP NATed to the server's primary allocation IP.</p>
                            <p class="text-xs text-error font-bold mt-1 italic">Enabling this will disable internal networking for servers using this egg.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Startup & Features Card --}}
        <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
            <div class="card-body p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center">
                        <i class="ri-terminal-box-line text-secondary text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold uppercase tracking-tight">Startup & Features</h3>
                </div>

                <div class="space-y-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Docker Images</span>
                        </label>
                        <textarea name="docker_images" rows="4" placeholder="quay.io/pterodactyl/service" class="textarea textarea-bordered w-full focus:textarea-primary transition-all font-mono text-sm">{{ old('docker_images') }}</textarea>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">One per line. Users can select from this list if multiple are provided.</span>
                        </label>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Startup Command</span>
                        </label>
                        <textarea name="startup" class="textarea textarea-bordered w-full h-48 focus:textarea-primary transition-all font-mono text-sm">{{ old('startup') }}</textarea>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">The default startup command for new servers. Can be changed per-server.</span>
                        </label>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Features</span>
                        </label>
                        <select class="select2-features w-full" name="features[]" id="pConfigFeatures" multiple></select>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">Additional features for panel modifications.</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Process Management Card --}}
        <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md lg:col-span-2">
            <div class="card-body p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-warning/10 flex items-center justify-center">
                        <i class="ri-cpu-line text-warning text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold uppercase tracking-tight">Process Management</h3>
                </div>

                <div class="alert alert-soft alert-warning mb-6">
                    <i class="ri-error-warning-line text-xl"></i>
                    <div class="text-sm">
                        <p class="font-bold uppercase tracking-wide">Advanced Configuration</p>
                        <p>All fields are required unless you copy settings from another Egg.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Copy Settings From</span>
                            </label>
                            <select name="config_from" id="pConfigFrom" class="select2-config w-full">
                                <option value="">None</option>
                            </select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Default to settings from another Egg in the same Nest.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Stop Command</span>
                            </label>
                            <input type="text" name="config_stop" class="input input-bordered w-full focus:input-primary transition-all" value="{{ old('config_stop') }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Command to stop processes gracefully (e.g. <code>^C</code>).</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Log Configuration</span>
                            </label>
                            <textarea data-action="handle-tabs" name="config_logs" class="textarea textarea-bordered w-full h-48 focus:textarea-primary transition-all font-mono text-sm">{{ old('config_logs') }}</textarea>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">JSON representation of log storage.</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Configuration Files</span>
                            </label>
                            <textarea data-action="handle-tabs" name="config_files" class="textarea textarea-bordered w-full h-48 focus:textarea-primary transition-all font-mono text-sm">{{ old('config_files') }}</textarea>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">JSON representation of configuration files to modify.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Start Configuration</span>
                            </label>
                            <textarea data-action="handle-tabs" name="config_startup" class="textarea textarea-bordered w-full h-48 focus:textarea-primary transition-all font-mono text-sm">{{ old('config_startup') }}</textarea>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">JSON representation of boot detection values.</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card-actions justify-end mt-8 pt-6 border-t border-base-300">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-primary px-8 font-bold uppercase tracking-wider">
                        <i class="ri-add-line mr-2"></i>
                        Create Egg
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

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
        @apply bg-primary text-primary-content border-none rounded-md px-2 py-0.5 text-xs font-bold uppercase tracking-wide;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        @apply text-primary-content mr-1 hover:text-white;
    }
    .select2-dropdown {
        @apply bg-base-100 border-base-300 rounded-lg shadow-2xl;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        @apply bg-primary text-primary-content;
    }
</style>
@endsection

@section('footer-scripts')
    @parent
    {!! Theme::js('vendor/lodash/lodash.js') !!}
    <script>
    $(document).ready(function() {
        $('.select2-nest').select2().change();
        $('.select2-config').select2();
        $('.select2-features').select2({
            tags: true,
            selectOnClose: false,
            tokenSeparators: [',', ' '],
        });
    });

    $('#pNestId').on('change', function (event) {
        $('#pConfigFrom').html('<option value="">None</option>').select2({
            data: $.map(_.get(Bluedactyl.nests, $(this).val() + '.eggs', []), function (item) {
                return {
                    id: item.id,
                    text: item.name + ' <' + item.author + '>',
                };
            }),
        });
    });

    $('textarea[data-action="handle-tabs"]').on('keydown', function(event) {
        if (event.keyCode === 9) {
            event.preventDefault();

            var curPos = $(this)[0].selectionStart;
            var prepend = $(this).val().substr(0, curPos);
            var append = $(this).val().substr(curPos);

            $(this).val(prepend + '    ' + append);
        }
    });
    </script>
@endsection
