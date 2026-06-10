<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Success message --}}
            @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
            @endif

            {{-- Shorten URL form --}}
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Shorten a URL</h3>

                <form method="POST" action="{{ route('dashboard.store') }}" class="flex gap-3">
                    @csrf
                    <input
                        type="url"
                        name="original_url"
                        value="{{ old('original_url') }}"
                        placeholder="https://example.com/your-long-url"
                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                        required>
                    <select
                        name="timeout"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="0" {{ old('timeout', '0') === '0' ? 'selected' : '' }}>No expiry</option>
                        <option value="1" {{ old('timeout') === '1' ? 'selected' : '' }}>1 hour</option>
                        <option value="24" {{ old('timeout') === '24' ? 'selected' : '' }}>1 day</option>
                        <option value="168" {{ old('timeout') === '168' ? 'selected' : '' }}>1 week</option>
                        <option value="720" {{ old('timeout') === '720' ? 'selected' : '' }}>1 month</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Shorten
                    </button>
                </form>

                @error('original_url')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Active URLs --}}
            @if ($shortUrls->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Your Active Short URLs</h3>

                <div class="space-y-3">
                    @foreach ($shortUrls as $shortUrl)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-start justify-between gap-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate min-w-0">{{ $shortUrl->original_url }}</p>
                            <span class="text-xs text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded-full shrink-0">{{ $shortUrl->expiryLabel() }}</span>
                        </div>
                        <div class="flex items-center justify-between mt-2 gap-4">
                            <a href="{{ url($shortUrl->short_code) }}" class="text-sm font-medium text-blue-500 hover:underline" target="_blank">
                                {{ url($shortUrl->short_code) }}
                            </a>
                            <div class="flex items-center gap-4 text-xs text-gray-400 dark:text-gray-500 shrink-0">
                                <span>{{ $shortUrl->created_at->diffForHumans() }}</span>
                                <span data-visits="{{ $shortUrl->id }}">{{ $shortUrl->visits_count }} visits</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Expired URLs --}}
            @if ($expiredUrls->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Expired URLs</h3>

                <div class="space-y-3">
                    @foreach ($expiredUrls as $expiredUrl)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 opacity-60">
                        <div class="flex items-start justify-between gap-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate min-w-0">{{ $expiredUrl->original_url }}</p>
                            <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full shrink-0">{{ $expiredUrl->expiryLabel() }}</span>
                        </div>
                        <div class="flex items-center justify-between mt-2 gap-4">
                            <span class="text-sm font-medium text-gray-400 dark:text-gray-500">
                                {{ url($expiredUrl->short_code) }}
                            </span>
                            <div class="flex items-center gap-4 text-xs text-gray-400 dark:text-gray-500 shrink-0">
                                <span>Expired {{ $expiredUrl->expired_at->diffForHumans() }}</span>
                                <span data-visits="{{ $expiredUrl->id }}">{{ $expiredUrl->visits_count }} visits</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
    @if ($shortUrls->isNotEmpty() || $expiredUrls->isNotEmpty())
    <div data-visit-counts-url="{{ route('dashboard.visit-counts') }}" hidden></div>
    <script>
        const url = document.querySelector('[data-visit-counts-url]').dataset.visitCountsUrl
        setInterval(() => {
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(counts => {
                    document.querySelectorAll('[data-visits]').forEach(el => {
                        const id = el.dataset.visits
                        if (counts[id] !== undefined) el.textContent = counts[id] + ' visits'
                    })
                })
        }, 5000)
    </script>
    @endif
</x-app-layout>
