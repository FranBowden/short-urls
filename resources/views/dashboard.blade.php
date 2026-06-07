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
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Shorten
                    </button>
                </form>

                @error('original_url')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Short URLs list --}}
            @if ($shortUrls->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Your Short URLs</h3>

                <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                    <thead>
                        <tr class="border-b dark:border-gray-600">
                            <th class="pb-2 font-medium">Original URL</th>
                            <th class="pb-2 font-medium">Short URL</th>
                            <th class="pb-2 font-medium">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shortUrls as $shortUrl)
                        <tr class="border-b dark:border-gray-700">
                            <td class="py-2 max-w-xs truncate">{{ $shortUrl->original_url }}</td>
                            <td class="py-2">
                                <a href="{{ url($shortUrl->short_code) }}" class="text-blue-500 hover:underline" target="_blank">
                                    {{ url($shortUrl->short_code) }}
                                </a>
                            </td>
                            <td class="py-2">{{ $shortUrl->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>