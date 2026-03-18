@extends('layouts.admin')

@section('title')
    Create User
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-base-content uppercase">Create User</h1>
            <p class="text-base-content/60 text-sm font-medium">Add a new user to the system.</p>
        </div>
        <div class="text-sm breadcrumbs text-base-content/60 font-medium">
            <ul>
                <li><a href="{{ route('admin.index') }}" class="hover:text-primary transition-colors">Admin</a></li>
                <li><a href="{{ route('admin.users') }}" class="hover:text-primary transition-colors">Users</a></li>
                <li class="text-base-content">Create</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <form method="post">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Identity Section --}}
            <div class="card bg-base-200/50 backdrop-blur-md border border-base-300 shadow-sm">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                            <i class="fa fa-id-card text-xl"></i>
                        </div>
                        <h2 class="text-xl font-black tracking-tight uppercase">Identity</h2>
                    </div>

                    <div class="space-y-4">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Email Address</span>
                            </label>
                            <input type="text" autocomplete="off" name="email" value="{{ old('email') }}" class="input input-bordered w-full bg-base-100 focus:border-primary transition-all" required />
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Username</span>
                            </label>
                            <input type="text" autocomplete="off" name="username" value="{{ old('username') }}" class="input input-bordered w-full bg-base-100 focus:border-primary transition-all" required />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">First Name</span>
                                </label>
                                <input type="text" autocomplete="off" name="name_first" value="{{ old('name_first') }}" class="input input-bordered w-full bg-base-100 focus:border-primary transition-all" required />
                            </div>
                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Last Name</span>
                                </label>
                                <input type="text" autocomplete="off" name="name_last" value="{{ old('name_last') }}" class="input input-bordered w-full bg-base-100 focus:border-primary transition-all" required />
                            </div>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Default Language</span>
                            </label>
                            <select name="language" class="select select-bordered w-full bg-base-100">
                                @foreach ($languages as $key => $value)
                                    <option value="{{ $key }}" @if (config('app.locale') === $key) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/40 italic">The default language for this user's panel.</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                {{-- Permissions Section --}}
                <div class="card bg-base-200/50 backdrop-blur-md border border-base-300 shadow-sm">
                    <div class="card-body p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                                <i class="fa fa-shield text-xl"></i>
                            </div>
                            <h2 class="text-xl font-black tracking-tight uppercase">Permissions</h2>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Administrator Status</span>
                            </label>
                            <select name="root_admin" class="select select-bordered w-full bg-base-100">
                                <option value="0">@lang('strings.no')</option>
                                <option value="1">@lang('strings.yes')</option>
                            </select>
                            <label class="label">
                                <span class="label-text-alt text-base-content/40 italic">Gives the user full administrative access to the panel.</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Password Section --}}
                <div class="card bg-base-200/50 backdrop-blur-md border border-base-300 shadow-sm">
                    <div class="card-body p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                                <i class="fa fa-key text-xl"></i>
                            </div>
                            <h2 class="text-xl font-black tracking-tight uppercase">Password</h2>
                        </div>

                        <div class="alert alert-soft alert-info mb-4 py-3 px-4 rounded-lg border-none">
                            <i class="fa fa-info-circle text-info"></i>
                            <span class="text-xs font-medium">Providing a password is optional. New users will be prompted to create one on their first login.</span>
                        </div>

                        <div id="gen_pass" class="alert alert-soft alert-success mb-4 hidden py-2 px-4 rounded-lg border-none font-mono text-xs"></div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase text-xs tracking-widest text-base-content/60">Password</span>
                                <button type="button" id="gen_pass_bttn" class="label-text-alt link link-primary font-bold uppercase text-[10px] tracking-wider no-underline hover:underline">Generate Random</button>
                            </label>
                            <input type="password" name="password" class="input input-bordered w-full bg-base-100 focus:border-primary transition-all" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3">
            {!! csrf_field() !!}
            <a href="{{ route('admin.users') }}" class="btn btn-ghost font-bold uppercase tracking-wider">Cancel</a>
            <button type="submit" class="btn btn-primary px-12 font-bold uppercase tracking-wider shadow-lg shadow-primary/20">
                <i class="fa fa-user-plus mr-2"></i> Create User Account
            </button>
        </div>
    </form>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $("#gen_pass_bttn").click(function(event) {
            event.preventDefault();
            const btn = $(this);
            btn.addClass('opacity-50 pointer-events-none');
            
            $.ajax({
                type: "GET",
                url: "/password-gen/12",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    $("#gen_pass").html('<strong>Generated Password:</strong> <span class="select-all">' + data + '</span>').removeClass('hidden').slideDown();
                    $('input[name="password"]').val(data);
                },
                complete: function() {
                    btn.removeClass('opacity-50 pointer-events-none');
                }
            });
        });
    </script>
@endsection
