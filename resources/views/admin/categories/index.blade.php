<x-admin-layout>
    <x-slot name="header">Quản lý danh mục</x-slot>

    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Danh sách danh mục</h2>
            <span class="ml-4 px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                {{ $categories->total() }} danh mục
            </span>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
            <span class="flex items-center">
                <x-icon name="plus" class="h-5 w-5 mr-1" />
                Thêm danh mục mới
            </span>
        </a>
    </div>

    <x-card class="mb-6">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <x-form.input name="search" label="Tìm kiếm" :value="request('search')" placeholder="Tên danh mục..." />

            <x-form.select name="status" label="Trạng thái">
                <option value="">Tất cả</option>
                <option value="active" @selected(request('status') === 'active')>Hoạt động</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Không hoạt động</option>
            </x-form.select>

            <x-buttons.button class="md:self-end">Lọc kết quả</x-buttons.button>
        </form>
    </x-card>

    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <x-table.th>ID</x-table.th>
                <x-table.th>Tên danh mục</x-table.th>
                <x-table.th>Slug</x-table.th>
                <x-table.th>Danh mục cha</x-table.th>
                <x-table.th>Trạng thái</x-table.th>
                <x-table.th>Sản phẩm</x-table.th>
                <x-table.th class="text-right">Thao tác</x-table.th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
            @forelse ($categories as $category)
                <tr>
                    <x-table.td>{{ $category->id }}</x-table.td>
                    <x-table.td>
                        <div class="flex items-center">
                            <x-avatar :src="$category->image ? Storage::url($category->image) : null" :alt="$category->name" />
                            <span class="ml-2 text-gray-900 dark:text-white">{{ $category->name }}</span>
                        </div>
                    </x-table.td>
                    <x-table.td>{{ $category->slug }}</x-table.td>
                    <x-table.td>{{ $category->parent->name ?? 'Không có' }}</x-table.td>
                    <x-table.td>
                        <x-badge :color="$category->is_active ? 'green' : 'red'">
                            {{ $category->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                        </x-badge>
                    </x-table.td>
                    <x-table.td>{{ $category->products_count }}</x-table.td>
                    <x-table.td class="text-right">
                        <x-buttons.group>
                            <x-buttons.link :href="route('admin.categories.edit', $category)" icon="edit" />
                            <x-buttons.delete :action="route('admin.categories.destroy', $category)" :confirm="'Bạn có chắc chắn muốn xóa danh mục ' . $category->name . '?'" />
                        </x-buttons.group>
                    </x-table.td>
                </tr>
            @empty
                <x-table.empty colspan="7" message="Không tìm thấy danh mục nào" link="{{ route('admin.categories.create') }}" />
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $categories->links() }}
    </div>
</x-admin-layout>
