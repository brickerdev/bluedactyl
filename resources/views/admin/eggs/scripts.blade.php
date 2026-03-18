@extends('layouts.admin')

@section('title')
    Nests &rarr; Egg: {{ $egg->name }} &rarr; Install Script
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter uppercase">{{ $egg->name }}</h1>
            <p class="text-base-content/60 text-sm">Manage the install script for this Egg.</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.nests') }}">Nests</a></li>
                <li><a href="{{ route('admin.nests.view', $egg->nest->id) }}">{{ $egg->nest->name }}</a></li>
                <li><a href="{{ route('admin.nests.egg.view', $egg->id) }}">{{ $egg->name }}</a></li>
                <li class="text-primary font-bold">Install Script</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="flex flex-col gap-6">
        <div class="tabs tabs-box bg-base-200/50 p-1 rounded-xl inline-flex border border-base-300 w-fit">
            <a href="{{ route('admin.nests.egg.view', $egg->id) }}"
                class="tab !rounded-lg font-bold uppercase tracking-wide text-xs">Configuration</a>
            <a href="{{ route('admin.nests.egg.variables', $egg->id) }}"
                class="tab !rounded-lg font-bold uppercase tracking-wide text-xs">Variables</a>
            <a href="{{ route('admin.nests.egg.scripts', $egg->id) }}"
                class="tab tab-active !rounded-lg font-bold uppercase tracking-wide text-xs">Install Script</a>
        </div>

        <form action="{{ route('admin.nests.egg.scripts', $egg->id) }}" method="POST">
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <i class="ri-terminal-window-line text-primary text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold uppercase tracking-tight">Install Script</h3>
                    </div>

                    @if (!is_null($egg->copyFrom))
                        <div class="alert alert-soft alert-warning mb-6">
                            <i class="ri-error-warning-line text-xl"></i>
                            <div class="text-sm">
                                <p class="font-bold uppercase tracking-wide">Inherited Script</p>
                                <p>This service option is copying installation scripts and container options from <a
                                        href="{{ route('admin.nests.egg.view', $egg->copyFrom->id) }}"
                                        class="underline font-bold">{{ $egg->copyFrom->name }}</a>. Changes won't apply
                                    unless you select "None" below.</p>
                            </div>
                        </div>
                    @endif

                    <div class="rounded-xl border border-base-300 overflow-hidden mb-6">
                        <div id="editor_install" style="height:400px" class="w-full">{{ $egg->script_install }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Copy Script From</span>
                            </label>
                            <select id="pCopyScriptFrom" name="copy_script_from" class="select2-copy w-full">
                                <option value="">None</option>
                                @foreach ($copyFromOptions as $opt)
                                    <option value="{{ $opt->id }}"
                                        {{ $egg->copy_script_from !== $opt->id ?: 'selected' }}>{{ $opt->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">If selected, the script above will be
                                    ignored.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Script Container</span>
                            </label>
                            <input type="text" name="script_container"
                                class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm"
                                value="{{ $egg->script_container }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Docker container to use for
                                    installation.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Script Entrypoint
                                    Command</span>
                            </label>
                            <input type="text" name="script_entry"
                                class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm"
                                value="{{ $egg->script_entry }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">The entrypoint command for this
                                    script.</span>
                            </label>
                        </div>
                    </div>

                    <div class="p-4 bg-base-300/30 rounded-xl border border-base-300/50">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-base-content/40 mb-3">Dependent Service
                            Options</h4>
                        <div class="flex flex-wrap gap-2">
                            @if (count($relyOnScript) > 0)
                                @foreach ($relyOnScript as $rely)
                                    <a href="{{ route('admin.nests.egg.view', $rely->id) }}"
                                        class="badge badge-soft badge-primary font-mono text-xs py-3">
                                        {{ $rely->name }}
                                    </a>
                                @endforeach
                            @else
                                <span class="text-sm italic text-base-content/40">No other eggs rely on this script.</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-actions justify-end mt-8 pt-6 border-t border-base-300">
                        {!! csrf_field() !!}
                        <textarea name="script_install" class="hidden"></textarea>
                        <button type="submit" name="_method" value="PATCH"
                            class="btn btn-primary px-8 font-bold uppercase tracking-wider">
                            <i class="ri-save-line mr-2"></i>
                            Save Script
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .select2-container--default .select2-selection--single {
            @apply bg-base-100 border-base-300 rounded-lg h-12 flex items-center transition-all;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            @apply border-primary ring-1 ring-primary;
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
    {!! Theme::js('vendor/ace/ace.js') !!}
    {!! Theme::js('vendor/ace/ext-modelist.js') !!}
    <script>
        $(document).ready(function() {
            $('.select2-copy').select2();

            const InstallEditor = ace.edit('editor_install');
            const Modelist = ace.require('ace/ext/modelist')

            InstallEditor.setTheme('ace/theme/chrome');
            InstallEditor.getSession().setMode('ace/mode/sh');
            InstallEditor.getSession().setUseWrapMode(true);
            InstallEditor.setShowPrintMargin(false);

            $('form').on('submit', function(e) {
                $('textarea[name="script_install"]').val(InstallEditor.getValue());
            });
        });
    </script>
@endsection
