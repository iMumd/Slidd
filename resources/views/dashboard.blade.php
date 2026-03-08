<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slidd — Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', sans-serif; }
    </style>
</head>
<body class="h-full flex bg-white antialiased text-gray-900">

    <aside class="w-56 shrink-0 h-screen sticky top-0 flex flex-col border-r border-gray-200" style="background:#f4f4f5;">
        <div class="px-5 h-14 flex items-center border-b border-gray-200 shrink-0">
            <a href="/dashboard" class="flex items-center gap-1.5 select-none">
                <span class="text-base font-bold tracking-tight text-gray-900">Slidd</span>
            </a>
        </div>

        <nav class="flex-1 px-3 py-3 space-y-0.5 overflow-y-auto">
            <a href="/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-white text-gray-900 text-sm font-medium border border-gray-200 shadow-sm">
                <svg class="w-4 h-4 text-gray-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                </svg>
                Dashboard
            </a>
            <a href="/settings" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-500 text-sm font-medium hover:text-gray-900 hover:bg-white hover:border hover:border-gray-200 transition-all">
                <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>
                Settings
            </a>
        </nav>

        <div class="px-3 py-3 border-t border-gray-200 shrink-0">
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-gray-500 text-sm font-medium hover:text-gray-900 hover:bg-white hover:border hover:border-gray-200 transition-all text-left">
                    <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15"/>
                    </svg>
                    Log out
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 overflow-auto bg-white">
        <div class="max-w-5xl mx-auto px-10 py-10">

            <div class="flex items-start justify-between mb-12">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Good morning, {{ auth()->user()->name ?? 'there' }}</h1>
                    <p class="text-sm text-gray-400 mt-1">Here's what you've been working on.</p>
                </div>
                <a href="/projects/create" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-700 transition-colors shrink-0">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Create New Project
                </a>
            </div>

            <div class="flex items-center justify-between mb-5">
                <span class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Recent Projects</span>
                <a href="/projects" class="text-xs text-gray-400 hover:text-gray-700 transition-colors">View all</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <a href="/projects/1/editor" class="flex flex-col rounded-2xl border border-gray-200 overflow-hidden hover:border-gray-400 transition-all group cursor-pointer bg-white">
                    <div class="h-44 flex flex-col justify-between p-6" style="background:#f4f4f5;">
                        <div class="space-y-2.5">
                            <div class="h-2.5 bg-gray-300 rounded-full" style="width:62%;"></div>
                            <div class="space-y-1.5">
                                <div class="h-1.5 bg-gray-200 rounded-full w-full"></div>
                                <div class="h-1.5 bg-gray-200 rounded-full" style="width:85%;"></div>
                                <div class="h-1.5 bg-gray-200 rounded-full" style="width:70%;"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex gap-1">
                                <span class="w-5 h-3.5 rounded-sm bg-gray-300"></span>
                                <span class="w-5 h-3.5 rounded-sm bg-gray-200"></span>
                                <span class="w-5 h-3.5 rounded-sm bg-gray-200"></span>
                            </div>
                            <span class="text-[10px] font-medium text-gray-400">6 slides</span>
                        </div>
                    </div>
                    <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-gray-700 transition-colors">Product Roadmap 2026</p>
                            <p class="text-xs text-gray-400 mt-0.5">Edited 2 hours ago</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-500 transition-colors shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                        </svg>
                    </div>
                </a>

                <a href="/projects/2/editor" class="flex flex-col rounded-2xl border border-gray-200 overflow-hidden hover:border-gray-400 transition-all group cursor-pointer bg-white">
                    <div class="h-44 flex flex-col justify-between p-6" style="background:#f4f4f5;">
                        <div class="space-y-2.5">
                            <div class="h-2.5 bg-gray-300 rounded-full" style="width:55%;"></div>
                            <div class="space-y-1.5">
                                <div class="h-1.5 bg-gray-200 rounded-full w-full"></div>
                                <div class="h-1.5 bg-gray-200 rounded-full" style="width:90%;"></div>
                                <div class="h-1.5 bg-gray-200 rounded-full" style="width:60%;"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex gap-1">
                                <span class="w-5 h-3.5 rounded-sm bg-gray-300"></span>
                                <span class="w-5 h-3.5 rounded-sm bg-gray-200"></span>
                                <span class="w-5 h-3.5 rounded-sm bg-gray-200"></span>
                                <span class="w-5 h-3.5 rounded-sm bg-gray-200"></span>
                            </div>
                            <span class="text-[10px] font-medium text-gray-400">12 slides</span>
                        </div>
                    </div>
                    <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-gray-700 transition-colors">Q1 Investor Deck</p>
                            <p class="text-xs text-gray-400 mt-0.5">Edited yesterday</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-500 transition-colors shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                        </svg>
                    </div>
                </a>

                <a href="/projects/3/editor" class="flex flex-col rounded-2xl border border-gray-200 overflow-hidden hover:border-gray-400 transition-all group cursor-pointer bg-white">
                    <div class="h-44 flex flex-col justify-between p-6" style="background:#f4f4f5;">
                        <div class="space-y-2.5">
                            <div class="h-2.5 bg-gray-300 rounded-full" style="width:72%;"></div>
                            <div class="space-y-1.5">
                                <div class="h-1.5 bg-gray-200 rounded-full" style="width:80%;"></div>
                                <div class="h-1.5 bg-gray-200 rounded-full w-full"></div>
                                <div class="h-1.5 bg-gray-200 rounded-full" style="width:45%;"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex gap-1">
                                <span class="w-5 h-3.5 rounded-sm bg-gray-300"></span>
                                <span class="w-5 h-3.5 rounded-sm bg-gray-200"></span>
                            </div>
                            <span class="text-[10px] font-medium text-gray-400">4 slides</span>
                        </div>
                    </div>
                    <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-gray-700 transition-colors">Team Onboarding</p>
                            <p class="text-xs text-gray-400 mt-0.5">Edited 3 days ago</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-500 transition-colors shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                        </svg>
                    </div>
                </a>

                <a href="/projects/4/editor" class="flex flex-col rounded-2xl border border-gray-200 overflow-hidden hover:border-gray-400 transition-all group cursor-pointer bg-white">
                    <div class="h-44 flex flex-col justify-between p-6" style="background:#f4f4f5;">
                        <div class="space-y-2.5">
                            <div class="h-2.5 bg-gray-300 rounded-full" style="width:50%;"></div>
                            <div class="space-y-1.5">
                                <div class="h-1.5 bg-gray-200 rounded-full" style="width:95%;"></div>
                                <div class="h-1.5 bg-gray-200 rounded-full" style="width:75%;"></div>
                                <div class="h-1.5 bg-gray-200 rounded-full" style="width:55%;"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex gap-1">
                                <span class="w-5 h-3.5 rounded-sm bg-gray-300"></span>
                                <span class="w-5 h-3.5 rounded-sm bg-gray-200"></span>
                                <span class="w-5 h-3.5 rounded-sm bg-gray-200"></span>
                                <span class="w-5 h-3.5 rounded-sm bg-gray-200"></span>
                                <span class="w-5 h-3.5 rounded-sm bg-gray-200"></span>
                            </div>
                            <span class="text-[10px] font-medium text-gray-400">9 slides</span>
                        </div>
                    </div>
                    <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-gray-700 transition-colors">API Documentation</p>
                            <p class="text-xs text-gray-400 mt-0.5">Edited 1 week ago</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-500 transition-colors shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                        </svg>
                    </div>
                </a>

                <a href="/projects/5/editor" class="flex flex-col rounded-2xl border border-gray-200 overflow-hidden hover:border-gray-400 transition-all group cursor-pointer bg-white">
                    <div class="h-44 flex flex-col justify-between p-6" style="background:#f4f4f5;">
                        <div class="space-y-2.5">
                            <div class="h-2.5 bg-gray-300 rounded-full" style="width:65%;"></div>
                            <div class="space-y-1.5">
                                <div class="h-1.5 bg-gray-200 rounded-full" style="width:70%;"></div>
                                <div class="h-1.5 bg-gray-200 rounded-full w-full"></div>
                                <div class="h-1.5 bg-gray-200 rounded-full" style="width:80%;"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex gap-1">
                                <span class="w-5 h-3.5 rounded-sm bg-gray-300"></span>
                                <span class="w-5 h-3.5 rounded-sm bg-gray-200"></span>
                                <span class="w-5 h-3.5 rounded-sm bg-gray-200"></span>
                            </div>
                            <span class="text-[10px] font-medium text-gray-400">5 slides</span>
                        </div>
                    </div>
                    <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-gray-700 transition-colors">Design Principles</p>
                            <p class="text-xs text-gray-400 mt-0.5">Edited 2 weeks ago</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-500 transition-colors shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                        </svg>
                    </div>
                </a>

                <a href="/projects/create" class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-gray-200 h-full min-h-[200px] hover:border-gray-400 hover:bg-gray-50 transition-all group cursor-pointer">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 group-hover:bg-gray-200 transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-400 group-hover:text-gray-600 transition-colors">New project</p>
                    </div>
                </a>

            </div>

        </div>
    </main>

</body>
</html>
