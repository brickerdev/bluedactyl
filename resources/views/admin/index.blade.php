@extends('layouts.admin')

@section('title')
    Administration
@endsection

@section('content-header')
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-4xl font-black tracking-tight">Administrative Overview</h1>
            <p class="text-base-content/60 text-lg">A quick glance at your system status.</p>
        </div>
        <div class="breadcrumbs text-sm bg-base-200 px-4 py-2 rounded-lg border border-base-300">
            <ul>
                <li><a href="{{ route('admin.index') }}" class="opacity-60">Admin</a></li>
                <li class="font-bold">Index</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="flex flex-col gap-8">
        <!-- Hero Section -->
        <div class="hero bg-base-200/50 backdrop-blur-md rounded-3xl border border-base-300 overflow-hidden shadow-xl">
            <div class="hero-content flex-col lg:flex-row p-8 lg:p-12 gap-8 w-full max-w-none justify-start">
                <div class="bg-primary text-primary-content p-6 rounded-2xl shadow-2xl shadow-primary/20 shrink-0">
                    <i class="ri-flashlight-fill text-5xl"></i>
                </div>
                <div class="text-center lg:text-left grow">
                    <h2 class="text-3xl font-black mb-2 tracking-tighter uppercase">Welcome to Bluedactyl</h2>
                    <p class="text-base-content/70 text-lg mb-6 max-w-2xl">You are running version <span class="badge badge-primary badge-outline font-mono font-bold px-3">{{ config('app.version') }}</span>. Your system is healthy and all services are operational.</p>
                    <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
                        <div class="badge badge-soft badge-md font-bold opacity-70">Laravel {{ App::version() }}</div>
                        <div class="badge badge-soft badge-md font-bold opacity-70">PHP {{ phpversion() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
            <div class="card bg-base-100 border border-base-300 shadow-sm hover:shadow-xl transition-all group overflow-hidden">
                <div class="card-body p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 rounded-xl bg-blue-500/10 text-blue-500 group-hover:scale-110 transition-transform">
                            <i class="ri-cpu-line text-2xl"></i>
                        </div>
                        <div class="badge badge-soft badge-info badge-sm font-bold uppercase tracking-tighter">Real-time</div>
                    </div>
                    <div class="text-4xl font-black tracking-tighter mb-1" id="cpu-load">--</div>
                    <div class="text-[10px] font-black opacity-40 uppercase tracking-widest">CPU Usage</div>
                    <progress class="progress progress-info w-full mt-4 h-1.5" value="0" max="100" id="cpu-progress"></progress>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300 shadow-sm hover:shadow-xl transition-all group overflow-hidden">
                <div class="card-body p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 rounded-xl bg-purple-500/10 text-purple-500 group-hover:scale-110 transition-transform">
                            <i class="ri-ram-2-line text-2xl"></i>
                        </div>
                        <div class="badge badge-soft badge-secondary badge-sm font-bold uppercase tracking-tighter">Memory</div>
                    </div>
                    <div class="text-3xl font-black tracking-tighter mb-1 leading-none" id="ram-usage">--</div>
                    <div class="text-[10px] font-black opacity-40 uppercase tracking-widest">Memory Usage</div>
                    <progress class="progress progress-secondary w-full mt-4 h-1.5" value="0" max="100" id="ram-progress"></progress>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300 shadow-sm hover:shadow-xl transition-all group overflow-hidden">
                <div class="card-body p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 rounded-xl bg-emerald-500/10 text-emerald-500 group-hover:scale-110 transition-transform">
                            <i class="ri-hard-drive-2-line text-2xl"></i>
                        </div>
                        <div class="badge badge-soft badge-success badge-sm font-bold uppercase tracking-tighter">Storage</div>
                    </div>
                    <div class="text-3xl font-black tracking-tighter mb-1 leading-none" id="disk-usage">--</div>
                    <div class="text-[10px] font-black opacity-40 uppercase tracking-widest">Disk Space</div>
                    <progress class="progress progress-success w-full mt-4 h-1.5" value="0" max="100" id="disk-progress"></progress>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300 shadow-sm hover:shadow-xl transition-all group overflow-hidden">
                <div class="card-body p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 rounded-xl bg-orange-500/10 text-orange-500 group-hover:scale-110 transition-transform">
                            <i class="ri-time-line text-2xl"></i>
                        </div>
                        <div class="badge badge-soft badge-warning badge-sm font-bold uppercase tracking-tighter">Uptime</div>
                    </div>
                    <div class="text-3xl font-black tracking-tighter mb-1 leading-none" id="uptime">--</div>
                    <div class="text-[10px] font-black opacity-40 uppercase tracking-widest">System Uptime</div>
                    <div class="flex gap-1 mt-6">
                        <div class="h-1 flex-1 bg-orange-500 rounded-full"></div>
                        <div class="h-1 flex-1 bg-orange-500 rounded-full"></div>
                        <div class="h-1 flex-1 bg-orange-500 rounded-full opacity-30"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="https://discord.gg/UhuYKKK2uM" target="_blank" class="btn btn-lg bg-[#5865F2] hover:bg-[#4752C4] text-white border-none shadow-xl group rounded-2xl">
                <i class="ri-discord-fill text-xl group-hover:rotate-12 transition-transform"></i> Discord Support
            </a>
            <a href="https://bluedactyl.dev" target="_blank" class="btn btn-lg btn-primary shadow-xl group rounded-2xl">
                <i class="ri-book-read-line text-xl group-hover:scale-110 transition-transform"></i> Documentation
            </a>
            <a href="https://github.com/brickerium/bluedactyl" target="_blank" class="btn btn-lg btn-neutral shadow-xl group rounded-2xl border border-base-content/10">
                <i class="ri-github-fill text-xl group-hover:rotate-12 transition-transform"></i> GitHub Repo
            </a>
            <a href="{{ $version->getDonations() }}" target="_blank" class="btn btn-lg btn-success shadow-xl group rounded-2xl">
                <i class="ri-heart-fill text-xl group-hover:scale-125 transition-transform text-red-400"></i> Support Project
            </a>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $(document).ready(function() {
            function formatBytes(bytes, decimals = 2) {
                if (!bytes) return '0 B';
                const k = 1024;
                const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return `${parseFloat((bytes / Math.pow(k, i)).toFixed(decimals))} ${sizes[i]}`;
            }

            function formatUptime(seconds) {
                const days = Math.floor(seconds / 86400);
                const hours = Math.floor((seconds % 86400) / 3600);
                const minutes = Math.floor((seconds % 3600) / 60);
                return `${days}d ${hours}h ${minutes}m`;
            }

            function updateSystemMetrics() {
                $.ajax({
                    url: '/api/application/panel/status',
                    method: 'GET',
                    success: function(data) {
                        // CPU
                        const cpu = data.metrics.cpu;
                        $('#cpu-load').text(`${cpu.toFixed(1)}%`);
                        $('#cpu-progress').val(cpu);

                        // RAM
                        const ramUsed = data.metrics.memory.used;
                        const ramTotal = data.metrics.memory.total;
                        const ramPercent = (ramUsed / ramTotal) * 100;
                        $('#ram-usage').html(
                            `${formatBytes(ramUsed)} <span class="text-xs opacity-40 font-normal block mt-1">of ${formatBytes(ramTotal)}</span>`
                        );
                        $('#ram-progress').val(ramPercent);

                        // Disk
                        const diskUsed = data.metrics.disk.used;
                        const diskTotal = data.metrics.disk.total;
                        const diskPercent = (diskUsed / diskTotal) * 100;
                        $('#disk-usage').html(
                            `${formatBytes(diskUsed)} <span class="text-xs opacity-40 font-normal block mt-1">of ${formatBytes(diskTotal)}</span>`
                        );
                        $('#disk-progress').val(diskPercent);

                        // Uptime
                        $('#uptime').text(formatUptime(data.metrics.uptime));
                    },
                    error: function(xhr) {
                        console.error('Failed to fetch system metrics:', xhr.responseText);
                    }
                });
            }

            // Initial update
            updateSystemMetrics();

            // Update every 30 seconds for a more "real-time" feel
            setInterval(updateSystemMetrics, 30000);
        });
    </script>
@endsection
