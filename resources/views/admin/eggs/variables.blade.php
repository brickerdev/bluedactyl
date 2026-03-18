@extends('layouts.admin')

@section('title')
    Egg &rarr; {{ $egg->name }} &rarr; Variables
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter uppercase">{{ $egg->name }}</h1>
            <p class="text-base-content/60 text-sm">Managing variables for this Egg.</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.nests') }}">Nests</a></li>
                <li><a href="{{ route('admin.nests.view', $egg->nest->id) }}">{{ $egg->nest->name }}</a></li>
                <li><a href="{{ route('admin.nests.egg.view', $egg->id) }}">{{ $egg->name }}</a></li>
                <li class="text-primary font-bold">Variables</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="tabs tabs-box bg-base-200/50 p-1 rounded-xl inline-flex border border-base-300 w-fit">
            <a href="{{ route('admin.nests.egg.view', $egg->id) }}" class="tab !rounded-lg font-bold uppercase tracking-wide text-xs">Configuration</a>
            <a href="{{ route('admin.nests.egg.variables', $egg->id) }}" class="tab tab-active !rounded-lg font-bold uppercase tracking-wide text-xs">Variables</a>
            <a href="{{ route('admin.nests.egg.scripts', $egg->id) }}" class="tab !rounded-lg font-bold uppercase tracking-wide text-xs">Install Script</a>
        </div>
        <button class="btn btn-primary font-bold uppercase tracking-wider" onclick="newVariableModal.showModal()">
            <i class="ri-add-line mr-2"></i>
            Create New Variable
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($egg->variables as $variable)
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <i class="ri-code-box-line text-primary text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold uppercase tracking-tight">{{ $variable->name }}</h3>
                    </div>

                    <form action="{{ route('admin.nests.egg.variables.edit', ['egg' => $egg->id, 'variable' => $variable->id]) }}" method="POST">
                        <div class="space-y-4">
                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold uppercase tracking-wide text-xs">Name</span>
                                </label>
                                <input type="text" name="name" value="{{ $variable->name }}" class="input input-bordered w-full focus:input-primary transition-all" />
                            </div>

                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold uppercase tracking-wide text-xs">Description</span>
                                </label>
                                <textarea name="description" class="textarea textarea-bordered w-full h-24 focus:textarea-primary transition-all">{{ $variable->description }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text font-bold uppercase tracking-wide text-xs">Environment Variable</span>
                                    </label>
                                    <input type="text" name="env_variable" value="{{ $variable->env_variable }}" class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm" />
                                </div>
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text font-bold uppercase tracking-wide text-xs">Default Value</span>
                                    </label>
                                    <input type="text" name="default_value" value="{{ $variable->default_value }}" class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm" />
                                </div>
                            </div>
                            <p class="text-xs text-base-content/50 px-1">Access this in the startup command using <code>{{ $variable->env_variable }}</code>.</p>

                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold uppercase tracking-wide text-xs">Permissions</span>
                                </label>
                                <select name="options[]" class="select2-options w-full" multiple>
                                    <option value="user_viewable" {{ (! $variable->user_viewable) ?: 'selected' }}>Users Can View</option>
                                    <option value="user_editable" {{ (! $variable->user_editable) ?: 'selected' }}>Users Can Edit</option>
                                </select>
                            </div>

                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold uppercase tracking-wide text-xs">Input Rules</span>
                                </label>
                                <input type="text" name="rules" class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm" value="{{ $variable->rules }}" />
                                <label class="label">
                                    <span class="label-text-alt text-base-content/50">Defined using <a href="https://laravel.com/docs/5.7/validation#available-validation-rules" target="_blank" class="text-primary hover:underline">Laravel validation rules</a>.</span>
                                </label>
                            </div>
                        </div>

                        <div class="card-actions justify-between mt-8 pt-6 border-t border-base-300">
                            <button class="btn btn-ghost btn-error btn-sm font-bold uppercase tracking-wider delete-variable-btn" name="_method" value="DELETE" type="submit">
                                <i class="ri-delete-bin-line mr-2"></i>
                                <span>Delete</span>
                            </button>
                            <div class="flex gap-3">
                                {!! csrf_field() !!}
                                <button class="btn btn-primary btn-sm px-6 font-bold uppercase tracking-wider" name="_method" value="PATCH" type="submit">
                                    <i class="ri-save-line mr-2"></i>
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- New Variable Modal --}}
<dialog id="newVariableModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box bg-base-200 border border-base-300 p-0 overflow-hidden">
        <div class="p-6 border-b border-base-300 flex items-center justify-between bg-base-300/30">
            <h3 class="font-black uppercase tracking-tighter text-xl">Create New Egg Variable</h3>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost">✕</button>
            </form>
        </div>
        <form action="{{ route('admin.nests.egg.variables', $egg->id) }}" method="POST">
            <div class="p-6 space-y-4">
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-bold uppercase tracking-wide text-xs">Name <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="name" class="input input-bordered w-full focus:input-primary transition-all" value="{{ old('name') }}" required />
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-bold uppercase tracking-wide text-xs">Description</span>
                    </label>
                    <textarea name="description" class="textarea textarea-bordered w-full h-24 focus:textarea-primary transition-all">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Environment Variable <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="env_variable" class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm" value="{{ old('env_variable') }}" required />
                    </div>
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Default Value</span>
                        </label>
                        <input type="text" name="default_value" class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm" value="{{ old('default_value') }}" />
                    </div>
                </div>
                <p class="text-xs text-base-content/50 px-1">Access this in the startup command by entering <code>@{{environment variable value}}</code>.</p>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-bold uppercase tracking-wide text-xs">Permissions</span>
                    </label>
                    <select name="options[]" class="select2-options w-full" multiple>
                        <option value="user_viewable">Users Can View</option>
                        <option value="user_editable">Users Can Edit</option>
                    </select>
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-bold uppercase tracking-wide text-xs">Input Rules <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="rules" class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm" value="{{ old('rules', 'required|string|max:20') }}" placeholder="required|string|max:20" required />
                    <label class="label">
                        <span class="label-text-alt text-base-content/50">Defined using <a href="https://laravel.com/docs/5.7/validation#available-validation-rules" target="_blank" class="text-primary hover:underline">Laravel validation rules</a>.</span>
                    </label>
                </div>
            </div>
            <div class="modal-action p-6 border-t border-base-300 bg-base-300/30 mt-0">
                {!! csrf_field() !!}
                <form method="dialog">
                    <button class="btn btn-ghost font-bold uppercase tracking-wider">Cancel</button>
                </form>
                <button type="submit" class="btn btn-primary px-8 font-bold uppercase tracking-wider">Create Variable</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

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
        $(document).ready(function() {
            $('.select2-options').select2();
        });

        $('.delete-variable-btn').on('mouseenter', function (event) {
            $(this).find('span').text('Delete Variable');
        }).on('mouseleave', function (event) {
            $(this).find('span').text('Delete');
        });
    </script>
@endsection
