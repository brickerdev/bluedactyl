@extends('layouts.admin')

@section('title')
    {{ $node->name }}: Allocations
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-base-content uppercase">{{ $node->name }}</h1>
            <p class="text-base-content/60 text-sm font-medium">Control allocations available for servers on this node.</p>
        </div>
        <div class="text-sm breadcrumbs text-base-content/60 font-medium">
            <ul>
                <li><a href="{{ route('admin.index') }}" class="hover:text-primary transition-colors">Admin</a></li>
                <li><a href="{{ route('admin.nodes') }}" class="hover:text-primary transition-colors">Nodes</a></li>
                <li><a href="{{ route('admin.nodes.view', $node->id) }}" class="hover:text-primary transition-colors">{{ $node->name }}</a></li>
                <li class="text-base-content">Allocations</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="mb-8">
        <div class="tabs tabs-box bg-base-200/50 p-1 rounded-xl inline-flex border border-base-300 whitespace-nowrap overflow-x-auto max-w-full">
            <a href="{{ route('admin.nodes.view', $node->id) }}" class="tab !rounded-lg">About</a>
            <a href="{{ route('admin.nodes.view.settings', $node->id) }}" class="tab !rounded-lg">Settings</a>
            <a href="{{ route('admin.nodes.view.configuration', $node->id) }}" class="tab !rounded-lg">Configuration</a>
            <a href="{{ route('admin.nodes.view.allocation', $node->id) }}" class="tab tab-active !rounded-lg font-bold">Allocation</a>
            <a href="{{ route('admin.nodes.view.servers', $node->id) }}" class="tab !rounded-lg">Servers</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="card bg-base-200/50 shadow-sm backdrop-blur-md border border-base-300">
                <div class="card-body p-0">
                    <div class="p-6 border-b border-base-300 flex items-center justify-between">
                        <h3 class="text-xl font-black tracking-tighter text-base-content uppercase">Existing Allocations</h3>
                        <div class="flex gap-2">
                            <div class="dropdown dropdown-end">
                                <button type="button" id="mass_actions" class="btn btn-sm btn-ghost btn-disabled font-bold uppercase tracking-wider" tabindex="0">
                                    Mass Actions <i class="fa fa-chevron-down ml-2 text-[10px]"></i>
                                </button>
                                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow-2xl bg-base-200 rounded-xl w-52 border border-base-300 mt-2">
                                    <li><a href="#" id="selective-deletion" class="text-error font-bold uppercase text-xs tracking-widest py-3"><i class="fa fa-trash-o"></i> Delete Selected</a></li>
                                </ul>
                            </div>
                            <button class="btn btn-sm btn-error btn-outline font-bold uppercase tracking-wider" onclick="allocationModal.showModal()">
                                <i class="fa fa-minus-square"></i> <span class="hidden sm:inline">Delete Block</span>
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full" id="allocation_table">
                            <thead>
                                <tr class="text-base-content/50 uppercase text-[10px] tracking-[0.15em] border-b border-base-300">
                                    <th class="w-12 pl-6">
                                        <input type="checkbox" class="checkbox checkbox-sm checkbox-primary" data-action="selectAll">
                                    </th>
                                    <th>IP Address</th>
                                    <th>IP Alias</th>
                                    <th>Port</th>
                                    <th>Assigned To</th>
                                    <th class="text-right pr-6">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($node->allocations as $allocation)
                                    <tr class="hover:bg-base-300/30 transition-colors group">
                                        <td class="pl-6">
                                            @if(is_null($allocation->server_id))
                                                <input type="checkbox" class="checkbox checkbox-sm select-file checkbox-primary" data-action="addSelection">
                                            @else
                                                <input disabled="disabled" type="checkbox" class="checkbox checkbox-sm opacity-10">
                                            @endif
                                        </td>
                                        <td class="font-mono text-xs font-bold text-base-content/70" data-identifier="ip">{{ $allocation->ip }}</td>
                                        <td class="relative">
                                            <input class="input input-xs input-bordered w-full focus:input-primary transition-all bg-transparent border-transparent group-hover:border-base-300 font-medium text-xs" 
                                                   type="text" value="{{ $allocation->ip_alias }}" data-action="set-alias" data-id="{{ $allocation->id }}" placeholder="none" />
                                            <span class="input-loader absolute right-3 top-1/2 -translate-y-1/2 hidden"><span class="loading loading-spinner loading-xs text-primary"></span></span>
                                        </td>
                                        <td class="font-mono text-xs font-bold text-primary" data-identifier="port">{{ $allocation->port }}</td>
                                        <td>
                                            @if(! is_null($allocation->server))
                                                <a href="{{ route('admin.servers.view', $allocation->server_id) }}" class="link link-primary font-bold text-xs tracking-tight uppercase">{{ $allocation->server->name }}</a>
                                            @else
                                                <span class="badge badge-soft badge-ghost font-black uppercase text-[10px] tracking-widest opacity-40">Unassigned</span>
                                            @endif
                                        </td>
                                        <td class="text-right pr-6">
                                            @if(is_null($allocation->server_id))
                                                <button data-action="deallocate" data-id="{{ $allocation->id }}" class="btn btn-ghost btn-square btn-xs text-error hover:bg-error/10 tooltip" data-tip="Delete Allocation">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($node->allocations->hasPages())
                        <div class="p-6 border-t border-base-300 flex justify-center">
                            <div class="join">
                                {{ $node->allocations->links('vendor.pagination.daisyui') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <form action="{{ route('admin.nodes.view.allocation', $node->id) }}" method="POST">
                <div class="card bg-success/5 border border-success/20 shadow-sm sticky top-8">
                    <div class="card-body p-6 space-y-6">
                        <h3 class="text-xl font-black text-success tracking-tighter uppercase flex items-center gap-2">
                            <i class="fa fa-plus-circle"></i> Assign New
                        </h3>
                        
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">IP Address</span>
                            </label>
                            <select class="select2-daisy w-full" name="allocation_ip" id="pAllocationIP" multiple>
                                @foreach($allocations as $allocation)
                                    <option value="{{ $allocation->ip }}">{{ $allocation->ip }}</option>
                                @endforeach
                            </select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/40 italic">Enter an IP address to assign ports to.</span>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">IP Alias</span>
                            </label>
                            <input type="text" id="pAllocationAlias" class="input input-bordered focus:input-success transition-all bg-base-100" name="allocation_alias" placeholder="alias" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/40 italic">Optional default alias for these allocations.</span>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Ports</span>
                            </label>
                            <select class="select2-daisy w-full" name="allocation_ports[]" id="pAllocationPorts" multiple></select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/40 italic">Individual ports or ranges (e.g. 25565, 25570-25580).</span>
                            </label>
                        </div>

                        <div class="card-actions mt-4">
                            {!! csrf_field() !!}
                            <button type="submit" class="btn btn-success btn-block font-bold uppercase tracking-wider shadow-lg shadow-success/20">Submit Allocations</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Block Modal -->
    <dialog id="allocationModal" class="modal">
        <div class="modal-box bg-base-200 border border-base-300 shadow-2xl max-w-md">
            <h3 class="font-black text-2xl tracking-tighter mb-4 text-error uppercase">Delete IP Block</h3>
            <p class="text-sm text-base-content/70 mb-6 italic leading-relaxed">This will remove ALL allocations for the selected IP address that are not currently assigned to a server.</p>
            <form action="{{ route('admin.nodes.view.allocation.removeBlock', $node->id) }}" method="POST">
                {!! csrf_field() !!}
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Select IP Address</span>
                    </label>
                    <select class="select select-bordered w-full bg-base-100" name="ip">
                        @foreach($allocations as $allocation)
                            <option value="{{ $allocation->ip }}">{{ $allocation->ip }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-action mt-8">
                    <button type="button" class="btn btn-ghost font-bold uppercase tracking-wider" onclick="allocationModal.close()">Cancel</button>
                    <button type="submit" class="btn btn-error px-8 font-bold uppercase tracking-wider">Delete Allocations</button>
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
            @apply bg-base-100 border-base-300 rounded-lg min-h-[3rem] p-1;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            @apply border-success ring-1 ring-success;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            @apply bg-success text-success-content border-none rounded px-2 py-0.5 mt-1 font-bold uppercase text-[10px] tracking-wider;
        }
        .select2-dropdown {
            @apply bg-base-200 border-base-300 shadow-xl rounded-xl overflow-hidden;
        }
        .select2-results__option--highlighted[aria-selected] {
            @apply bg-success text-success-content;
        }
    </style>
    <script>
    $(document).ready(function() {
        $('#pAllocationIP').select2({
            tags: true,
            maximumSelectionLength: 1,
            selectOnClose: true,
            tokenSeparators: [',', ' '],
        });

        $('#pAllocationPorts').select2({
            tags: true,
            selectOnClose: true,
            tokenSeparators: [',', ' '],
        });

        $('[data-action="addSelection"]').on('click', function () {
            updateMassActions();
        });

        $('[data-action="selectAll"]').on('click', function () {
            const checked = $(this).prop('checked');
            $('input.select-file').not(':disabled').prop('checked', checked);
            updateMassActions();
        });

        $('[data-action="selective-deletion"]').on('mousedown', function (e) {
            e.preventDefault();
            deleteSelected();
        });

        $('button[data-action="deallocate"]').click(function (event) {
            event.preventDefault();
            var element = $(this);
            var allocation = $(this).data('id');
            swal({
                title: 'Delete Allocation?',
                text: 'Are you sure you want to delete this port?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                confirmButtonColor: '#d33',
                showLoaderOnConfirm: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        method: 'DELETE',
                        url: '/admin/nodes/view/' + {{ $node->id }} + '/allocation/remove/' + allocation,
                        headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                    }).done(function (data) {
                        element.closest('tr').addClass('bg-error/20').fadeOut(500, function() {
                            $(this).remove();
                        });
                        swal({ type: 'success', title: 'Port Deleted!' });
                    }).fail(function (jqXHR) {
                        swal({
                            title: 'Whoops!',
                            text: jqXHR.responseJSON ? jqXHR.responseJSON.error : 'An error occurred.',
                            type: 'error'
                        });
                    });
                }
            });
        });

        var typingTimer;
        $('input[data-action="set-alias"]').keyup(function () {
            clearTimeout(typingTimer);
            $(this).removeClass('input-error input-success');
            typingTimer = setTimeout(sendAlias, 500, $(this));
        });

        function sendAlias(element) {
            element.parent().find('.input-loader').show();
            $.ajax({
                method: 'POST',
                url: '/admin/nodes/view/' + {{ $node->id }} + '/allocation/alias',
                headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                data: {
                    alias: element.val(),
                    allocation_id: element.data('id'),
                }
            }).done(function () {
                element.addClass('input-success');
            }).fail(function (jqXHR) {
                element.addClass('input-error');
            }).always(function () {
                element.parent().find('.input-loader').hide();
                setTimeout(() => element.removeClass('input-error input-success'), 2000);
            });
        }

        function updateMassActions() {
            const count = $('input.select-file:checked').length;
            if (count > 0) {
                $('#mass_actions').removeClass('btn-disabled');
            } else {
                $('#mass_actions').addClass('btn-disabled');
            }
        }

        function deleteSelected() {
            var selectedIds = [];
            var selectedItems = [];
            var selectedItemsElements = [];

            $('input.select-file:checked').each(function () {
                var $parent = $($(this).closest('tr'));
                var id = $parent.find('[data-action="deallocate"]').data('id');
                var $ip = $parent.find('td[data-identifier="ip"]');
                var $port = $parent.find('td[data-identifier="port"]');
                var block = `${$ip.text()}:${$port.text()}`;

                selectedIds.push({ id: id });
                selectedItems.push(block);
                selectedItemsElements.push($parent);
            });

            if (selectedItems.length !== 0) {
                var formattedItems = selectedItems.slice(0, 5).map(i => `<code>${i}</code>`).join(', ');
                if (selectedItems.length > 5) {
                    formattedItems += ', and ' + (selectedItems.length - 5) + ' other(s)';
                }

                swal({
                    type: 'warning',
                    title: 'Delete Allocations?',
                    text: 'Are you sure you want to delete: ' + formattedItems + '?',
                    html: true,
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete',
                    confirmButtonColor: '#d33',
                    showLoaderOnConfirm: true
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            method: 'DELETE',
                            url: '/admin/nodes/view/' + {{ $node->id }} + '/allocations',
                            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
                            data: JSON.stringify({ allocations: selectedIds }),
                            contentType: 'application/json',
                            processData: false
                        }).done(function () {
                            $.each(selectedItemsElements, function () {
                                $(this).addClass('bg-error/20').fadeOut(500, function() { $(this).remove(); });
                            });
                            swal({ type: 'success', title: 'Allocations Deleted' });
                            updateMassActions();
                        }).fail(function (jqXHR) {
                            swal({
                                type: 'error',
                                title: 'Whoops!',
                                text: 'An error occurred while attempting to delete these allocations.',
                            });
                        });
                    }
                });
            }
        }
    });
    </script>
@endsection
