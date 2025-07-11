@props(['actions' => []])

<div class="flex justify-end space-x-2">
    @foreach ($actions as $action)
        @if ($action['type'] === 'link')
            <a href="{{ $action['url'] }}" class="{{ $action['class'] }}" title="{{ $action['title'] }}">
                {!! $action['icon'] ?? '' !!}
            </a>
        @elseif ($action['type'] === 'button')
            <button onclick="{{ $action['onclick'] }}" class="{{ $action['class'] }}" title="{{ $action['title'] }}">
                {!! $action['icon'] ?? '' !!}
            </button>
        @endif
    @endforeach
</div>
