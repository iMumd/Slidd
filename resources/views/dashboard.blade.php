@extends('layouts.app')

@section('title', 'Dashboard — Slidd')

@section('content')

<div
    x-data="{
        showModal: false,
        form: { title: '', type: 'slides' },

        showRenameModal: false,
        renameForm: { slug: '', title: '' },

        showDeleteModal: false,
        deleteForm: { slug: '', title: '' },

        openDropdown: null,

        get greeting() {
            const h = new Date().getHours();
            if (h >= 5  && h < 12) return 'Good morning';
            if (h >= 12 && h < 17) return 'Good afternoon';
            if (h >= 17 && h < 21) return 'Good evening';
            return 'Good night';
        },

        showToast: false,
        toastLoading: false,
        toastError: false,
        toastMessage: '',
        _timer: null,

        showToastMsg(msg, loading = false, error = false) {
            if (this._timer) clearTimeout(this._timer);
            this.toastMessage = msg;
            this.toastLoading = loading;
            this.toastError   = error;
            this.showToast    = true;
            if (!loading) {
                this._timer = setTimeout(() => { this.showToast = false; }, 3500);
            }
        },

        showError(msg) { this.showToastMsg(msg, false, true); },

        async importSlidd(event) {
            const file = event.target.files[0];
            if (!file) return;
            try {
                const text = await file.text();
                const data = JSON.parse(text);
                if (!data.slides || !Array.isArray(data.slides)) throw new Error('Invalid .slidd file.');
                this.showToastMsg('Importing…', true);
                const res = await fetch('/projects/import', {
                    method  : 'POST',
                    headers : {
                        'Content-Type' : 'application/json',
                        'Accept'       : 'application/json',
                        'X-CSRF-TOKEN' : document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify(data),
                });
                const result = await res.json();
                if (!res.ok) throw new Error(result.error ?? `HTTP ${res.status}`);
                window.location.href = result.redirect_url;
            } catch (err) {
                this.showError('Import failed: ' + err.message);
            }
            event.target.value = '';
        },

        openModal() {
            this.showModal = true;
            this.$nextTick(() => this.$refs.titleInput && this.$refs.titleInput.focus());
        },
        closeModal() {
            this.showModal = false;
            this.form = { title: '', type: 'slides' };
        },
        async submitCreate() {
            if (!this.form.title.trim()) return;
            const title = this.form.title.trim();
            const type  = this.form.type;
            this.closeModal();
            this.showToastMsg('Creating project...', true);
            try {
                const res  = await fetch('/projects', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                    body: JSON.stringify({ title, type }),
                });
                const data = await res.json();
                if (!res.ok) { this.showError(data.error || 'Something went wrong.'); return; }
                this.showToastMsg('Project created!');
                setTimeout(() => { window.location.href = data.redirect_url; }, 700);
            } catch (err) {
                this.showError(err.message || 'Network error.');
            }
        },

        openRename(slug, title) {
            this.openDropdown  = null;
            this.renameForm    = { slug, title };
            this.showRenameModal = true;
            this.$nextTick(() => this.$refs.renameInput && this.$refs.renameInput.select());
        },
        closeRename() {
            this.showRenameModal = false;
            this.renameForm = { slug: '', title: '' };
        },
        async submitRename() {
            if (!this.renameForm.title.trim()) return;
            const slug  = this.renameForm.slug;
            const title = this.renameForm.title.trim();
            this.closeRename();
            this.showToastMsg('Renaming...', true);
            try {
                const res  = await fetch('/projects/' + slug, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                    body: JSON.stringify({ title }),
                });
                const data = await res.json();
                if (!res.ok) { this.showError(data.error || 'Something went wrong.'); return; }
                this.showToastMsg('Project renamed!');
                setTimeout(() => location.reload(), 800);
            } catch (err) {
                this.showError(err.message || 'Network error.');
            }
        },

        openDelete(slug, title) {
            this.openDropdown  = null;
            this.deleteForm    = { slug, title };
            this.showDeleteModal = true;
        },
        closeDelete() {
            this.showDeleteModal = false;
            this.deleteForm = { slug: '', title: '' };
        },
        async submitDelete() {
            const slug = this.deleteForm.slug;
            this.closeDelete();
            this.showToastMsg('Deleting...', true);
            try {
                const res  = await fetch('/projects/' + slug, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                });
                const data = await res.json();
                if (!res.ok) { this.showError(data.error || 'Something went wrong.'); return; }
                this.showToastMsg('Project deleted!');
                setTimeout(() => location.reload(), 800);
            } catch (err) {
                this.showError(err.message || 'Network error.');
            }
        },

        async share(slug) {
            this.openDropdown = null;
            try {
                await navigator.clipboard.writeText(window.location.origin + '/s/' + slug);
                this.showToastMsg('Preview link copied!');
            } catch {
                this.showError('Could not copy link.');
            }
        },
    }"
    @click.window="openDropdown = null"
    @keydown.escape.window="closeModal(); closeRename(); closeDelete(); openDropdown = null;"
>

    <div class="max-w-6xl mx-auto w-full px-4 sm:px-6 md:px-10 py-10">

    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900" x-text="greeting + ', {{ Auth::user()->name }}.'"></h1>
            <p class="text-sm text-gray-400 mt-1">Here's what you've been working on.</p>
        </div>
        <div class="flex items-center gap-2">
            <input type="file" x-ref="sliddImport" accept=".slidd" class="hidden" @change="importSlidd($event)">
            <button
                @click="$refs.sliddImport.click()"
                class="flex items-center gap-1.5 text-sm font-medium text-gray-500 border border-gray-200 hover:border-gray-300 hover:text-slate-900 py-2 px-4 rounded-lg transition-colors"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                </svg>
                Import .slidd
            </button>
            <button
                @click="openModal()"
                class="bg-slate-900 text-white text-sm font-medium py-2 px-4 rounded-lg shadow-sm hover:bg-slate-800 transition-colors"
            >
                + Create New Project
            </button>
        </div>
    </div>

    <div class="relative" style="min-height:calc(100vh - 11rem);">

        @forelse ($projects as $project)
            @if ($loop->first)
                <div class="flex items-center justify-between mb-6">
                    <span class="text-xs font-bold tracking-widest text-gray-400 uppercase">Recent Projects</span>
                    <span class="text-xs text-gray-400">{{ $projects->count() }} {{ Str::plural('project', $projects->count()) }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @endif

            @php
                $firstSlide = $project->slides->first();
                $previewBlocks = $firstSlide ? $firstSlide->blocks->take(7) : collect();
            @endphp

            <div class="relative group">
                <a href="{{ route('editor.show', $project->slug) }}"
                   class="block rounded-2xl overflow-hidden transition-all duration-200 hover:-translate-y-0.5"
                   style="box-shadow:0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.05);"
                   onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,.10), 0 2px 6px rgba(0,0,0,.07)'"
                   onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.05)'">

                    {{-- Slide preview thumbnail --}}
                    <div class="relative overflow-hidden bg-white" style="aspect-ratio:16/9; padding:7% 8%;">

                        @if ($project->cover_path)
                            <img src="{{ asset('storage/' . $project->cover_path) }}"
                                 class="absolute inset-0 w-full h-full object-cover" alt="{{ $project->title }}">

                        @elseif ($previewBlocks->isEmpty())
                            {{-- Empty project skeleton --}}
                            <div class="absolute inset-0 flex flex-col justify-center items-center gap-2 px-8">
                                <div style="height:6px; background:#e2e8f0; border-radius:3px; width:58%;"></div>
                                <div style="height:3.5px; background:#f1f5f9; border-radius:2px; width:90%;"></div>
                                <div style="height:3.5px; background:#f1f5f9; border-radius:2px; width:76%;"></div>
                            </div>

                        @else
                            @foreach ($previewBlocks as $i => $block)
                                <div style="margin-bottom:5px;">
                                    @if ($block->type === 'text')
                                        @if ($i === 0)
                                            {{-- Title line --}}
                                            <div style="height:6px; background:#1e293b; border-radius:3px; width:62%;"></div>
                                        @else
                                            {{-- Body paragraph lines --}}
                                            <div style="height:3.5px; background:#cbd5e1; border-radius:2px; width:100%; margin-bottom:2px;"></div>
                                            <div style="height:3.5px; background:#e2e8f0; border-radius:2px; width:{{ [88,75,92,70,83][$i % 5] }}%;"></div>
                                        @endif
                                    @elseif ($block->type === 'code')
                                        <div style="background:#1e1e2e; border-radius:5px; padding:6px 7px;">
                                            <div style="display:flex; gap:4px; margin-bottom:5px;">
                                                <div style="width:6px;height:6px;border-radius:50%;background:#ff5f57;"></div>
                                                <div style="width:6px;height:6px;border-radius:50%;background:#febc2e;"></div>
                                                <div style="width:6px;height:6px;border-radius:50%;background:#28c840;"></div>
                                            </div>
                                            <div style="height:3px;background:#7c6af5;border-radius:2px;width:52%;margin-bottom:3px;"></div>
                                            <div style="height:3px;background:#4a4a6a;border-radius:2px;width:78%;margin-bottom:3px;"></div>
                                            <div style="height:3px;background:#4a4a6a;border-radius:2px;width:61%;"></div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif

                        {{-- Slide count badge --}}
                        @if ($project->slides->count() > 1)
                            <div class="absolute bottom-2 right-2.5 text-[9px] font-semibold text-gray-400 bg-white/80 backdrop-blur-sm px-1.5 py-0.5 rounded-full border border-gray-100 leading-none">
                                {{ $project->slides->count() }} slides
                            </div>
                        @endif

                        {{-- Subtle inner border overlay --}}
                        <div class="absolute inset-0 rounded-none pointer-events-none" style="box-shadow:inset 0 0 0 1px rgba(0,0,0,.06);"></div>
                    </div>

                    {{-- Card footer --}}
                    <div class="bg-white px-4 py-3.5 flex justify-between items-center border-t border-gray-100">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-900 truncate">{{ $project->title }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $project->updated_at->diffForHumans() }}</p>
                        </div>
                        <span class="ml-3 shrink-0 text-[9px] font-semibold tracking-widest uppercase px-2 py-1 rounded-full
                                     {{ $project->type === 'galaxy' ? 'bg-violet-50 text-violet-400' : 'bg-gray-100 text-gray-400' }}">
                            {{ $project->type }}
                        </span>
                    </div>
                </a>

                <div class="absolute top-2.5 right-2.5 z-10" @click.stop>
                    <button
                        @click="openDropdown = openDropdown === @js($project->slug) ? null : @js($project->slug)"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 bg-white border border-gray-100 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity"
                    >
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/></svg>
                    </button>

                    <div
                        x-show="openDropdown === @js($project->slug)"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 top-full mt-1.5 w-44 bg-white border border-gray-100 rounded-xl shadow-lg z-20 py-1 overflow-hidden"
                        style="display:none;"
                    >
                        <button
                            @click="openRename(@js($project->slug), @js($project->title))"
                            class="w-full flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                            Rename
                        </button>
                        <button
                            @click="share(@js($project->slug))"
                            class="w-full flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
                            Share
                        </button>
                        <div class="h-px bg-gray-100 my-1 mx-3"></div>
                        <button
                            @click="openDelete(@js($project->slug), @js($project->title))"
                            class="w-full flex items-center gap-2.5 px-3 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors"
                        >
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Delete
                        </button>
                    </div>
                </div>
            </div>

            @if ($loop->last)
                    <div
                        @click="openModal()"
                        class="rounded-2xl flex flex-col justify-center items-center bg-white hover:bg-gray-50 transition-all duration-200 cursor-pointer text-gray-400 hover:text-gray-600 border-2 border-dashed border-gray-200 hover:border-gray-300"
                        style="aspect-ratio:unset; min-height:220px;"
                    >
                        <div class="w-9 h-9 rounded-xl border border-gray-200 bg-white flex items-center justify-center mb-3 shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        </div>
                        <span class="text-sm font-medium">New project</span>
                    </div>
                </div>
            @endif

        @empty

            <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none">
                <div class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                </div>
                <p class="text-sm font-semibold text-slate-900 mb-1">No projects yet</p>
                <p class="text-sm text-gray-400">Create your first workspace to get started.</p>
            </div>

        @endforelse

    </div>

    </div>

    {{-- Create Modal --}}
    <div
        x-show="showModal"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-start justify-center pt-24 px-4"
        style="display:none;"
    >
        <div class="absolute inset-0 bg-black/20 backdrop-blur-sm" @click="closeModal()"></div>
        <div
            x-show="showModal"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 -translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
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
                    <input x-ref="titleInput" x-model="form.title" @keydown.enter="submitCreate()" type="text" placeholder="My awesome deck"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white placeholder-gray-300 text-slate-900 outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-900/10 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Type</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" @click="form.type = 'slides'"
                            :class="form.type === 'slides' ? 'border-slate-900 bg-slate-900 text-white' : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300 hover:text-slate-900'"
                            class="flex flex-col items-center gap-2 p-4 rounded-xl border text-sm font-medium transition-all">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                            Standard Slides
                        </button>
                        <button type="button" @click="form.type = 'galaxy'"
                            :class="form.type === 'galaxy' ? 'border-slate-900 bg-slate-900 text-white' : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300 hover:text-slate-900'"
                            class="flex flex-col items-center gap-2 p-4 rounded-xl border text-sm font-medium transition-all">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z"/></svg>
                            Galaxy Workspace
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 mt-6 pt-5 border-t border-gray-100">
                <button @click="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-slate-900 transition-colors">Cancel</button>
                <button @click="submitCreate()" :disabled="!form.title.trim()"
                    :class="!form.title.trim() ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-800'"
                    class="px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-lg transition-colors">
                    Create Project
                </button>
            </div>
        </div>
    </div>

    {{-- Rename Modal --}}
    <div
        x-show="showRenameModal"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-start justify-center pt-24 px-4"
        style="display:none;"
    >
        <div class="absolute inset-0 bg-black/20 backdrop-blur-sm" @click="closeRename()"></div>
        <div
            x-show="showRenameModal"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 -translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
            class="relative bg-white border border-gray-100 rounded-2xl shadow-xl w-full max-w-sm p-6"
            @click.stop
        >
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-semibold text-slate-900">Rename Project</h2>
                <button @click="closeRename()" class="p-1 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">New name</label>
                <input x-ref="renameInput" x-model="renameForm.title" @keydown.enter="submitRename()" type="text"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-slate-900 outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-900/10 transition-colors">
            </div>
            <div class="flex items-center justify-end gap-2 mt-5 pt-5 border-t border-gray-100">
                <button @click="closeRename()" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-slate-900 transition-colors">Cancel</button>
                <button @click="submitRename()" :disabled="!renameForm.title.trim()"
                    :class="!renameForm.title.trim() ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-800'"
                    class="px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-lg transition-colors">
                    Save
                </button>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div
        x-show="showDeleteModal"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-start justify-center pt-24 px-4"
        style="display:none;"
    >
        <div class="absolute inset-0 bg-black/20 backdrop-blur-sm" @click="closeDelete()"></div>
        <div
            x-show="showDeleteModal"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 -translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
            class="relative bg-white border border-gray-100 rounded-2xl shadow-xl w-full max-w-sm p-6"
            @click.stop
        >
            <div class="w-10 h-10 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center mb-4">
                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
            </div>
            <h2 class="text-base font-semibold text-slate-900 mb-1">Delete project?</h2>
            <p class="text-sm text-gray-400 mb-6">
                "<span class="text-slate-700 font-medium" x-text="deleteForm.title"></span>" will be permanently deleted. This cannot be undone.
            </p>
            <div class="flex items-center justify-end gap-2">
                <button @click="closeDelete()" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-slate-900 transition-colors">Cancel</button>
                <button @click="submitDelete()" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                    Delete
                </button>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div
        x-show="showToast"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
        :class="toastError ? 'border-red-100 bg-red-50' : 'border-gray-100 bg-white'"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-3 border rounded-xl shadow-lg px-4 py-3"
        style="display:none;"
    >
        <div x-show="toastLoading" class="w-4 h-4 border-2 border-gray-200 border-t-slate-900 rounded-full animate-spin shrink-0"></div>
        <svg x-show="!toastLoading && !toastError" class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
        <svg x-show="toastError" class="w-4 h-4 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
        <span class="text-sm font-medium" :class="toastError ? 'text-red-700' : 'text-slate-900'" x-text="toastMessage"></span>
    </div>

</div>

@endsection
