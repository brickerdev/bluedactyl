@include('partials/admin.settings.notice')

@section('settings::nav')
    @yield('settings::notice')
    <div role="tablist" class="tabs tabs-box bg-base-200/50 p-1 rounded-xl border border-base-300 mb-8">
        <a href="{{ route('admin.settings') }}" role="tab"
            class="tab rounded-lg transition-all {{ $activeTab === 'basic' ? 'tab-active font-bold shadow-sm' : 'opacity-60 hover:opacity-100' }}">General</a>
        <a href="{{ route('admin.settings.mail') }}" role="tab"
            class="tab rounded-lg transition-all {{ $activeTab === 'mail' ? 'tab-active font-bold shadow-sm' : 'opacity-60 hover:opacity-100' }}">Mail</a>
        <a href="{{ route('admin.settings.captcha') }}" role="tab"
            class="tab rounded-lg transition-all {{ $activeTab === 'captcha' ? 'tab-active font-bold shadow-sm' : 'opacity-60 hover:opacity-100' }}">Captcha</a>
        <a href="{{ route('admin.settings.domains.index') }}" role="tab"
            class="tab rounded-lg transition-all {{ $activeTab === 'domains' ? 'tab-active font-bold shadow-sm' : 'opacity-60 hover:opacity-100' }}">Domains</a>
        <a href="{{ route('admin.settings.advanced') }}" role="tab"
            class="tab rounded-lg transition-all {{ $activeTab === 'advanced' ? 'tab-active font-bold shadow-sm' : 'opacity-60 hover:opacity-100' }}">Advanced</a>
    </div>
@endsection
