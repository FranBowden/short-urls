<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Short URLs') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        }
    </script>
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white">

    {{-- Nav --}}
    <nav class="absolute top-0 inset-x-0 flex items-center justify-between px-6 py-4 z-10">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/Icon-Blue.png') }}" alt="Logo" class="dark:hidden h-8 w-8" />
            <img src="{{ asset('images/Icon-White.png') }}" alt="Logo" class="hidden dark:block h-8 w-8" />
            <span class="font-bold text-lg">Short URLs</span>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">Log in</a>
            <a href="{{ route('register') }}" class="text-sm font-semibold px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">Sign up</a>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="min-h-screen flex flex-col items-center justify-center px-4 pt-20 pb-16">
        <div class="w-full max-w-2xl text-center">

            <div class="inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 text-sm font-medium px-3 py-1.5 rounded-full mb-6">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/></svg>
                No account needed to get started
            </div>

            <h1 class="text-5xl sm:text-6xl font-extrabold tracking-tight mb-4 leading-tight">
                Shorten any link<br>
                <span class="text-blue-600">in seconds</span>
            </h1>
            <p class="text-lg text-gray-500 dark:text-gray-400 mb-10">
                Paste a long URL and get a clean, shareable link instantly.<br class="hidden sm:block">
                No sign-up required for your first {{ $linksRemaining + $linksUsed }} links.
            </p>

            {{-- Shortener form --}}
            <div
                x-data="{
                    url: '',
                    result: null,
                    error: null,
                    loading: false,
                    copied: false,
                    linksUsed: {{ $linksUsed }},
                    linksRemaining: {{ $linksRemaining }},
                    get limitReached() { return this.linksRemaining <= 0 },
                    async submit() {
                        if (!this.url || this.limitReached) return
                        this.loading = true
                        this.result = null
                        this.error = null
                        try {
                            const res = await fetch('{{ route('guest.shorten') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ original_url: this.url }),
                            })
                            const data = await res.json()
                            if (!res.ok) {
                                this.error = data.message
                            } else {
                                this.result = data.short_url
                                this.linksUsed = data.links_used
                                this.linksRemaining = data.links_remaining
                                this.url = ''
                            }
                        } catch {
                            this.error = 'Something went wrong. Please try again.'
                        }
                        this.loading = false
                    },
                    async copy() {
                        await navigator.clipboard.writeText(this.result)
                        this.copied = true
                        setTimeout(() => this.copied = false, 2000)
                    }
                }"
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 text-left"
            >
                {{-- Limit bar --}}
                <div class="flex items-center justify-between mb-4 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Free links</span>
                    <div class="flex items-center gap-2">
                        <div class="flex gap-1">
                            <template x-for="i in 3">
                                <div
                                    :class="i <= linksUsed ? 'bg-blue-500' : 'bg-gray-200 dark:bg-gray-700'"
                                    class="w-5 h-1.5 rounded-full transition-colors duration-300"
                                ></div>
                            </template>
                        </div>
                        <span class="text-gray-500 dark:text-gray-400" x-text="linksUsed + '/3 used'"></span>
                    </div>
                </div>

                {{-- Form --}}
                <form @submit.prevent="submit" class="flex flex-col sm:flex-row gap-3">
                    <input
                        x-model="url"
                        type="url"
                        placeholder="https://your-very-long-url.com/paste-it-here"
                        :disabled="limitReached || loading"
                        class="flex-1 px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white placeholder-gray-400 disabled:opacity-50 text-sm"
                        required
                    >
                    <button
                        type="submit"
                        :disabled="limitReached || loading"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold rounded-xl transition text-sm whitespace-nowrap"
                    >
                        <span x-show="!loading">Shorten</span>
                        <span x-show="loading">Shortening…</span>
                    </button>
                </form>

                {{-- Result --}}
                <div x-show="result" x-transition class="mt-4">
                    <div class="flex items-center gap-3 bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900 rounded-xl px-4 py-3">
                        <a :href="result" target="_blank" x-text="result" class="flex-1 text-blue-600 dark:text-blue-400 text-sm font-medium truncate hover:underline"></a>
                        <button
                            @click="copy"
                            class="shrink-0 text-xs font-semibold px-3 py-1.5 rounded-lg transition"
                            :class="copied ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-900/60'"
                            x-text="copied ? 'Copied!' : 'Copy'"
                        ></button>
                    </div>
                    <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">This link expires in 30 days. <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Sign up</a> for permanent links.</p>
                </div>

                {{-- Error / limit reached --}}
                <div x-show="error" x-transition class="mt-4">
                    <p class="text-sm text-red-600 dark:text-red-400" x-text="error"></p>
                </div>

                <div x-show="limitReached" x-transition class="mt-4 p-4 bg-amber-50 dark:bg-amber-950/30 border border-amber-100 dark:border-amber-900 rounded-xl text-center">
                    <p class="text-sm font-medium text-amber-800 dark:text-amber-300 mb-2">You've used all 3 free links!</p>
                    <a href="{{ route('register') }}" class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
                        Sign up for unlimited links →
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-16 px-4 border-t border-gray-100 dark:border-gray-800">
        <div class="max-w-4xl mx-auto grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
            <div>
                <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 dark:bg-blue-900/40 rounded-xl mb-4">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="font-semibold mb-1">Instant</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Links are generated immediately, no waiting around.</p>
            </div>
            <div>
                <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 dark:bg-blue-900/40 rounded-xl mb-4">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h3 class="font-semibold mb-1">Track visits</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">See how many times your links have been clicked.</p>
            </div>
            <div>
                <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 dark:bg-blue-900/40 rounded-xl mb-4">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-semibold mb-1">Expiry control</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Set links to expire after an hour, a day, or never.</p>
            </div>
        </div>

        <div class="text-center mt-12">
            <p class="text-gray-500 dark:text-gray-400 mb-4">Want unlimited links, visit tracking, and full history?</p>
            <a href="{{ route('register') }}" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition">
                Create a free account →
            </a>
        </div>
    </section>

</body>

</html>
