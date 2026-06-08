<x-guest-layout>
    <div class="text-center py-4">
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
            Oops, this URL has expired
        </h1>

        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
            Sorry, this short link is no longer active.
        </p>

        <a href="{{ route('dashboard') }}" class="text-sm text-blue-500 hover:underline">
            Create a new short URL
        </a>
    </div>
</x-guest-layout>