<div class="pt-4">
    <button type="submit"
        {{ $attributes->merge(['class' => 'px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring']) }}>
        {{ $slot ?? 'Lưu' }}
    </button>
</div>
