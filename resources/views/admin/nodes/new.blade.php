@extends('layouts.admin')

@section('title')
    Nodes &rarr; New
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-4xl font-black tracking-tighter">New Node</h1>
            <p class="text-base-content/60">Create a new local or remote node for servers to be installed to.</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.nodes') }}">Nodes</a></li>
                <li class="text-primary">New</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.nodes.new') }}" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Basic Details --}}
            <div class="bg-base-200/50 rounded-box p-6 border border-base-300">
                <div class="flex items-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 text-primary">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                    <h2 class="text-2xl font-black tracking-tight">Basic Details</h2>
                </div>

                <div class="space-y-4">
                    <div class="form-control w-full">
                        <label for="pName" class="label"><span class="label-text font-bold">Name</span></label>
                        <input type="text" name="name" id="pName" class="input input-bordered w-full"
                            value="{{ old('name') }}" />
                        <label class="label">
                            <span class="label-text-alt text-base-content/60">Limits: <code>a-zA-Z0-9_.-</code> and
                                <code>[Space]</code> (1-100 chars).</span>
                        </label>
                    </div>

                    <div class="form-control w-full">
                        <label for="pDescription" class="label"><span
                                class="label-text font-bold">Description</span></label>
                        <textarea name="description" id="pDescription" rows="3" class="textarea textarea-bordered w-full">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control w-full">
                            <label for="pLocationId" class="label"><span
                                    class="label-text font-bold">Location</span></label>
                            <select name="location_id" id="pLocationId" class="select select-bordered w-full">
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ $location->id != old('location_id') ?: 'selected' }}>{{ $location->short }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control w-full">
                            <label for="pDaemonType" class="label"><span class="label-text font-bold">Daemon</span></label>
                            <select name="daemonType" id="pDaemonType" class="select select-bordered w-full">
                                @foreach ($daemonTypes as $daemon => $label)
                                    <option value="{{ $daemon }}"
                                        {{ $daemon == old('daemon_type', 'wings') ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label for="pBackupDisk" class="label"><span class="label-text font-bold">Backup
                                Disk</span></label>
                        <select name="backupDisk" id="pBackupDisk" class="select select-bordered w-full">
                            <!-- Populated via Script-->
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Node Visibility</span></label>
                        <div class="join">
                            <input class="join-item btn btn-sm px-6" type="radio" name="public" id="pPublicTrue"
                                value="1" aria-label="Public" checked />
                            <input class="join-item btn btn-sm px-6" type="radio" name="public" id="pPublicFalse"
                                value="0" aria-label="Private" />
                        </div>
                        <label class="label">
                            <span class="label-text-alt text-base-content/60">Private nodes deny auto-deployment.</span>
                        </label>
                    </div>

                    <div class="form-control w-full">
                        <label for="pFQDN" class="label"><span class="label-text font-bold">Public FQDN</span></label>
                        <input type="text" name="fqdn" id="pFQDN" class="input input-bordered w-full"
                            value="{{ old('fqdn') }}" placeholder="node.example.com" />
                        <label class="label">
                            <span class="label-text-alt text-base-content/60">Domain name for browser connections. IP only
                                if no SSL.</span>
                        </label>
                    </div>

                    <div class="form-control w-full">
                        <label for="pInternalFQDN" class="label">
                            <span class="label-text font-bold">Internal FQDN <span
                                    class="badge badge-ghost badge-sm">Optional</span></span>
                        </label>
                        <input type="text" name="internal_fqdn" id="pInternalFQDN"
                            class="input input-bordered w-full" value="{{ old('internal_fqdn') }}" />
                        <label class="label">
                            <span class="label-text-alt text-base-content/60">Used for panel-to-node communication if
                                specified.</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">SSL Connection</span></label>
                        <div class="join">
                            <input class="join-item btn btn-sm px-6" type="radio" name="scheme" id="pSSLTrue"
                                value="https" aria-label="Use SSL" checked />
                            <input class="join-item btn btn-sm px-6" type="radio" name="scheme" id="pSSLFalse"
                                value="http" aria-label="Use HTTP" @if (request()->isSecure()) disabled @endif />
                        </div>
                        @if (request()->isSecure())
                            <label class="label"><span class="label-text-alt text-error font-bold italic">Panel is
                                    secure; Node MUST use SSL.</span></label>
                        @endif
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Behind Proxy</span></label>
                        <div class="join">
                            <input class="join-item btn btn-sm px-6" type="radio" name="behind_proxy" id="pProxyFalse"
                                value="0" aria-label="No Proxy" checked />
                            <input class="join-item btn btn-sm px-6" type="radio" name="behind_proxy" id="pProxyTrue"
                                value="1" aria-label="Behind Proxy" />
                        </div>
                        <label class="label">
                            <span class="label-text-alt text-base-content/60">Skip certificate checks on boot (e.g.
                                Cloudflare).</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Configuration --}}
            <div class="bg-base-200/50 rounded-box p-6 border border-base-300 h-fit">
                <div class="flex items-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 text-primary">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.964m11.49-9.642l1.149-.964M7.501 19.795l.75-1.3m7.5-12.99l.75-1.3m-6.06 15.633l.191-1.487m9.111-12.34l.192-1.487M10.5 20.988V19.5m3-15V3.012m-3.71 18.576l-.191-1.487M3.038 15.309l1.487-.191m15.013-1.932l1.487-.191M3.012 10.5H4.5m15 3H21m-18.576 3.71l1.487.191m12.34-9.111l1.487.191m-15.633 6.06l1.3-.75m12.99-7.5l1.3-.75m-15.633 6.06l.964-1.15m9.642-11.49l.964-1.15m-12.34 14.095l.513-1.41m5.13-14.095l.513-1.41" />
                    </svg>
                    <h2 class="text-2xl font-black tracking-tight">Configuration</h2>
                </div>

                <div class="space-y-6">
                    <div class="form-control w-full">
                        <label for="pDaemonBase" class="label"><span class="label-text font-bold">Server File
                                Directory</span></label>
                        <input type="text" name="daemonBase" id="pDaemonBase" class="input input-bordered w-full"
                            value="/var/lib/elytra/volumes" />
                        <label class="label">
                            <span class="label-text-alt text-base-content/60 italic">OVH users: check partitions (e.g.
                                <code>/home/daemon-data</code>).</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control w-full">
                            <label for="pMemory" class="label"><span class="label-text font-bold">Total
                                    Memory</span></label>
                            <div class="join w-full">
                                <input type="text" name="memory" data-multiplicator="true"
                                    class="input input-bordered join-item w-full" id="pMemory"
                                    value="{{ old('memory') }}" />
                                <span class="btn btn-disabled join-item">MiB</span>
                            </div>
                        </div>
                        <div class="form-control w-full">
                            <label for="pMemoryOverallocate" class="label"><span
                                    class="label-text font-bold">Over-Allocation</span></label>
                            <div class="join w-full">
                                <input type="text" name="memory_overallocate"
                                    class="input input-bordered join-item w-full" id="pMemoryOverallocate"
                                    value="{{ old('memory_overallocate') }}" />
                                <span class="btn btn-disabled join-item">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control w-full">
                            <label for="pDisk" class="label"><span class="label-text font-bold">Total Disk
                                    Space</span></label>
                            <div class="join w-full">
                                <input type="text" name="disk" data-multiplicator="true"
                                    class="input input-bordered join-item w-full" id="pDisk"
                                    value="{{ old('disk') }}" />
                                <span class="btn btn-disabled join-item">MiB</span>
                            </div>
                        </div>
                        <div class="form-control w-full">
                            <label for="pDiskOverallocate" class="label"><span
                                    class="label-text font-bold">Over-Allocation</span></label>
                            <div class="join w-full">
                                <input type="text" name="disk_overallocate"
                                    class="input input-bordered join-item w-full" id="pDiskOverallocate"
                                    value="{{ old('disk_overallocate') }}" />
                                <span class="btn btn-disabled join-item">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info alert-soft text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            class="stroke-current shrink-0 w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Use <code>-1</code> to disable checking, <code>0</code> to prevent creating if over
                            limit.</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control w-full">
                            <label for="pDaemonListen" class="label"><span class="label-text font-bold">Daemon
                                    Port</span></label>
                            <input type="text" name="daemonListen" class="input input-bordered w-full"
                                id="pDaemonListen" value="8080" />
                        </div>
                        <div class="form-control w-full">
                            <label for="pDaemonSFTP" class="label"><span class="label-text font-bold">SFTP
                                    Port</span></label>
                            <input type="text" name="daemonSFTP" class="input input-bordered w-full" id="pDaemonSFTP"
                                value="2022" />
                        </div>
                    </div>

                    <div class="alert alert-warning alert-soft text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-4 w-4" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Do NOT use the same port as the physical server's SSH. Use <code>8443</code> for
                            Cloudflare.</span>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    {!! csrf_field() !!}
                    <a href="{{ route('admin.nodes') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary px-8">Create Node</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $(document).ready(function() {
            const daemonSelect = document.getElementById('pDaemonType');
            const backupDiskSelect = document.getElementById('pBackupDisk');

            function updateBackupDisks() {
                const daemonValue = daemonSelect.value;
                const disks = {!! json_encode($backupDisks ?? []) !!}[daemonValue] || [];

                backupDiskSelect.innerHTML = '';

                disks.forEach(disk => {
                    const option = document.createElement('option');
                    option.value = disk;
                    option.textContent = disk;
                    backupDiskSelect.appendChild(option);
                });
            }

            updateBackupDisks();
            daemonSelect.addEventListener('change', updateBackupDisks);
        });
    </script>
@endsection
