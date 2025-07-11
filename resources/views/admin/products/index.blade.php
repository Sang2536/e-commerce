<x-admin-layout>
    <x-slot name="header">Quản lý sản phẩm</x-slot>

    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Danh sách sản phẩm</h2>
            <span class="ml-4 px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                {{ $products->total() }} sản phẩm
            </span>
        </div>
        <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
            <span class="flex items-center">
                <x-icon name="plus" class="h-5 w-5 mr-1" />
                Thêm sản phẩm mới
            </span>
        </a>
    </div>

    <x-card class="mb-6">
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <x-form.input name="search" label="Tìm kiếm" :value="request('search')" placeholder="Tên sản phẩm..." />

            <x-form.select name="category_id" label="Danh mục">
                <option value="">Tất cả</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </x-form.select>

            <x-form.select name="status" label="Trạng thái">
                <option value="">Tất cả</option>
                <option value="active" @selected(request('status') === 'active')>Hiển thị</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Ẩn</option>
            </x-form.select>

            <x-buttons.button class="md:self-end">Lọc kết quả</x-buttons.button>
        </form>
    </x-card>

    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <x-table.th>ID</x-table.th>
                <x-table.th>Hình ảnh</x-table.th>
                <x-table.th>Tên sản phẩm</x-table.th>
                <x-table.th>Danh mục</x-table.th>
                <x-table.th>Giá</x-table.th>
                <x-table.th>Trạng thái</x-table.th>
                <x-table.th class="text-right">Thao tác</x-table.th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
            @forelse ($products as $product)
                <tr>
                    <x-table.td>{{ $product->id }}</x-table.td>
                    <x-table.td>
                        <x-avatar :src="$product->image ? Storage::url($product->image) : null" :alt="$product->name" />
                    </x-table.td>
                    <x-table.td class="text-gray-900 dark:text-white">{{ $product->name }}</x-table.td>
                    <x-table.td>{{ $product->category->name ?? 'Không có' }}</x-table.td>
                    <x-table.td>{{ number_format($product->price) }} đ</x-table.td>
                    <x-table.td>
                        <x-badge :color="$product->is_active ? 'green' : 'red'">
                            {{ $product->is_active ? 'Hiển thị' : 'Ẩn' }}
                        </x-badge>
                    </x-table.td>
                    <x-table.td class="text-right">
                        <x-buttons.group>
                            <x-buttons.link :href="route('admin.products.edit', $product)" icon="edit" />
                            <x-buttons.delete
                                :action="route('admin.products.destroy', $product)"
                                :confirm="'Bạn có chắc chắn muốn xóa sản phẩm ' . $product->name . '?'"
                            />
                        </x-buttons.group>
                    </x-table.td>
                </tr>
            @empty
                <x-table.empty colspan="7" message="Không tìm thấy sản phẩm nào" link="{{ route('admin.products.create') }}" />
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $products->links() }}
    </div>
</x-admin-layout>
