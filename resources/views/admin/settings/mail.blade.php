@extends('layouts.admin')
@include('partials/admin.settings.nav', ['activeTab' => 'mail'])

@section('title')
    Mail Settings
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter uppercase">Mail Settings</h1>
            <p class="text-base-content/60 text-sm">Configure how Pterodactyl should handle sending emails.</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.settings') }}">Settings</a></li>
                <li class="text-primary font-bold">Mail</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    @yield('settings::nav')

    <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
        <div class="card-body p-6">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                    <i class="ri-mail-send-line text-primary text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold uppercase tracking-tight">Email Configuration</h3>
                    <p class="text-xs text-base-content/50 italic">Configure SMTP and sender information.</p>
                </div>
            </div>

            @if ($disabled)
                <div class="alert alert-soft alert-info mb-6">
                    <i class="ri-information-line text-xl"></i>
                    <div class="text-sm">
                        <p class="font-bold uppercase tracking-wide">SMTP Only</p>
                        <p>This interface is limited to instances using SMTP as the mail driver. Please use <code>php
                                artisan p:environment:mail</code> or set <code>MAIL_DRIVER=smtp</code> in your environment
                            file.</p>
                    </div>
                </div>
            @else
                <form>
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        <div class="form-control md:col-span-6">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">SMTP Host</span>
                            </label>
                            <input required type="text"
                                class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm"
                                name="mail:mailers:smtp:host"
                                value="{{ old('mail:mailers:smtp:host', config('mail.mailers.smtp.host')) }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Enter the SMTP server address.</span>
                            </label>
                        </div>

                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">SMTP Port</span>
                            </label>
                            <input required type="number"
                                class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm"
                                name="mail:mailers:smtp:port"
                                value="{{ old('mail:mailers:smtp:port', config('mail.mailers.smtp.port')) }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">SMTP server port.</span>
                            </label>
                        </div>

                        <div class="form-control md:col-span-4">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Encryption</span>
                            </label>
                            @php
                                $encryption = old(
                                    'mail:mailers:smtp:encryption',
                                    config('mail.mailers.smtp.encryption'),
                                );
                            @endphp
                            <select name="mail:mailers:smtp:encryption"
                                class="select select-bordered w-full focus:select-primary transition-all">
                                <option value="" @if ($encryption === '') selected @endif>None</option>
                                <option value="tls" @if ($encryption === 'tls') selected @endif>TLS</option>
                                <option value="ssl" @if ($encryption === 'ssl') selected @endif>SSL</option>
                            </select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Select encryption type.</span>
                            </label>
                        </div>

                        <div class="form-control md:col-span-6">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Username <span
                                        class="badge badge-soft badge-sm font-bold uppercase tracking-widest ml-1">Optional</span></span>
                            </label>
                            <input type="text"
                                class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm"
                                name="mail:mailers:smtp:username"
                                value="{{ old('mail:mailers:smtp:username', config('mail.mailers.smtp.username')) }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">The username for the SMTP server.</span>
                            </label>
                        </div>

                        <div class="form-control md:col-span-6">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Password <span
                                        class="badge badge-soft badge-sm font-bold uppercase tracking-widest ml-1">Optional</span></span>
                            </label>
                            <input type="password" class="input input-bordered w-full focus:input-primary transition-all"
                                name="mail:mailers:smtp:password" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Leave blank to keep current. Enter
                                    <code>!e</code> for empty.</span>
                            </label>
                        </div>

                        <div class="divider md:col-span-12 uppercase font-black tracking-widest text-[10px] opacity-40">
                            Sender Information</div>

                        <div class="form-control md:col-span-6">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Mail From</span>
                            </label>
                            <input required type="email"
                                class="input input-bordered w-full focus:input-primary transition-all font-mono text-sm"
                                name="mail:from:address"
                                value="{{ old('mail:from:address', config('mail.from.address')) }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Email address for outgoing emails.</span>
                            </label>
                        </div>

                        <div class="form-control md:col-span-6">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Mail From Name <span
                                        class="badge badge-soft badge-sm font-bold uppercase tracking-widest ml-1">Optional</span></span>
                            </label>
                            <input type="text" class="input input-bordered w-full focus:input-primary transition-all"
                                name="mail:from:name" value="{{ old('mail:from:name', config('mail.from.name')) }}" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">The name that emails should appear to come
                                    from.</span>
                            </label>
                        </div>
                    </div>

                    <div class="card-actions justify-end mt-12 pt-8 border-t border-base-300 gap-3">
                        {{ csrf_field() }}
                        <button type="button" id="testButton"
                            class="btn btn-ghost btn-success btn-sm font-bold uppercase tracking-wider">
                            <i class="ri-send-plane-line mr-2"></i>
                            Test Settings
                        </button>
                        <button type="button" id="saveButton"
                            class="btn btn-primary btn-sm px-8 font-bold uppercase tracking-wider">
                            <i class="ri-save-line mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        function saveSettings() {
            return $.ajax({
                method: 'PATCH',
                url: '/admin/settings/mail',
                contentType: 'application/json',
                data: JSON.stringify({
                    'mail:mailers:smtp:host': $('input[name="mail:mailers:smtp:host"]').val(),
                    'mail:mailers:smtp:port': $('input[name="mail:mailers:smtp:port"]').val(),
                    'mail:mailers:smtp:encryption': $('select[name="mail:mailers:smtp:encryption"]').val(),
                    'mail:mailers:smtp:username': $('input[name="mail:mailers:smtp:username"]').val(),
                    'mail:mailers:smtp:password': $('input[name="mail:mailers:smtp:password"]').val(),
                    'mail:from:address': $('input[name="mail:from:address"]').val(),
                    'mail:from:name': $('input[name="mail:from:name"]').val()
                }),
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                }
            }).fail(function(jqXHR) {
                showErrorDialog(jqXHR, 'save');
            });
        }

        function testSettings() {
            swal({
                type: 'info',
                title: 'Test Mail Settings',
                text: 'Click "Test" to begin the test.',
                showCancelButton: true,
                confirmButtonText: 'Test',
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function() {
                $.ajax({
                    method: 'POST',
                    url: '/admin/settings/mail/test',
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    }
                }).fail(function(jqXHR) {
                    showErrorDialog(jqXHR, 'test');
                }).done(function() {
                    swal({
                        title: 'Success',
                        text: 'The test message was sent successfully.',
                        type: 'success'
                    });
                });
            });
        }

        function saveAndTestSettings() {
            saveSettings().done(testSettings);
        }

        function showErrorDialog(jqXHR, verb) {
            console.error(jqXHR);
            var errorText = '';
            if (!jqXHR.responseJSON) {
                errorText = jqXHR.responseText;
            } else if (jqXHR.responseJSON.error) {
                errorText = jqXHR.responseJSON.error;
            } else if (jqXHR.responseJSON.errors) {
                $.each(jqXHR.responseJSON.errors, function(i, v) {
                    if (v.detail) {
                        errorText += v.detail + ' ';
                    }
                });
            }

            swal({
                title: 'Whoops!',
                text: 'An error occurred while attempting to ' + verb + ' mail settings: ' + errorText,
                type: 'error'
            });
        }

        $(document).ready(function() {
            $('#testButton').on('click', saveAndTestSettings);
            $('#saveButton').on('click', function() {
                saveSettings().done(function() {
                    swal({
                        title: 'Success',
                        text: 'Mail settings have been updated successfully and the queue worker was restarted to apply these changes.',
                        type: 'success'
                    });
                });
            });
        });
    </script>
@endsection
