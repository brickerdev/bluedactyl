@extends('layouts.admin')

@section('title')
    Nests &rarr; Egg: {{ $egg->name }}
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter uppercase">{{ $egg->name }}</h1>
            <p class="text-base-content/60 text-sm">{{ str_limit($egg->description, 50) }}</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.nests') }}">Nests</a></li>
                <li><a href="{{ route('admin.nests.view', $egg->nest->id) }}">{{ $egg->nest->name }}</a></li>
                <li class="text-primary font-bold">{{ $egg->name }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="flex flex-col gap-6">
        <div class="tabs tabs-box bg-base-200/50 p-1 rounded-xl inline-flex border border-base-300 w-fit">
            <a href="{{ route('admin.nests.egg.view', $egg->id) }}"
                class="tab tab-active !rounded-lg font-bold uppercase tracking-wide text-xs">Configuration</a>
            <a href="{{ route('admin.nests.egg.variables', $egg->id) }}"
                class="tab !rounded-lg font-bold uppercase tracking-wide text-xs">Variables</a>
            <a href="{{ route('admin.nests.egg.scripts', $egg->id) }}"
                class="tab !rounded-lg font-bold uppercase tracking-wide text-xs">Install Script</a>
        </div>

        <form action="{{ route('admin.nests.egg.view', $egg->id) }}" enctype="multipart/form-data" method="POST">
            <div class="card bg-error/5 border border-error/20 shadow-xl backdrop-blur-md">
                <div class="card-body p-4">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4 flex-1">
                            <div class="w-10 h-10 rounded-xl bg-error/10 flex items-center justify-center shrink-0">
                                <i class="ri-file-upload-line text-error text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold uppercase tracking-tight text-error">Update Egg File</h3>
                                <p class="text-xs text-base-content/60">Upload a new JSON file to replace settings. This
                                    won't change existing servers.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 w-full md:w-auto">
                            <input type="file" name="import_file"
                                class="file-input file-input-bordered file-input-error file-input-sm w-full md:w-64" />
                            {!! csrf_field() !!}
                            <button type="submit" name="_method" value="PUT"
                                class="btn btn-error btn-sm font-bold uppercase tracking-wider">
                                Update Egg
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form action="{{ route('admin.nests.egg.view', $egg->id) }}" method="POST">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Configuration Card --}}
                <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                    <div class="card-body p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                <i class="ri-settings-4-line text-primary text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold uppercase tracking-tight">Configuration</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold uppercase tracking-wide text-xs">Name <span
                                            class="text-error">*</span></span>
                                </label>
                                <input type="text" name="name" value="{{ $egg->name }}"
                                    class="input input-bordered w-full focus:input-primary transition-all" />
                                <label class="label">
                                    <span class="label-text-alt text-base-content/50">A simple, human-readable name to use
                                        as an identifier for this Egg.</span>
                                </label>
                            </div>

                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold uppercase tracking-wide text-xs">UUID</span>
                                </label>
                                <input type="text" readonly value="{{ $egg->uuid }}"
                                    class="input input-bordered w-full bg-base-300/50 cursor-not-allowed" />
                                <label class="label">
                                    <span class="label-text-alt text-base-content/50">Globally unique identifier for this
                                        Egg used by the Daemon.</span>
                                </label>
                            </div>

                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold uppercase tracking-wide text-xs">Author</span>
                                </label>
                                <input type="text" readonly value="{{ $egg->author }}"
                                    class="input input-bordered w-full bg-base-300/50 cursor-not-allowed" />
                                <label class="label">
                                    <span class="label-text-alt text-base-content/50">The author of this version of the
                                        Egg.</span>
                                </label>
                            </div>

                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold uppercase tracking-wide text-xs">Docker Images <span
                                            class="text-error">*</span></span>
                                </label>
                                <textarea name="docker_images"
                                    class="textarea textarea-bordered w-full h-32 focus:textarea-primary transition-all font-mono text-sm">{{ implode(PHP_EOL, $images) }}</textarea>
                                <label class="label">
                                    <span class="label-text-alt text-base-content/50">One per line. Use <code>Display
                                            Name|image:tag</code> for custom labels.</span>
                                </label>
                            </div>

                            <div class="form-control">
                                <label
                                    class="label cursor-pointer justify-start gap-4 p-4 bg-base-300/30 rounded-xl border border-base-300/50 hover:bg-base-300/50 transition-all">
                                    <input type="checkbox" name="force_outgoing_ip" value="1"
                                        class="checkbox checkbox-primary"
                                        @if ($egg->force_outgoing_ip) checked @endif />
                                    <span class="label-text font-bold uppercase tracking-wide text-xs">Force Outgoing
                                        IP</span>
                                </label>
                                <div class="mt-2 px-2">
                                    <p class="text-xs text-base-content/60">Forces all outgoing network traffic to have its
                                        Source IP NATed to the server's primary allocation IP.</p>
                                    <p class="text-xs text-error font-bold mt-1 italic">Enabling this will disable internal
                                        networking for servers using this egg.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Description & Startup Card --}}
                <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                    <div class="card-body p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center">
                                <i class="ri-terminal-box-line text-secondary text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold uppercase tracking-tight">Details & Startup</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold uppercase tracking-wide text-xs">Description</span>
                                </label>
                                <textarea name="description" class="textarea textarea-bordered w-full h-32 focus:textarea-primary transition-all">{{ $egg->description }}</textarea>
                                <label class="label">
                                    <span class="label-text-alt text-base-content/50">A description of this Egg displayed
                                        throughout the Panel.</span>
                                </label>
                            </div>

                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold uppercase tracking-wide text-xs">Startup Command
                                        <span class="text-error">*</span></span>
                                </label>
                                <textarea name="startup"
                                    class="textarea textarea-bordered w-full h-32 focus:textarea-primary transition-all font-mono text-sm">{{ $egg->startup }}</textarea>
                                <label class="label">
                                    <span class="label-text-alt text-base-content/50">The default startup command for new
                                        servers using this Egg.</span>
                                </label>
                            </div>

                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold uppercase tracking-wide text-xs">Features</span>
                                </label>
                                <select class="select2-features w-full" name="features[]" multiple>
                                    @foreach ($egg->features ?? [] as $feature)
                                        <option value="{{ $feature }}" selected>{{ $feature }}</option>
                                    @endforeach
                                </select>
                                <label class="label">
                                    <span class="label-text-alt text-base-content/50">Additional features belonging to the
                                        egg.</span>
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
                                <p>Do not edit these unless you understand how the system works. Wrong modifications can
                                    break the daemon.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text font-bold uppercase tracking-wide text-xs">Copy Settings
                                            From</span>
                                    </label>
                                    <select name="config_from" id="pConfigFrom" class="select2-config w-full">
                                        <option value="">None</option>
                                        @foreach ($egg->nest->eggs as $o)
                                            <option value="{{ $o->id }}"
                                                {{ $egg->config_from !== $o->id ?: 'selected' }}>{{ $o->name }}
                                                <{{ $o->author }}>
                                            </option>
                                        @endforeach
                                    </select>
                                    <label class="label">
                                        <span class="label-text-alt text-base-content/50">Default to settings from another
                                            Egg.</span>
                                    </label>
                                </div>

                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text font-bold uppercase tracking-wide text-xs">Stop
                                            Command</span>
                                    </label>
                                    <input type="text" name="config_stop"
                                        class="input input-bordered w-full focus:input-primary transition-all"
                                        value="{{ $egg->config_stop }}" />
                                    <label class="label">
                                        <span class="label-text-alt text-base-content/50">Command to stop processes
                                            gracefully (e.g. <code>^C</code> for SIGINT).</span>
                                    </label>
                                </div>

                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text font-bold uppercase tracking-wide text-xs">Log
                                            Configuration</span>
                                    </label>
                                    <textarea data-action="handle-tabs" name="config_logs"
                                        class="textarea textarea-bordered w-full h-48 focus:textarea-primary transition-all font-mono text-sm">{{ !is_null($egg->config_logs) ? json_encode(json_decode($egg->config_logs), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '' }}</textarea>
                                    <label class="label">
                                        <span class="label-text-alt text-base-content/50">JSON representation of log file
                                            storage and custom logs.</span>
                                    </label>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text font-bold uppercase tracking-wide text-xs">Configuration
                                            Files</span>
                                    </label>
                                    <textarea data-action="handle-tabs" name="config_files"
                                        class="textarea textarea-bordered w-full h-48 focus:textarea-primary transition-all font-mono text-sm">{{ !is_null($egg->config_files) ? json_encode(json_decode($egg->config_files), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '' }}</textarea>
                                    <label class="label">
                                        <span class="label-text-alt text-base-content/50">JSON representation of
                                            configuration files to modify.</span>
                                    </label>
                                </div>

                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text font-bold uppercase tracking-wide text-xs">Start
                                            Configuration</span>
                                    </label>
                                    <textarea data-action="handle-tabs" name="config_startup"
                                        class="textarea textarea-bordered w-full h-48 focus:textarea-primary transition-all font-mono text-sm">{{ !is_null($egg->config_startup) ? json_encode(json_decode($egg->config_startup), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '' }}</textarea>
                                    <label class="label">
                                        <span class="label-text-alt text-base-content/50">JSON representation of boot
                                            detection values.</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card-actions justify-between mt-8 pt-6 border-t border-base-300">
                            <button id="deleteButton" type="submit" name="_method" value="DELETE"
                                class="btn btn-ghost btn-error btn-sm font-bold uppercase tracking-wider">
                                <i class="ri-delete-bin-line mr-2"></i>
                                <span>Delete</span>
                            </button>
                            <div class="flex gap-3">
                                <a href="{{ route('admin.nests.egg.export', $egg->id) }}"
                                    class="btn btn-info btn-sm font-bold uppercase tracking-wider">
                                    <i class="ri-download-cloud-line mr-2"></i>
                                    Export
                                </a>
                                {!! csrf_field() !!}
                                <button type="submit" name="_method" value="PATCH"
                                    class="btn btn-primary btn-sm px-8 font-bold uppercase tracking-wider">
                                    <i class="ri-save-line mr-2"></i>
                                    Save Configuration
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

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
    <script>
        $('.select2-config').select2();
        $('.select2-features').select2({
            tags: true,
            selectOnClose: false,
            tokenSeparators: [',', ' '],
        });

        $('#deleteButton').on('mouseenter', function(event) {
            $(this).find('span').text('Delete Egg');
        }).on('mouseleave', function(event) {
            $(this).find('span').text('Delete');
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
