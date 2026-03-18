@extends('layouts.admin')

@section('title')
    Server — {{ $server->name }}: Startup
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter">{{ $server->name }}</h1>
            <p class="text-base-content/60 text-sm">Control startup command as well as variables.</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.servers') }}">Servers</a></li>
                <li><a href="{{ route('admin.servers.view', $server->id) }}">{{ $server->name }}</a></li>
                <li class="text-primary">Startup</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
@include('admin.servers.partials.navigation')

<form action="{{ route('admin.servers.view.startup', $server->id) }}" method="POST">
    <div class="grid grid-cols-1 gap-8 mb-8">
        <!-- Startup Command Modification -->
        <div class="card bg-base-200/50 shadow-xl backdrop-blur-md border border-base-300">
            <div class="card-body space-y-6">
                <h3 class="text-xl font-bold tracking-tight border-b border-base-300 pb-4">Startup Command Modification</h3>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Startup Command</span>
                    </label>
                    <input id="pStartup" name="startup" class="input input-bordered focus:input-primary transition-all font-mono text-sm" type="text" value="{{ old('startup', $server->startup) }}" />
                    <label class="label">
                        <span class="label-text-alt text-base-content/50">
                            Available variables: <code>@{{SERVER_MEMORY}}</code>, <code>@{{SERVER_IP}}</code>, and <code>@{{SERVER_PORT}}</code>.
                        </span>
                    </label>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Default Service Start Command</span>
                    </label>
                    <input id="pDefaultStartupCommand" class="input input-bordered bg-base-300/50 font-mono text-sm" type="text" readonly />
                </div>

                <div class="pt-4 flex justify-end border-t border-base-300">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-primary px-8">Save Modifications</button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="space-y-8">
            <!-- Service Configuration -->
            <div class="card bg-base-200/50 shadow-xl backdrop-blur-md border border-base-300">
                <div class="card-body space-y-6">
                    <h3 class="text-xl font-bold tracking-tight border-b border-base-300 pb-4">Service Configuration</h3>
                    
                    <div class="alert alert-error alert-soft shadow-inner">
                        <i class="fa fa-exclamation-triangle"></i>
                        <div class="text-xs font-medium">
                            Changing these values will trigger a <strong>re-install</strong>. The server will be stopped immediately.
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Nest</span>
                        </label>
                        <select name="nest_id" id="pNestId" class="select select-bordered focus:select-primary">
                            @foreach($nests as $nest)
                                <option value="{{ $nest->id }}" @if($nest->id === $server->nest_id) selected @endif>{{ $nest->name }}</option>
                            @endforeach
                        </select>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">Select the Nest for this server.</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Egg</span>
                        </label>
                        <select name="egg_id" id="pEggId" class="select select-bordered focus:select-primary"></select>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">Select the Egg that provides processing data.</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label cursor-pointer justify-start gap-4">
                            <input id="pSkipScripting" name="skip_scripts" type="checkbox" value="1" class="checkbox checkbox-primary" @if($server->skip_scripts) checked @endif />
                            <span class="label-text font-bold">Skip Egg Install Script</span>
                        </label>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">If checked, the install script attached to the Egg will not run.</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Docker Image Configuration -->
            <div class="card bg-base-200/50 shadow-xl backdrop-blur-md border border-base-300">
                <div class="card-body space-y-6">
                    <h3 class="text-xl font-bold tracking-tight border-b border-base-300 pb-4">Docker Image Configuration</h3>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Image</span>
                        </label>
                        <select id="pDockerImage" name="docker_image" class="select select-bordered focus:select-primary mb-4"></select>
                        <input id="pDockerImageCustom" name="custom_docker_image" value="{{ old('custom_docker_image') }}" class="input input-bordered focus:input-primary transition-all" placeholder="Or enter a custom image..." />
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">The Docker image used to run this server.</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Variables -->
        <div class="space-y-8" id="appendVariablesTo"></div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    {!! Theme::js('vendor/lodash/lodash.js') !!}
    <style>
        .select2-container--default .select2-selection--single {
            @apply bg-base-100 border-base-300 rounded-lg h-12 flex items-center px-2;
        }
        .select2-dropdown {
            @apply bg-base-200 border-base-300 shadow-2xl rounded-lg overflow-hidden;
        }
    </style>
    <script>
    function escapeHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    $(document).ready(function () {
        $('#pEggId').select2({placeholder: 'Select a Nest Egg'}).on('change', function () {
            var selectedEgg = _.isNull($(this).val()) ? $(this).find('option').first().val() : $(this).val();
            var parentChain = _.get(Bluedactyl.nests, $("#pNestId").val());
            var objectChain = _.get(parentChain, 'eggs.' + selectedEgg);

            const images = _.get(objectChain, 'docker_images', [])
            $('#pDockerImage').html('');
            const keys = Object.keys(images);
            for (let i = 0; i < keys.length; i++) {
                let opt = document.createElement('option');
                opt.value = images[keys[i]];
                opt.innerText = keys[i] + " (" + images[keys[i]] + ")";
                if (objectChain.id === parseInt(Bluedactyl.server.egg_id) && Bluedactyl.server.image == opt.value) {
                    opt.selected = true
                }
                $('#pDockerImage').append(opt);
            }
            $('#pDockerImage').on('change', function () {
                $('#pDockerImageCustom').val('');
            })

            if (objectChain.id === parseInt(Bluedactyl.server.egg_id)) {
                if ($('#pDockerImage').val() != Bluedactyl.server.image) {
                    $('#pDockerImageCustom').val(Bluedactyl.server.image);
                }
            }

            if (!_.get(objectChain, 'startup', false)) {
                $('#pDefaultStartupCommand').val(_.get(parentChain, 'startup', 'ERROR: Startup Not Defined!'));
            } else {
                $('#pDefaultStartupCommand').val(_.get(objectChain, 'startup'));
            }

            $('#appendVariablesTo').html('');
            $.each(_.get(objectChain, 'variables', []), function (i, item) {
                var setValue = _.get(Bluedactyl.server_variables, item.env_variable, item.default_value);
                var isRequired = (item.required === 1) ? '<span class="badge badge-error badge-sm mr-2">Required</span> ' : '';
                var dataAppend = ' \
                    <div class="card bg-base-200/50 shadow-xl backdrop-blur-md border border-base-300"> \
                        <div class="card-body space-y-4"> \
                            <h3 class="text-lg font-bold tracking-tight border-b border-base-300 pb-2 flex items-center">' + isRequired + escapeHtml(item.name) + '</h3> \
                            <div class="form-control"> \
                                <input name="environment[' + escapeHtml(item.env_variable) + ']" class="input input-bordered focus:input-primary transition-all" type="text" id="egg_variable_' + escapeHtml(item.env_variable) + '" /> \
                                <p class="text-xs text-base-content/60 mt-2">' + escapeHtml(item.description) + '</p> \
                            </div> \
                            <div class="pt-4 space-y-1 border-t border-base-300"> \
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-40">Startup Command Variable</p> \
                                <code class="text-xs text-primary font-bold">' + escapeHtml(item.env_variable) + '</code> \
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-40 mt-2">Input Rules</p> \
                                <code class="text-xs text-base-content/60">' + escapeHtml(item.rules) + '</code> \
                            </div> \
                        </div> \
                    </div>';
                $('#appendVariablesTo').append(dataAppend).find('#egg_variable_' + item.env_variable).val(setValue);
            });
        });

        $('#pNestId').select2({placeholder: 'Select a Nest'}).on('change', function () {
            $('#pEggId').html('').select2({
                data: $.map(_.get(Bluedactyl.nests, $(this).val() + '.eggs', []), function (item) {
                    return {
                        id: item.id,
                        text: item.name,
                    };
                }),
            });

            if (_.isObject(_.get(Bluedactyl.nests, $(this).val() + '.eggs.' + Bluedactyl.server.egg_id))) {
                $('#pEggId').val(Bluedactyl.server.egg_id);
            }

            $('#pEggId').change();
        }).change();
    });
    </script>
@endsection
