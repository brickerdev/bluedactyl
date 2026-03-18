@extends('layouts.admin')
@include('partials/admin.settings.nav', ['activeTab' => 'domains'])

@section('title')
    Create Domain
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-4xl font-black tracking-tighter uppercase">Create Domain</h1>
            <p class="text-base-content/60 text-sm">Add a new DNS domain for subdomain management.</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.settings') }}">Settings</a></li>
                <li><a href="{{ route('admin.settings.domains.index') }}">Domains</a></li>
                <li class="text-primary font-bold">Create</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    @yield('settings::nav')

    <form action="{{ route('admin.settings.domains.store') }}" method="POST" id="domain-form">
        <div class="grid grid-cols-1 gap-8">
            {{-- Domain Information --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <i class="ri-global-line text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold uppercase tracking-tight">Domain Information</h3>
                            <p class="text-xs text-base-content/50 italic">Basic identification for the new domain.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="form-control w-full">
                            <label for="name" class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Domain Name</span>
                            </label>
                            <input type="text" name="name" id="name" class="input input-bordered focus:input-primary w-full transition-all" value="{{ old('name') }}"
                                placeholder="example.com" required />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">The domain name that will be used for subdomains.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label for="dns_provider" class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">DNS Provider</span>
                            </label>
                            <select name="dns_provider" id="dns_provider" class="select select-bordered focus:select-primary w-full transition-all" required>
                                <option value="">Select a DNS provider...</option>
                                @foreach($providers as $key => $provider)
                                    <option value="{{ $key }}" @if(old('dns_provider') === $key) selected @endif>
                                        {{ $provider['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">The DNS service provider that manages this domain.</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DNS Provider Configuration (Dynamic) --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md" id="dns-config-box" style="display: none;">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <i class="ri-settings-5-line text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold uppercase tracking-tight">DNS Provider Configuration</h3>
                            <p class="text-xs text-base-content/50 italic">Specific credentials and settings for the chosen provider.</p>
                        </div>
                    </div>
                    <div id="dns-config-content">
                        <!-- Dynamic content will be loaded here -->
                    </div>
                </div>
            </div>

            {{-- Additional Settings --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <i class="ri-equalizer-line text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold uppercase tracking-tight">Additional Settings</h3>
                            <p class="text-xs text-base-content/50 italic">Configure the availability and default status of this domain.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Status</span>
                            </label>
                            <div class="join w-full border border-base-300 rounded-xl overflow-hidden bg-base-300/30">
                                <input class="join-item btn btn-sm flex-1 font-bold uppercase tracking-wider" type="radio" name="is_active" value="1" aria-label="Active" @if(old('is_active', true)) checked @endif />
                                <input class="join-item btn btn-sm flex-1 font-bold uppercase tracking-wider" type="radio" name="is_active" value="0" aria-label="Inactive" @if(!old('is_active', true)) checked @endif />
                            </div>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Whether this domain should be available for subdomain creation.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Default Domain</span>
                            </label>
                            <div class="join w-full border border-base-300 rounded-xl overflow-hidden bg-base-300/30">
                                <input class="join-item btn btn-sm flex-1 font-bold uppercase tracking-wider" type="radio" name="is_default" value="1" aria-label="Yes" @if(old('is_default', false)) checked @endif />
                                <input class="join-item btn btn-sm flex-1 font-bold uppercase tracking-wider" type="radio" name="is_default" value="0" aria-label="No" @if(!old('is_default', false)) checked @endif />
                            </div>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Whether this domain should be used as the default for automatic generation.</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mt-4">
                <button type="button" id="test-connection" class="btn btn-outline btn-info px-8 font-bold uppercase tracking-wider" disabled>
                    <span class="loading loading-spinner loading-xs hidden" id="test-spinner"></span>
                    <i class="ri-plug-line mr-1"></i>
                    Test Connection
                </button>
                <div class="flex gap-3">
                    {!! csrf_field() !!}
                    <a href="{{ route('admin.settings.domains.index') }}" class="btn btn-ghost font-bold uppercase tracking-wider">Cancel</a>
                    <button type="submit" class="btn btn-primary px-12 font-bold uppercase tracking-wider shadow-lg shadow-primary/20">
                        <i class="ri-add-line mr-1"></i>
                        Create Domain
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $(document).ready(function () {
            const $providerSelect = $('#dns_provider');
            const $configBox = $('#dns-config-box');
            const $configContent = $('#dns-config-content');
            const $testButton = $('#test-connection');
            const $testSpinner = $('#test-spinner');

            // Handle provider selection
            $providerSelect.change(function () {
                const provider = $(this).val();

                if (provider) {
                    loadProviderConfig(provider);
                    $testButton.prop('disabled', false);
                } else {
                    $configBox.hide();
                    $testButton.prop('disabled', true);
                }
            });

            // Test connection
            $testButton.click(function () {
                const formData = {
                    dns_provider: $providerSelect.val(),
                    dns_config: {}
                };

                // Collect DNS config fields
                $configContent.find('input').each(function () {
                    const name = $(this).attr('name');
                    if (name && name.startsWith('dns_config[')) {
                        const key = name.replace('dns_config[', '').replace(']', '');
                        formData.dns_config[key] = $(this).val();
                    }
                });

                $testButton.prop('disabled', true);
                $testSpinner.removeClass('hidden');

                $.post('{{ route('admin.settings.domains.test-connection') }}', {
                    _token: '{{ csrf_token() }}',
                    ...formData
                })
                .done(function (response) {
                    if (response.success) {
                        swal({ type: 'success', title: 'Connection Successful', text: response.message });
                    } else {
                        swal({ type: 'error', title: 'Connection Failed', text: response.message });
                    }
                })
                .fail(function (xhr) {
                    const response = xhr.responseJSON || {};
                    swal({ type: 'error', title: 'Connection Failed', text: response.message || 'An unexpected error occurred.' });
                })
                .always(function () {
                    $testButton.prop('disabled', false);
                    $testSpinner.addClass('hidden');
                });
            });

            // Load provider configuration
            function loadProviderConfig(provider) {
                $.get(`{{ route('admin.settings.domains.provider-schema', ':provider') }}`.replace(':provider', provider))
                .done(function (response) {
                    if (response.success) {
                        renderConfigForm(response.schema);
                        $configBox.show();
                    }
                })
                .fail(function () {
                    $configBox.hide();
                });
            }

            // Render configuration form
            function renderConfigForm(schema) {
                let html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-8">';

                Object.keys(schema).forEach(function (key) {
                    const field = schema[key];
                    const oldValue = `{{ old('dns_config.${key}') }}`.replace('${key}', key);

                    html += `
                        <div class="form-control w-full">
                            <label for="dns_config_${key}" class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">
                                    ${field.description || key} 
                                    ${field.required ? '<span class="badge badge-ghost badge-soft badge-xs ml-1 font-bold uppercase tracking-tighter">Required</span>' : ''}
                                </span>
                            </label>
                            <input type="${field.sensitive ? 'password' : 'text'}" 
                                   name="dns_config[${key}]" 
                                   id="dns_config_${key}" 
                                   class="input input-bordered focus:input-primary w-full transition-all" 
                                   value="${oldValue}"
                                   ${field.required ? 'required' : ''} />
                        </div>
                    `;
                });

                html += '</div>';
                $configContent.html(html);
            }

            // Trigger change if provider is pre-selected
            if ($providerSelect.val()) {
                $providerSelect.trigger('change');
            }
        });
    </script>
@endsection
