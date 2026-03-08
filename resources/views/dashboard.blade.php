@extends('layouts.app')

@section('title', 'Dashboard — Slidd')

@section('content')

<div
    x-data="{
        showModal: false,
        showToast: false,
        toastLoading: true,
        toastError: false,
        toastMessage: '',
        form: { title: '', type: 'slides' },

        openModal() {
            this.showModal = true;
            this.$nextTick(() => this.$refs.titleInput && this.$refs.titleInput.focus());
        },

        closeModal() {
            this.showModal = false;
            this.form = { title: '', type: 'slides' };
        },

        showError(msg) {
            this.toastError = true;
            this.toastLoading = false;
            this.toastMessage = msg;
            this.showToast = true;
            setTimeout(() => { this.showToast = false; this.toastError = false; }, 4000);
        },

        async submit() {
            if (!this.form.title.trim()) return;
            const title = this.form.title.trim();
            const type  = this.form.type;
            this.closeModal();
            this.toastError = false;
            this.toastLoading = true;
            this.toastMessage = 'Creating project...';
            this.showToast = true;

            try {
                const res = await fetch('/projects', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ title, type }),
                });

                const data = await res.json();

                if (!res.ok) {
                    this.showToast = false;
                    this.showError(data.error || 'Something went wrong.');
                    return;
                }

                this.toastMessage = 'Project created!';
                this.toastLoading = false;
                setTimeout(() => { window.location.href = data.redirect_url; }, 700);
            } catch (err) {
                this.showToast = false;
                this.showError(err.message || 'Network error.');
            }
        }
    }"
    @keydown.escape.window="closeModal()"
>

    <div class="p-10 pb-0 flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Good morning, {{ Auth::user()->name }}.</h1>
            <p class="text-sm text-gray-400 mt-1">Here's what you've been working on.</p>
        </div>
        <button
            @click="openModal()"
            class="bg-slate-900 text-white text-sm font-medium py-2 px-4 rounded-lg shadow-sm hover:bg-slate-800 transition-colors"
        >
            + Create New Project
        </button>
    </div>

    <div class="p-10 relative" style="min-height:calc(100vh - 11rem);">

        @forelse ($projects as $project)
            @if ($loop->first)
                <div class="flex items-center justify-between mb-6">
                    <span class="text-xs font-bold tracking-widest text-gray-400 uppercase">Recent Projects</span>
                    <span class="text-xs text-gray-400">{{ $projects->count() }} {{ Str::plural('project', $projects->count()) }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @endif

            <a href="{{ route('editor.show', $project->slug) }}" class="border border-gray-100 rounded-2xl overflow-hidden hover:shadow-md transition-shadow cursor-pointer flex flex-col group">
                <div class="bg-gray-50 h-36 p-4 flex flex-col justify-between overflow-hidden">
                    @if ($project->cover_path)
                        <img src="{{ asset('storage/' . $project->cover_path) }}" class="w-full h-full object-cover rounded-lg -m-4" style="margin:-1rem;width:calc(100% + 2rem);height:calc(100% + 2rem);" alt="{{ $project->title }}">
                    @else
                        <div>
                            <div class="h-1.5 bg-gray-200 rounded-full w-3/4 mb-2"></div>
                            <div class="h-1.5 bg-gray-200 rounded-full w-1/2 mb-2"></div>
                            <div class="h-1.5 bg-gray-200 rounded-full w-2/3"></div>
                        </div>
                        <div class="flex justify-between items-end">
                            <div class="flex gap-1">
                                <div class="w-4 h-3 bg-gray-200 rounded-sm"></div>
                                <div class="w-4 h-3 bg-gray-200 rounded-sm"></div>
                                <div class="w-4 h-3 bg-gray-200 rounded-sm"></div>
                            </div>
                            <span class="text-[10px] text-gray-400 capitalize">{{ $project->type }}</span>
                        </div>
                    @endif
                </div>
                <div class="bg-white p-4 flex justify-between items-center">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-900 truncate">{{ $project->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Edited {{ $project->updated_at->diffForHumans() }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 shrink-0 ml-3 group-hover:text-gray-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </div>
            </a>

            @if ($loop->last)
                    <div
                        @click="openModal()"
                        class="border border-gray-100 rounded-2xl flex flex-col justify-center items-center min-h-[220px] bg-white hover:bg-gray-50 transition-colors cursor-pointer text-gray-400 hover:text-gray-500"
                    >
                        <div class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        </div>
                        <span class="text-sm font-medium">New project</span>
                    </div>
                </div>
            @endif

        @empty

            <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none" style="top:0;left:0;right:0;bottom:0;">
                <div class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                </div>
                <p class="text-sm font-semibold text-slate-900 mb-1">No projects yet</p>
                <p class="text-sm text-gray-400">Create your first workspace to get started.</p>
            </div>

        @endforelse

    </div>

    <div
        x-show="showModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-start justify-center pt-24 px-4"
        style="display:none;"
    >
        <div class="absolute inset-0 bg-black/20 backdrop-blur-sm" @click="closeModal()"></div>

        <div
            x-show="showModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
            class="relative bg-white border border-gray-100 rounded-2xl shadow-xl w-full max-w-md p-6"
            @click.stop
        >
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-semibold text-slate-900">New Project</h2>
                <button @click="closeModal()" class="p-1 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Project name</label>
                    <input
                        x-ref="titleInput"
                        x-model="form.title"
                        @keydown.enter="submit()"
                        type="text"
                        placeholder="My awesome deck"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white placeholder-gray-300 text-slate-900 outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-900/10 transition-colors"
                    >
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Type</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button
                            type="button"
                            @click="form.type = 'slides'"
                            :class="form.type === 'slides' ? 'border-slate-900 bg-slate-900 text-white' : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300 hover:text-slate-900'"
                            class="flex flex-col items-center gap-2 p-4 rounded-xl border text-sm font-medium transition-all"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                            Standard Slides
                        </button>
                        <button
                            type="button"
                            @click="form.type = 'galaxy'"
                            :class="form.type === 'galaxy' ? 'border-slate-900 bg-slate-900 text-white' : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300 hover:text-slate-900'"
                            class="flex flex-col items-center gap-2 p-4 rounded-xl border text-sm font-medium transition-all"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z"/></svg>
                            Galaxy Workspace
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 mt-6 pt-5 border-t border-gray-100">
                <button @click="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-slate-900 transition-colors">
                    Cancel
                </button>
                <button
                    @click="submit()"
                    :disabled="!form.title.trim()"
                    :class="!form.title.trim() ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-800'"
                    class="px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-lg transition-colors"
                >
                    Create Project
                </button>
            </div>
        </div>
    </div>

    <div
        x-show="showToast"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        :class="toastError ? 'border-red-100 bg-red-50' : 'border-gray-100 bg-white'"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-3 border rounded-xl shadow-lg px-4 py-3"
        style="display:none;"
    >
        <div x-show="toastLoading" class="w-4 h-4 border-2 border-gray-200 border-t-slate-900 rounded-full animate-spin shrink-0"></div>
        <div x-show="!toastLoading && !toastError">
            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
        </div>
        <div x-show="toastError">
            <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
        </div>
        <span class="text-sm font-medium" :class="toastError ? 'text-red-700' : 'text-slate-900'" x-text="toastMessage"></span>
    </div>

</div>

@endsection
