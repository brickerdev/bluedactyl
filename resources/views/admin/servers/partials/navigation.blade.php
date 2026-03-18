@php
    /** @var \Pterodactyl\Models\Server $server */
    $router = app('router');
@endphp

<div class="mb-8 overflow-x-auto">
    <div class="tabs tabs-box bg-base-200/50 p-1 rounded-xl inline-flex border border-base-300 whitespace-nowrap">
        <a href="{{ route('admin.servers.view', $server->id) }}"
            class="tab !rounded-lg {{ $router->currentRouteNamed('admin.servers.view') ? 'tab-active font-bold' : '' }}">
            About
        </a>

        @if ($server->isInstalled())
            <a href="{{ route('admin.servers.view.details', $server->id) }}"
                class="tab !rounded-lg {{ $router->currentRouteNamed('admin.servers.view.details') ? 'tab-active font-bold' : '' }}">
                Details
            </a>
            <a href="{{ route('admin.servers.view.build', $server->id) }}"
                class="tab !rounded-lg {{ $router->currentRouteNamed('admin.servers.view.build') ? 'tab-active font-bold' : '' }}">
                Build
            </a>
            <a href="{{ route('admin.servers.view.startup', $server->id) }}"
                class="tab !rounded-lg {{ $router->currentRouteNamed('admin.servers.view.startup') ? 'tab-active font-bold' : '' }}">
                Startup
            </a>
            <a href="{{ route('admin.servers.view.database', $server->id) }}"
                class="tab !rounded-lg {{ $router->currentRouteNamed('admin.servers.view.database') ? 'tab-active font-bold' : '' }}">
                Database
            </a>
            <a href="{{ route('admin.servers.view.mounts', $server->id) }}"
                class="tab !rounded-lg {{ $router->currentRouteNamed('admin.servers.view.mounts') ? 'tab-active font-bold' : '' }}">
                Mounts
            </a>
        @endif

        <a href="{{ route('admin.servers.view.manage', $server->id) }}"
            class="tab !rounded-lg {{ $router->currentRouteNamed('admin.servers.view.manage') ? 'tab-active font-bold' : '' }}">
            Manage
        </a>

        <a href="{{ route('admin.servers.view.delete', $server->id) }}"
            class="tab !rounded-lg {{ $router->currentRouteNamed('admin.servers.view.delete') ? 'tab-active font-bold text-error' : '' }}">
            Delete
        </a>

        <div class="divider divider-horizontal mx-1 opacity-20"></div>

        <a href="/server/{{ $server->uuidShort }}" target="_blank"
            class="tab !rounded-lg hover:text-primary transition-colors tooltip tooltip-bottom"
            data-tip="Open Client Panel">
            <i class="fa fa-external-link"></i>
        </a>
    </div>
</div>
