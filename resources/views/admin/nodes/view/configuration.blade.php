@extends('layouts.admin')

@section('title')
    {{ $node->name }}: Configuration
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-base-content uppercase">{{ $node->name }}</h1>
            <p class="text-base-content/60 text-sm font-medium">Your daemon configuration file.</p>
        </div>
        <div class="text-sm breadcrumbs text-base-content/60 font-medium">
            <ul>
                <li><a href="{{ route('admin.index') }}" class="hover:text-primary transition-colors">Admin</a></li>
                <li><a href="{{ route('admin.nodes') }}" class="hover:text-primary transition-colors">Nodes</a></li>
                <li><a href="{{ route('admin.nodes.view', $node->id) }}"
                        class="hover:text-primary transition-colors">{{ $node->name }}</a></li>
                <li class="text-base-content">Configuration</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="mb-8">
        <div
            class="tabs tabs-box bg-base-200/50 p-1 rounded-xl inline-flex border border-base-300 whitespace-nowrap overflow-x-auto max-w-full">
            <a href="{{ route('admin.nodes.view', $node->id) }}" class="tab !rounded-lg">About</a>
            <a href="{{ route('admin.nodes.view.settings', $node->id) }}" class="tab !rounded-lg">Settings</a>
            <a href="{{ route('admin.nodes.view.configuration', $node->id) }}"
                class="tab tab-active !rounded-lg font-bold">Configuration</a>
            <a href="{{ route('admin.nodes.view.allocation', $node->id) }}" class="tab !rounded-lg">Allocation</a>
            <a href="{{ route('admin.nodes.view.servers', $node->id) }}" class="tab !rounded-lg">Servers</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="card bg-base-200/50 shadow-sm backdrop-blur-md border border-base-300">
                <div class="card-body p-0">
                    <div class="p-6 border-b border-base-300 flex items-center justify-between">
                        <h3 class="text-xl font-black tracking-tighter text-base-content uppercase">Configuration File</h3>
                        <button class="btn btn-ghost btn-sm font-bold uppercase tracking-wider" onclick="copyConfig()">
                            <i class="fa fa-copy mr-2"></i> Copy
                        </button>
                    </div>
                    <div class="p-6">
                        <div
                            class="mockup-code bg-base-300 text-base-content border border-base-300 shadow-inner rounded-2xl">
                            <pre id="configBlock" class="px-6 font-mono text-xs leading-relaxed"><code>{{ $node->getYamlConfiguration() }}</code></pre>
                        </div>
                        <div class="mt-6 p-4 bg-info/5 border border-info/20 rounded-xl flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-info/10 flex items-center justify-center text-info shrink-0">
                                <i class="fa fa-info-circle"></i>
                            </div>
                            <p class="text-xs text-base-content/70 italic leading-relaxed">
                                This file should be placed in your daemon's root directory (usually
                                <code class="badge badge-soft badge-ghost font-mono text-[10px]">/etc/elytra</code>) in a
                                file called <code
                                    class="badge badge-soft badge-ghost font-mono text-[10px]">config.yml</code>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card bg-success/5 border border-success/20 shadow-sm overflow-hidden">
                <div class="card-body p-6">
                    <h3 class="text-xl font-black text-success tracking-tighter uppercase flex items-center gap-2">
                        <i class="fa fa-rocket"></i> Auto-Deploy
                    </h3>
                    <p class="text-base-content/70 text-sm mt-4 italic leading-relaxed">
                        Use the button below to generate a custom deployment command that can be used to configure
                        elytra on the target server with a single command.
                    </p>
                    <div class="card-actions mt-8">
                        <button type="button" id="configTokenBtn"
                            class="btn btn-success btn-block font-bold uppercase tracking-wider shadow-lg shadow-success/20">
                            Generate Token
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        function copyConfig() {
            const text = document.getElementById('configBlock').innerText;
            navigator.clipboard.writeText(text).then(() => {
                swal({
                    title: 'Copied!',
                    text: 'Configuration copied to clipboard.',
                    type: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        }

        $('#configTokenBtn').on('click', function(event) {
            const btn = $(this);
            btn.addClass('loading');

            $.ajax({
                method: 'POST',
                url: '{{ route('admin.nodes.view.configuration.token', $node->id) }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            }).done(function(data) {
                var commandTemplate = "{!! addslashes($node->getAutoDeploy('PLACEHOLDER_TOKEN')) !!}";
                var command = commandTemplate.replace('PLACEHOLDER_TOKEN', data.token);

                swal({
                    type: 'success',
                    title: 'Token created.',
                    text: '<div class="text-left mt-4"><p class="mb-4 text-xs font-black uppercase tracking-widest text-base-content/50">To auto-configure your node run the following command:</p><div class="mockup-code bg-base-300 text-[10px] rounded-xl border border-base-300"><pre class="px-4"><code>' +
                        command + '</code></pre></div></div>',
                    html: true,
                });
            }).fail(function() {
                swal({
                    title: 'Error',
                    text: 'Something went wrong creating your token.',
                    type: 'error'
                });
            }).always(function() {
                btn.removeClass('loading');
            });
        });
    </script>
@endsection
