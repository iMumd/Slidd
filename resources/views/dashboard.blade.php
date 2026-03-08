@extends('layouts.app')

@section('title', 'Dashboard — Slidd')

@section('content')

<div class="p-10 pb-0 flex items-start justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Good morning, {{ Auth::user()->name }}.</h1>
        <p class="text-sm text-gray-400 mt-1">Here's what you've been working on.</p>
    </div>
    <button class="bg-slate-900 text-white text-sm font-medium py-2 px-4 rounded-lg shadow-sm hover:bg-slate-800 transition-colors">
        + Create New Project
    </button>
</div>

<div class="p-10">
    <div class="flex items-center justify-between mb-6">
        <span class="text-xs font-bold tracking-widest text-gray-400 uppercase">Recent Projects</span>
        <span class="text-xs text-gray-400 cursor-pointer hover:text-slate-900 transition-colors">View all</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="border border-gray-100 rounded-2xl overflow-hidden hover:shadow-md transition-shadow cursor-pointer flex flex-col">
            <div class="bg-gray-50 h-36 p-4 flex flex-col justify-between">
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
                    <span class="text-[10px] text-gray-400">8 slides</span>
                </div>
            </div>
            <div class="bg-white p-4 flex justify-between items-center">
                <div>
                    <p class="text-sm font-semibold text-slate-900">Design System 2026</p>
                    <p class="text-xs text-gray-400 mt-0.5">Edited 2 hours ago</p>
                </div>
                <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </div>
        </div>

        <div class="border border-gray-100 rounded-2xl overflow-hidden hover:shadow-md transition-shadow cursor-pointer flex flex-col">
            <div class="bg-gray-50 h-36 p-4 flex flex-col justify-between">
                <div>
                    <div class="h-1.5 bg-gray-200 rounded-full w-4/5 mb-2"></div>
                    <div class="h-1.5 bg-gray-200 rounded-full w-3/5 mb-2"></div>
                    <div class="h-1.5 bg-gray-200 rounded-full w-1/2"></div>
                </div>
                <div class="flex justify-between items-end">
                    <div class="flex gap-1">
                        <div class="w-4 h-3 bg-gray-200 rounded-sm"></div>
                        <div class="w-4 h-3 bg-gray-200 rounded-sm"></div>
                        <div class="w-4 h-3 bg-gray-200 rounded-sm"></div>
                    </div>
                    <span class="text-[10px] text-gray-400">12 slides</span>
                </div>
            </div>
            <div class="bg-white p-4 flex justify-between items-center">
                <div>
                    <p class="text-sm font-semibold text-slate-900">Product Launch Deck</p>
                    <p class="text-xs text-gray-400 mt-0.5">Edited yesterday</p>
                </div>
                <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </div>
        </div>

        <div class="border border-gray-100 rounded-2xl overflow-hidden hover:shadow-md transition-shadow cursor-pointer flex flex-col">
            <div class="bg-gray-50 h-36 p-4 flex flex-col justify-between">
                <div>
                    <div class="h-1.5 bg-gray-200 rounded-full w-2/3 mb-2"></div>
                    <div class="h-1.5 bg-gray-200 rounded-full w-4/5 mb-2"></div>
                    <div class="h-1.5 bg-gray-200 rounded-full w-2/5"></div>
                </div>
                <div class="flex justify-between items-end">
                    <div class="flex gap-1">
                        <div class="w-4 h-3 bg-gray-200 rounded-sm"></div>
                        <div class="w-4 h-3 bg-gray-200 rounded-sm"></div>
                        <div class="w-4 h-3 bg-gray-200 rounded-sm"></div>
                    </div>
                    <span class="text-[10px] text-gray-400">5 slides</span>
                </div>
            </div>
            <div class="bg-white p-4 flex justify-between items-center">
                <div>
                    <p class="text-sm font-semibold text-slate-900">Q1 Planning</p>
                    <p class="text-xs text-gray-400 mt-0.5">Edited 3 days ago</p>
                </div>
                <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </div>
        </div>

        <div class="border border-gray-100 rounded-2xl overflow-hidden hover:shadow-md transition-shadow cursor-pointer flex flex-col">
            <div class="bg-gray-50 h-36 p-4 flex flex-col justify-between">
                <div>
                    <div class="h-1.5 bg-gray-200 rounded-full w-3/5 mb-2"></div>
                    <div class="h-1.5 bg-gray-200 rounded-full w-3/4 mb-2"></div>
                    <div class="h-1.5 bg-gray-200 rounded-full w-1/3"></div>
                </div>
                <div class="flex justify-between items-end">
                    <div class="flex gap-1">
                        <div class="w-4 h-3 bg-gray-200 rounded-sm"></div>
                        <div class="w-4 h-3 bg-gray-200 rounded-sm"></div>
                        <div class="w-4 h-3 bg-gray-200 rounded-sm"></div>
                    </div>
                    <span class="text-[10px] text-gray-400">9 slides</span>
                </div>
            </div>
            <div class="bg-white p-4 flex justify-between items-center">
                <div>
                    <p class="text-sm font-semibold text-slate-900">Engineering Onboarding</p>
                    <p class="text-xs text-gray-400 mt-0.5">Edited last week</p>
                </div>
                <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </div>
        </div>

        <div class="border border-gray-100 rounded-2xl overflow-hidden hover:shadow-md transition-shadow cursor-pointer flex flex-col">
            <div class="bg-gray-50 h-36 p-4 flex flex-col justify-between">
                <div>
                    <div class="h-1.5 bg-gray-200 rounded-full w-1/2 mb-2"></div>
                    <div class="h-1.5 bg-gray-200 rounded-full w-4/5 mb-2"></div>
                    <div class="h-1.5 bg-gray-200 rounded-full w-3/5"></div>
                </div>
                <div class="flex justify-between items-end">
                    <div class="flex gap-1">
                        <div class="w-4 h-3 bg-gray-200 rounded-sm"></div>
                        <div class="w-4 h-3 bg-gray-200 rounded-sm"></div>
                        <div class="w-4 h-3 bg-gray-200 rounded-sm"></div>
                    </div>
                    <span class="text-[10px] text-gray-400">6 slides</span>
                </div>
            </div>
            <div class="bg-white p-4 flex justify-between items-center">
                <div>
                    <p class="text-sm font-semibold text-slate-900">Investor Update</p>
                    <p class="text-xs text-gray-400 mt-0.5">Edited 2 weeks ago</p>
                </div>
                <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </div>
        </div>

        <div class="border border-gray-100 rounded-2xl flex flex-col justify-center items-center min-h-[220px] bg-white hover:bg-gray-50 transition-colors cursor-pointer text-gray-400 hover:text-gray-500">
            <div class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center mb-2.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </div>
            <span class="text-sm font-medium">New project</span>
        </div>

    </div>
</div>

@endsection
