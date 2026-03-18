@extends('layouts.admin')

@section('title')
    Application API
@endsection

@section('content-header')
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-4xl font-black tracking-tight text-base-content">Application API</h1>
            <p class="text-base-content/60 mt-1 text-sm">Create a new set of application credentials.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="breadcrumbs text-sm bg-base-200/50 px-4 py-2 rounded-lg border border-base-300">
                <ul>
                    <li><a href="{{ route('admin.index') }}">Admin</a></li>
                    <li><a href="{{ route('admin.api.index') }}">Application API</a></li>
                    <li class="font-bold">New Credentials</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.api.new') }}">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="card bg-base-100 border border-base-300 shadow-xl overflow-hidden">
                    <div class="card-body p-0">
                        <div class="bg-base-200/50 p-6 border-b border-base-300">
                            <h3 class="font-black text-xl uppercase tracking-wider">Select Permissions</h3>
                            <p class="text-xs text-base-content/50 mt-1">Define what this API key is allowed to access.</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table table-lg">
                                <thead>
                                    <tr class="bg-base-200/30">
                                        <th class="font-black text-xs uppercase tracking-wider">Resource</th>
                                        <th class="font-black text-xs uppercase tracking-wider text-center">Read</th>
                                        <th class="font-black text-xs uppercase tracking-wider text-center">Read & Write
                                        </th>
                                        <th class="font-black text-xs uppercase tracking-wider text-center">None</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-base-300">
                                    @foreach ($resources as $resource)
                                        <tr class="hover:bg-base-200/20 transition-colors">
                                            <td class="font-bold text-sm">{{ str_replace('_', ' ', title_case($resource)) }}
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" id="r_{{ $resource }}"
                                                    name="r_{{ $resource }}" value="{{ $permissions['r'] }}"
                                                    class="radio radio-primary radio-sm border-2">
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" id="rw_{{ $resource }}"
                                                    name="r_{{ $resource }}" value="{{ $permissions['rw'] }}"
                                                    class="radio radio-primary radio-sm border-2">
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" id="n_{{ $resource }}"
                                                    name="r_{{ $resource }}" value="{{ $permissions['n'] }}" checked
                                                    class="radio radio-sm border-2">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="space-y-6 sticky top-8">
                    <div class="card bg-base-100 border border-base-300 shadow-xl">
                        <div class="card-body">
                            <div class="form-control w-full">
                                <label class="label" for="memoField">
                                    <span class="label-text font-black text-xs uppercase tracking-widest opacity-70">Key
                                        Description</span>
                                </label>
                                <input id="memoField" type="text" name="memo"
                                    class="input input-bordered focus:input-primary w-full"
                                    placeholder="e.g. My Application Key" required>
                                <label class="label">
                                    <span class="label-text-alt text-base-content/50 italic">A short memo to identify this
                                        key.</span>
                                </label>
                            </div>

                            <div class="alert alert-info alert-soft mt-4 border-l-4 border-info">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    class="stroke-current shrink-0 w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-xs leading-relaxed">
                                    Once created, you <strong>cannot</strong> edit these permissions.
                                </div>
                            </div>

                            <div class="card-actions mt-8">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-primary btn-block shadow-lg shadow-primary/20">
                                    Create Credentials
                                </button>
                                <a href="{{ route('admin.api.index') }}"
                                    class="btn btn-ghost btn-block btn-sm mt-2">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
