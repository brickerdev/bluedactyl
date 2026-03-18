@extends('layouts.admin')
@include('partials/admin.settings.nav', ['activeTab' => 'captcha'])

@section('title')
    Captcha Settings
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter uppercase">Captcha Settings</h1>
            <p class="text-base-content/60 text-sm">Configure captcha protection for authentication forms.</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.settings') }}">Settings</a></li>
                <li class="text-primary font-bold">Captcha</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    @yield('settings::nav')

    <form action="{{ route('admin.settings.captcha') }}" method="POST">
        <div class="grid grid-cols-1 gap-6">
            {{-- Captcha Provider Selection --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <i class="ri-shield-check-line text-primary text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold uppercase tracking-tight">Captcha Provider</h3>
                    </div>

                    <div class="form-control max-w-md">
                        <label class="label">
                            <span class="label-text font-bold uppercase tracking-wide text-xs">Provider</span>
                        </label>
                        <select name="pterodactyl:captcha:provider"
                            class="select select-bordered w-full focus:select-primary transition-all" id="captcha-provider">
                            @foreach ($providers as $key => $name)
                                <option value="{{ $key }}" @if (old('pterodactyl:captcha:provider', config('pterodactyl.captcha.provider', 'none')) === $key) selected @endif>
                                    {{ $name }}</option>
                            @endforeach
                        </select>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">Select the captcha provider to use for
                                authentication forms.</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Cloudflare Turnstile --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md" id="turnstile-settings"
                style="display: none;">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-info/10 flex items-center justify-center">
                            <i class="ri-cloudflare-line text-info text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold uppercase tracking-tight">Cloudflare Turnstile Configuration</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Site Key</span>
                            </label>
                            <input type="text"
                                class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm"
                                name="pterodactyl:captcha:turnstile:site_key"
                                value="{{ old('pterodactyl:captcha:turnstile:site_key', config('pterodactyl.captcha.turnstile.site_key', '')) }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">The site key provided by Cloudflare
                                    Turnstile.</span>
                            </label>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Secret Key</span>
                            </label>
                            <input type="password" class="input input-bordered w-full focus:input-primary transition-all"
                                name="pterodactyl:captcha:turnstile:secret_key"
                                value="{{ old('pterodactyl:captcha:turnstile:secret_key', config('pterodactyl.captcha.turnstile.secret_key', '')) }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">The secret key provided by Cloudflare
                                    Turnstile.</span>
                            </label>
                        </div>
                    </div>
                    <div class="alert alert-soft alert-info mt-6">
                        <i class="ri-information-line text-xl"></i>
                        <div class="text-sm">
                            <p class="font-bold uppercase tracking-wide">Setup Instructions</p>
                            <ul class="list-disc list-inside mt-1 opacity-70">
                                <li>Visit the <a href="https://dash.cloudflare.com/?to=/:account/turnstile" target="_blank"
                                        class="underline font-bold">Cloudflare Turnstile dashboard</a></li>
                                <li>Create a new site and add your domain</li>
                                <li>Copy the Site Key and Secret Key here</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- hCaptcha --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md" id="hcaptcha-settings"
                style="display: none;">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-info/10 flex items-center justify-center">
                            <i class="ri-shield-user-line text-info text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold uppercase tracking-tight">hCaptcha Configuration</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Site Key</span>
                            </label>
                            <input type="text"
                                class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm"
                                name="pterodactyl:captcha:hcaptcha:site_key"
                                value="{{ old('pterodactyl:captcha:hcaptcha:site_key', config('pterodactyl.captcha.hcaptcha.site_key', '')) }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">The site key provided by hCaptcha.</span>
                            </label>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Secret Key</span>
                            </label>
                            <input type="password" class="input input-bordered w-full focus:input-primary transition-all"
                                name="pterodactyl:captcha:hcaptcha:secret_key"
                                value="{{ old('pterodactyl:captcha:hcaptcha:secret_key', config('pterodactyl.captcha.hcaptcha.secret_key', '')) }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">The secret key provided by
                                    hCaptcha.</span>
                            </label>
                        </div>
                    </div>
                    <div class="alert alert-soft alert-info mt-6">
                        <i class="ri-information-line text-xl"></i>
                        <div class="text-sm">
                            <p class="font-bold uppercase tracking-wide">Setup Instructions</p>
                            <ul class="list-disc list-inside mt-1 opacity-70">
                                <li>Visit the <a href="https://dashboard.hcaptcha.com/sites" target="_blank"
                                        class="underline font-bold">hCaptcha dashboard</a></li>
                                <li>Create a new site and add your domain</li>
                                <li>Copy the Site Key and Secret Key here</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Google reCAPTCHA v3 --}}
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md" id="recaptcha-settings"
                style="display: none;">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-info/10 flex items-center justify-center">
                            <i class="ri-google-line text-info text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold uppercase tracking-tight">Google reCAPTCHA v3 Configuration</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Site Key</span>
                            </label>
                            <input type="text"
                                class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm"
                                name="pterodactyl:captcha:recaptcha:site_key"
                                value="{{ old('pterodactyl:captcha:recaptcha:site_key', config('pterodactyl.captcha.recaptcha.site_key', '')) }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">The site key provided by Google reCAPTCHA
                                    v3.</span>
                            </label>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Secret Key</span>
                            </label>
                            <input type="password" class="input input-bordered w-full focus:input-primary transition-all"
                                name="pterodactyl:captcha:recaptcha:secret_key"
                                value="{{ old('pterodactyl:captcha:recaptcha:secret_key', config('pterodactyl.captcha.recaptcha.secret_key', '')) }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">The secret key provided by Google
                                    reCAPTCHA v3.</span>
                            </label>
                        </div>
                    </div>
                    <div class="alert alert-soft alert-info mt-6">
                        <i class="ri-information-line text-xl"></i>
                        <div class="text-sm">
                            <p class="font-bold uppercase tracking-wide">reCAPTCHA v3 Setup Instructions</p>
                            <ul class="list-disc list-inside mt-1 opacity-70">
                                <li>Visit the <a href="https://www.google.com/recaptcha/admin" target="_blank"
                                        class="underline font-bold">Google reCAPTCHA admin console</a></li>
                                <li>Create a new site and select <strong>reCAPTCHA v3</strong></li>
                                <li>Copy the Site Key and Secret Key here</li>
                            </ul>
                            <p class="mt-2 text-[10px] uppercase font-bold tracking-widest opacity-50">Note: reCAPTCHA v3
                                runs invisibly. A threshold of 0.5 is used by default.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-actions justify-end mt-6">
                {{ csrf_field() }}
                <button type="submit" name="_method" value="PATCH"
                    class="btn btn-primary px-12 font-bold uppercase tracking-wider">
                    <i class="ri-save-line mr-2"></i>
                    Save Changes
                </button>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const providerSelect = document.getElementById('captcha-provider');
            const turnstileSettings = document.getElementById('turnstile-settings');
            const hcaptchaSettings = document.getElementById('hcaptcha-settings');
            const recaptchaSettings = document.getElementById('recaptcha-settings');

            function toggleSettings() {
                const provider = providerSelect.value;

                // Hide all provider-specific settings first
                turnstileSettings.style.display = 'none';
                hcaptchaSettings.style.display = 'none';
                recaptchaSettings.style.display = 'none';

                if (provider === 'turnstile') {
                    turnstileSettings.style.display = 'block';
                } else if (provider === 'hcaptcha') {
                    hcaptchaSettings.style.display = 'block';
                } else if (provider === 'recaptcha') {
                    recaptchaSettings.style.display = 'block';
                }
            }

            providerSelect.addEventListener('change', toggleSettings);

            // Initialize on page load with a small delay to ensure DOM is ready
            setTimeout(toggleSettings, 100);
        });
    </script>
@endsection
