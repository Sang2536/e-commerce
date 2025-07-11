@props(['src' => null, 'alt' => '', 'size' => 8])

@if ($src)
    <img src="{{ $src }}" alt="{{ $alt }}" class="h-{{ $size }} w-{{ $size }} rounded-full object-cover">
@else
    <div class="h-{{ $size }} w-{{ $size }} rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
        </svg>
    </div>
@endif
