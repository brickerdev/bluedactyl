@extends('layouts.admin')

@section('title')
    New Nest
@endsection

@section('content-header')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter uppercase">New Nest</h1>
            <p class="text-base-content/60 text-sm">Configure a new nest to deploy to all nodes.</p>
        </div>
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                <li><a href="{{ route('admin.nests') }}">Nests</a></li>
                <li class="text-primary font-bold">New</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.nests.new') }}" method="POST">
        <div class="grid grid-cols-1 gap-6">
            <div class="card bg-base-200/50 border border-base-300 shadow-xl backdrop-blur-md">
                <div class="card-body p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <i class="ri-folder-add-line text-primary text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold uppercase tracking-tight">Nest Configuration</h3>
                    </div>

                    <div class="space-y-6">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Name</span>
                            </label>
                            <input type="text" name="name"
                                class="input input-bordered w-full focus:input-primary transition-all"
                                value="{{ old('name') }}" placeholder="e.g. Minecraft" />
                            <label class="label">
                                <span class="label-text-alt text-base-content/50">This should be a descriptive category name
                                    that encompasses all of the eggs within the nest.</span>
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold uppercase tracking-wide text-xs">Description</span>
                            </label>
                            <textarea name="description" class="textarea textarea-bordered w-full h-48 focus:textarea-primary transition-all"
                                placeholder="Describe what this nest is for...">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="card-actions justify-end mt-8 pt-6 border-t border-base-300">
                        {!! csrf_field() !!}
                        <button type="submit" class="btn btn-primary px-8 font-bold uppercase tracking-wider">
                            <i class="ri-save-line mr-2"></i>
                            Save Nest
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
