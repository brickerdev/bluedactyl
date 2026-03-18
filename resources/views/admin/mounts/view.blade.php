@extends('layouts.admin')

@section('title')
    Mounts &rarr; View &rarr; {{ $mount->id }}
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter uppercase">{{ $mount->name }}</h1>
            <p class="text-base-content/60 text-sm">{{ str_limit($mount->description, 75) }}</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.mounts') }}">Mounts</a></li>
                <li class="text-primary font-bold">{{ $mount->name }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Mount Details -->
        <div class="card bg-base-200/50 shadow-xl backdrop-blur-md border border-base-300">
            <div class="card-body p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <i class="ri-hard-drive-2-line text-primary text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold uppercase tracking-tight">Mount Details</h3>
                </div>

                <form action="{{ route('admin.mounts.view', $mount->id) }}" method="POST" class="space-y-6">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Unique ID</span>
                        </label>
                        <input type="text" class="input input-bordered bg-base-300/50 font-mono text-sm"
                            value="{{ $mount->uuid }}" disabled />
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Name</span>
                        </label>
                        <input type="text" name="name" class="input input-bordered focus:input-primary transition-all"
                            value="{{ $mount->name }}" required />
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Description</span>
                        </label>
                        <textarea name="description" class="textarea textarea-bordered focus:textarea-primary transition-all h-24">{{ $mount->description }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Source</span>
                            </label>
                            <input type="text" name="source"
                                class="input input-bordered focus:input-primary transition-all font-mono text-sm"
                                value="{{ $mount->source }}" required />
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Target</span>
                            </label>
                            <input type="text" name="target"
                                class="input input-bordered focus:input-primary transition-all font-mono text-sm"
                                value="{{ $mount->target }}" required />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Read Only</span>
                            </label>
                            <div class="flex gap-4 p-2 bg-base-300/30 rounded-xl border border-base-300/50">
                                <label class="label cursor-pointer flex items-center gap-2 flex-1 justify-center">
                                    <input type="radio" name="read_only" value="0"
                                        class="radio radio-primary radio-sm"
                                        @if (!$mount->read_only) checked @endif>
                                    <span class="label-text font-bold text-xs uppercase">False</span>
                                </label>
                                <div class="divider divider-horizontal m-0"></div>
                                <label class="label cursor-pointer flex items-center gap-2 flex-1 justify-center">
                                    <input type="radio" name="read_only" value="1"
                                        class="radio radio-warning radio-sm"
                                        @if ($mount->read_only) checked @endif>
                                    <span class="label-text font-bold text-xs uppercase">True</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">User Mountable</span>
                            </label>
                            <div class="flex gap-4 p-2 bg-base-300/30 rounded-xl border border-base-300/50">
                                <label class="label cursor-pointer flex items-center gap-2 flex-1 justify-center">
                                    <input type="radio" name="user_mountable" value="0"
                                        class="radio radio-primary radio-sm"
                                        @if (!$mount->user_mountable) checked @endif>
                                    <span class="label-text font-bold text-xs uppercase">False</span>
                                </label>
                                <div class="divider divider-horizontal m-0"></div>
                                <label class="label cursor-pointer flex items-center gap-2 flex-1 justify-center">
                                    <input type="radio" name="user_mountable" value="1"
                                        class="radio radio-warning radio-sm"
                                        @if ($mount->user_mountable) checked @endif>
                                    <span class="label-text font-bold text-xs uppercase">True</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card-actions justify-between mt-8 pt-6 border-t border-base-300">
                        {!! csrf_field() !!}
                        {!! method_field('PATCH') !!}
                        <button type="submit" name="action" value="delete"
                            class="btn btn-ghost btn-error btn-sm font-bold uppercase tracking-wider"
                            onclick="return confirm('Are you sure you want to delete this mount?')">
                            <i class="ri-delete-bin-line mr-2"></i> Delete Mount
                        </button>
                        <button type="submit" name="action" value="edit"
                            class="btn btn-primary btn-sm px-8 font-bold uppercase tracking-wider">
                            <i class="ri-save-line mr-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex flex-col gap-8">
            <!-- Eggs -->
            <div class="card bg-base-200/50 shadow-xl backdrop-blur-md border border-base-300 overflow-hidden">
                <div class="p-4 border-b border-base-300 bg-base-300/30 flex items-center justify-between">
                    <h3 class="font-black uppercase tracking-tighter">Associated Eggs</h3>
                    <button class="btn btn-primary btn-xs font-bold uppercase tracking-wider"
                        onclick="add_eggs_modal.showModal()">
                        <i class="ri-add-line mr-1"></i> Add Eggs
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-base-300/50">
                                <th class="text-[10px] uppercase tracking-widest font-black">ID</th>
                                <th class="text-[10px] uppercase tracking-widest font-black">Name</th>
                                <th class="text-[10px] uppercase tracking-widest font-black text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mount->eggs as $egg)
                                <tr class="hover:bg-base-300/30 transition-colors">
                                    <td><code class="text-xs font-bold">{{ $egg->id }}</code></td>
                                    <td>
                                        <a href="{{ route('admin.nests.egg.view', $egg->id) }}"
                                            class="link link-primary font-bold hover:no-underline">{{ $egg->name }}</a>
                                    </td>
                                    <td class="text-right">
                                        <button data-action="detach-egg" data-id="{{ $egg->id }}"
                                            class="btn btn-ghost btn-xs text-error tooltip" data-tip="Detach Egg">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($mount->eggs->isEmpty())
                                <tr>
                                    <td colspan="3" class="text-center py-8 text-base-content/40 italic text-sm">No
                                        eggs associated with this mount.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Nodes -->
            <div class="card bg-base-200/50 shadow-xl backdrop-blur-md border border-base-300 overflow-hidden">
                <div class="p-4 border-b border-base-300 bg-base-300/30 flex items-center justify-between">
                    <h3 class="font-black uppercase tracking-tighter">Associated Nodes</h3>
                    <button class="btn btn-primary btn-xs font-bold uppercase tracking-wider"
                        onclick="add_nodes_modal.showModal()">
                        <i class="ri-add-line mr-1"></i> Add Nodes
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-base-300/50">
                                <th class="text-[10px] uppercase tracking-widest font-black">ID</th>
                                <th class="text-[10px] uppercase tracking-widest font-black">Name</th>
                                <th class="text-[10px] uppercase tracking-widest font-black">FQDN</th>
                                <th class="text-[10px] uppercase tracking-widest font-black text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mount->nodes as $node)
                                <tr class="hover:bg-base-300/30 transition-colors">
                                    <td><code class="text-xs font-bold">{{ $node->id }}</code></td>
                                    <td>
                                        <a href="{{ route('admin.nodes.view', $node->id) }}"
                                            class="link link-primary font-bold hover:no-underline">{{ $node->name }}</a>
                                    </td>
                                    <td><code class="text-xs font-bold">{{ $node->fqdn }}</code></td>
                                    <td class="text-right">
                                        <button data-action="detach-node" data-id="{{ $node->id }}"
                                            class="btn btn-ghost btn-xs text-error tooltip" data-tip="Detach Node">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($mount->nodes->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-base-content/40 italic text-sm">No
                                        nodes associated with this mount.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Eggs Modal -->
    <dialog id="add_eggs_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-base-200 border border-base-300 p-0 overflow-hidden">
            <div class="p-6 border-b border-base-300 flex items-center justify-between bg-base-300/30">
                <h3 class="font-black uppercase tracking-tighter text-xl">Add Eggs to Mount</h3>
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost">✕</button>
                </form>
            </div>
            <form action="{{ route('admin.mounts.eggs', $mount->id) }}" method="POST">
                {!! csrf_field() !!}
                <div class="p-6">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Select Eggs</span>
                        </label>
                        <select id="pEggs" name="eggs[]" class="select2-daisy w-full" multiple>
                            @foreach ($nests as $nest)
                                <optgroup label="{{ $nest->name }}">
                                    @foreach ($nest->eggs as $egg)
                                        @if (!in_array($egg->id, $mount->eggs->pluck('id')->toArray()))
                                            <option value="{{ $egg->id }}">{{ $egg->name }}</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-action p-6 border-t border-base-300 bg-base-300/30 mt-0">
                    <form method="dialog">
                        <button class="btn btn-ghost font-bold uppercase tracking-wider">Cancel</button>
                    </form>
                    <button type="submit" class="btn btn-primary px-8 font-bold uppercase tracking-wider">Add
                        Selected</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Add Nodes Modal -->
    <dialog id="add_nodes_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-base-200 border border-base-300 p-0 overflow-hidden">
            <div class="p-6 border-b border-base-300 flex items-center justify-between bg-base-300/30">
                <h3 class="font-black uppercase tracking-tighter text-xl">Add Nodes to Mount</h3>
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost">✕</button>
                </form>
            </div>
            <form action="{{ route('admin.mounts.nodes', $mount->id) }}" method="POST">
                {!! csrf_field() !!}
                <div class="p-6">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Select Nodes</span>
                        </label>
                        <select id="pNodes" name="nodes[]" class="select2-daisy w-full" multiple>
                            @foreach ($locations as $location)
                                <optgroup label="{{ $location->long }} ({{ $location->short }})">
                                    @foreach ($location->nodes as $node)
                                        @if (!in_array($node->id, $mount->nodes->pluck('id')->toArray()))
                                            <option value="{{ $node->id }}">{{ $node->name }}</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-action p-6 border-t border-base-300 bg-base-300/30 mt-0">
                    <form method="dialog">
                        <button class="btn btn-ghost font-bold uppercase tracking-wider">Cancel</button>
                    </form>
                    <button type="submit" class="btn btn-primary px-8 font-bold uppercase tracking-wider">Add
                        Selected</button>
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
    <style>
        .select2-container--default .select2-selection--multiple {
            @apply bg-base-100 border-base-300 rounded-lg min-h-[3rem] p-1 flex items-center transition-all;
        }

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
            @apply bg-base-100 border-base-300 shadow-2xl rounded-lg overflow-hidden;
        }

        .select2-results__option--highlighted[aria-selected] {
            @apply bg-primary text-primary-content;
        }

        .select2-results__group {
            @apply font-black text-[10px] uppercase tracking-widest text-base-content/40 px-3 py-2 bg-base-300/20;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#pEggs').select2({
                placeholder: 'Select eggs..',
                dropdownParent: $('#add_eggs_modal')
            });

            $('#pNodes').select2({
                placeholder: 'Select nodes..',
                dropdownParent: $('#add_nodes_modal')
            });

            $('button[data-action="detach-egg"]').click(function(event) {
                event.preventDefault();
                const element = $(this);
                const eggId = $(this).data('id');

                swal({
                    title: 'Detach Egg?',
                    text: 'This egg will no longer be able to use this mount.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, detach it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            method: 'DELETE',
                            url: '/admin/mounts/' + {{ $mount->id }} + '/eggs/' + eggId,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                        }).done(function() {
                            element.closest('tr').addClass('bg-error/20').fadeOut(500,
                                function() {
                                    $(this).remove();
                                });
                            swal({
                                type: 'success',
                                title: 'Egg detached.'
                            });
                        }).fail(function(jqXHR) {
                            console.error(jqXHR);
                            swal({
                                title: 'Whoops!',
                                text: jqXHR.responseJSON ? jqXHR.responseJSON
                                    .error : 'An error occurred.',
                                type: 'error'
                            });
                        });
                    }
                });
            });

            $('button[data-action="detach-node"]').click(function(event) {
                event.preventDefault();
                const element = $(this);
                const nodeId = $(this).data('id');

                swal({
                    title: 'Detach Node?',
                    text: 'This node will no longer be able to use this mount.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, detach it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            method: 'DELETE',
                            url: '/admin/mounts/' + {{ $mount->id }} + '/nodes/' +
                                nodeId,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                        }).done(function() {
                            element.closest('tr').addClass('bg-error/20').fadeOut(500,
                                function() {
                                    $(this).remove();
                                });
                            swal({
                                type: 'success',
                                title: 'Node detached.'
                            });
                        }).fail(function(jqXHR) {
                            console.error(jqXHR);
                            swal({
                                title: 'Whoops!',
                                text: jqXHR.responseJSON ? jqXHR.responseJSON
                                    .error : 'An error occurred.',
                                type: 'error'
                            });
                        });
                    }
                });
            });
        });
    </script>
@endsection
