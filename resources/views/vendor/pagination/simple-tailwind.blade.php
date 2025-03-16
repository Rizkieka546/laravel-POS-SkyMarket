@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center space-x-2 mt-6">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
    <span class="px-4 py-2 text-gray-400 bg-gray-200 rounded-lg cursor-not-allowed">
        ← Prev
    </span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
        class="px-4 py-2 text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition">
        ← Prev
    </a>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
    @if (is_string($element))
    <span class="px-4 py-2 text-gray-500">{{ $element }}</span>
    @endif

    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <span class="px-4 py-2 bg-teal-500 text-white rounded-lg font-semibold">
        {{ $page }}
    </span>
    @else
    <a href="{{ $url }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-300 transition">
        {{ $page }}
    </a>
    @endif
    @endforeach
    @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
        class="px-4 py-2 text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition">
        Next →
    </a>
    @else
    <span class="px-4 py-2 text-gray-400 bg-gray-200 rounded-lg cursor-not-allowed">
        Next →
    </span>
    @endif
</nav>
@endif