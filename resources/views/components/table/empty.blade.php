@props(['colspan' => 1, 'message' => 'Không có dữ liệu'])
<tr>
    <td colspan="{{ $colspan }}" class="px-6 py-10 text-center text-sm font-medium text-gray-500 dark:text-gray-400">
        <div class="flex flex-col items-center justify-center">
            <x-icon name="search" class="h-10 w-10 text-gray-400" />
            <p class="mt-2">{{ $message }}</p>
        </div>
    </td>
</tr>
