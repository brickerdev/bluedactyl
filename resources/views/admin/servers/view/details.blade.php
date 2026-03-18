@extends('layouts.admin')

@section('title')
    Server — {{ $server->name }}: Details
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter">{{ $server->name }}</h1>
            <p class="text-base-content/60 text-sm">Edit details for this server including owner and container.</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.servers') }}">Servers</a></li>
                <li><a href="{{ route('admin.servers.view', $server->id) }}">{{ $server->name }}</a></li>
                <li class="text-primary">Details</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    @include('admin.servers.partials.navigation')

    <div class="grid grid-cols-1 gap-8">
        <div class="card bg-base-200/50 shadow-xl backdrop-blur-md border border-base-300">
            <div class="card-body p-0">
                <div class="p-6 border-b border-base-300">
                    <h3 class="text-xl font-bold tracking-tight">Base Information</h3>
                </div>
                <form action="{{ route('admin.servers.view.details', $server->id) }}" method="POST" class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Server Name <span class="text-error">*</span></span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $server->name) }}"
                                class="input input-bordered focus:input-primary transition-all" required />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Character limits: <code>a-zA-Z0-9_-</code>
                                    and <code>[Space]</code>.</span>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">External Identifier</span>
                            </label>
                            <input type="text" name="external_id" value="{{ old('external_id', $server->external_id) }}"
                                class="input input-bordered focus:input-primary transition-all" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">Leave empty to not assign an external
                                    identifier. Must be unique.</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Server Owner <span class="text-error">*</span></span>
                        </label>
                        <select name="owner_id" class="select2-daisy w-full" id="pUserId">
                            <option value="{{ $server->owner_id }}" selected>{{ $server->user->email }}</option>
                        </select>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">Changing the owner will automatically generate
                                a new daemon security token.</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Server Description</span>
                        </label>
                        <textarea name="description" rows="3"
                            class="textarea textarea-bordered focus:textarea-primary transition-all h-24">{{ old('description', $server->description) }}</textarea>
                        <label class="label">
                            <span class="label-text-alt text-base-content/50">A brief description of this server.</span>
                        </label>
                    </div>

                    <div class="pt-4 flex justify-end border-t border-base-300">
                        {!! csrf_field() !!}
                        {!! method_field('PATCH') !!}
                        <button type="submit" class="btn btn-primary px-8">Update Details</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent
    <style>
        .select2-container--default .select2-selection--single {
            @apply bg-base-100 border-base-300 rounded-lg h-12 flex items-center px-2;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            @apply border-primary ring-1 ring-primary;
        }

        .select2-dropdown {
            @apply bg-base-200 border-base-300 shadow-2xl rounded-lg overflow-hidden;
        }

        .user-block {
            @apply flex items-center gap-3 p-2;
        }

        .user-block img {
            @apply w-10 h-10 rounded-full border border-base-300;
        }

        .user-block .username {
            @apply font-bold text-sm block;
        }

        .user-block .description {
            @apply text-xs opacity-60 block;
        }
    </style>
    <script>
        function escapeHtml(str) {
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(str));
            return div.innerHTML;
        }

        $('#pUserId').select2({
            ajax: {
                url: '/admin/users/accounts.json',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        filter: {
                            email: params.term
                        },
                        page: params.page,
                    };
                },
                processResults: function(data, params) {
                    return {
                        results: data
                    };
                },
                cache: true,
            },
            escapeMarkup: function(markup) {
                return markup;
            },
            minimumInputLength: 2,
            templateResult: function(data) {
                if (data.loading) return escapeHtml(data.text);

                return '<div class="user-block"> \
                    <img src="https://www.gravatar.com/avatar/' + escapeHtml(data.md5) + '?s=120" alt="User Image"> \
                    <div> \
                        <span class="username">' + escapeHtml(data.name_first) + ' ' + escapeHtml(data.name_last) + '</span> \
                        <span class="description"><strong>' + escapeHtml(data.email) + '</strong> - ' + escapeHtml(data
                    .username) + '</span> \
                    </div> \
                </div>';
            },
            templateSelection: function(data) {
                if (typeof data.name_first === 'undefined') {
                    data = {
                        md5: '{{ md5(strtolower($server->user->email)) }}',
                        name_first: '{{ $server->user->name_first }}',
                        name_last: '{{ $server->user->name_last }}',
                        email: '{{ $server->user->email }}',
                        id: {{ $server->owner_id }}
                    };
                }

                return '<div class="flex items-center gap-2"> \
                    <img class="rounded-full border border-base-300" src="https://www.gravatar.com/avatar/' + escapeHtml(
                    data.md5) + '?s=120" style="height:24px;" alt="User Image"> \
                    <span class="text-sm"> \
                        ' + escapeHtml(data.name_first) + ' ' + escapeHtml(data.name_last) + ' (<strong>' + escapeHtml(data
                    .email) + '</strong>) \
                    </span> \
                </div>';
            }
        });
    </script>
@endsection
