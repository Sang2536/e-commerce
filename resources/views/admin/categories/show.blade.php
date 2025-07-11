<x-admin-layout>
    <x-slot name="header">Chi tiết danh mục</x-slot>
<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chi tiết danh mục') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="mb-4">
                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Quay lại danh sách
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 bg-gray-50">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Thông tin danh mục</h3>
                        </div>
                        <div class="border-t border-gray-200">
                            <dl>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $category->id }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Tên danh mục</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $category->name }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Slug</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $category->slug }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Danh mục cha</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        @if($category->parent)
                                            <a href="{{ route('admin.categories.show', $category->parent) }}" class="text-indigo-600 hover:text-indigo-900">{{ $category->parent->name }}</a>
                                        @else
                                            Không có
                                        @endif
                                    </dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Số danh mục con</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $category->children_count }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Số sản phẩm</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $category->products_count }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Trạng thái</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        @if($category->is_active)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hoạt động</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ẩn</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Ngày tạo</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $category->created_at->format('d/m/Y H:i:s') }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Cập nhật lần cuối</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $category->updated_at->format('d/m/Y H:i:s') }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Mô tả</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {!! $category->description ?? 'Không có mô tả' !!}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    @if($category->children->count() > 0)
                        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Danh mục con</h3>
                            </div>
                            <div class="border-t border-gray-200">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên danh mục</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số sản phẩm</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($category->children as $child)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $child->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $child->products_count }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @if($child->is_active)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hoạt động</span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ẩn</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('admin.categories.show', $child) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Xem</a>
                                                    <a href="{{ route('admin.categories.edit', $child) }}" class="text-yellow-600 hover:text-yellow-900">Sửa</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div>
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        @if($category->image)
                            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Hình ảnh danh mục</h3>
                            </div>
                            <div class="border-t border-gray-200 p-4">
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-auto rounded">
                            </div>
                        @endif

                        <div class="px-4 py-5 sm:px-6 bg-gray-50">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Thao tác</h3>
                        </div>
                        <div class="border-t border-gray-200 p-4">
                            <div class="flex flex-col space-y-3">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center justify-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-500 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Chỉnh sửa danh mục
                                </a>

                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Xóa danh mục
                                    </button>
                                </form>

                                @if($category->is_active)
                                    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="name" value="{{ $category->name }}">
                                        <input type="hidden" name="slug" value="{{ $category->slug }}">
                                        <input type="hidden" name="description" value="{{ $category->description }}">
                                        <input type="hidden" name="parent_id" value="{{ $category->parent_id }}">
                                        <input type="hidden" name="is_active" value="0">
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                            Ẩn danh mục
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="name" value="{{ $category->name }}">
                                        <input type="hidden" name="slug" value="{{ $category->slug }}">
                                        <input type="hidden" name="description" value="{{ $category->description }}">
                                        <input type="hidden" name="parent_id" value="{{ $category->parent_id }}">
                                        <input type="hidden" name="is_active" value="1">
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Hiển thị danh mục
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
    <div class="max-w-3xl mx-auto">
        <!-- Thông tin cơ bản -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm mb-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Thông tin danh mục</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Sửa
                    </a>
                    <button onclick="confirmDelete()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Xóa
                    </button>
                    <form id="delete-form" action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $category->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tên danh mục</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $category->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Slug</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $category->slug }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Danh mục cha</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                @if ($category->parent)
                                    <a href="{{ route('admin.categories.show', $category->parent) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        {{ $category->parent->name }}
                                    </a>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">Không có</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Trạng thái</dt>
                            <dd class="mt-1 text-sm">
                                <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $category->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                    {{ $category->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Thứ tự hiển thị</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $category->order }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ngày tạo</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $category->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cập nhật lần cuối</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $category->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Ảnh đại diện</h3>
                        @if ($category->image)
                            <div class="rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 h-48 w-full">
                                <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 h-48 w-full flex items-center justify-center bg-gray-50 dark:bg-gray-800">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Không có ảnh</span>
                            </div>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Mô tả</h3>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md text-sm text-gray-900 dark:text-white">
                            {{ $category->description ?: 'Không có mô tả' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Metadata -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm mb-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Thông tin SEO</h2>

            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Meta Title</h3>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                        {{ $category->meta_title ?: $category->name }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Meta Description</h3>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                        {{ $category->meta_description ?: ($category->description ?: 'Không có mô tả') }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">URL Đầy đủ</h3>
                    <p class="mt-1 text-sm text-indigo-600 dark:text-indigo-400 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <a href="{{ route('products.category', $category->slug) }}" target="_blank" class="hover:underline">
                            {{ route('products.category', $category->slug) }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z" />
                                <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" />
                            </svg>
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Danh mục con -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Danh mục con</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                    {{ $category->children->count() }}
                </span>
            </div>

            @if ($category->children->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">Tên danh mục</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">Trạng thái</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">Sản phẩm</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4"><span class="sr-only">Xem</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($category->children as $child)
                                <tr>
                                    <td class="py-4 pl-4 pr-3 text-sm">
                                        <div class="flex items-center">
                                            @if ($child->image)
                                                <img src="{{ Storage::url($child->image) }}" alt="{{ $child->name }}" class="h-8 w-8 rounded-full mr-2 object-cover">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-600 mr-2 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $child->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 text-sm">
                                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $child->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                            {{ $child->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $child->products_count ?? 0 }}
                                    </td>
                                    <td class="py-4 pl-3 pr-4 text-right text-sm font-medium">
                                        <a href="{{ route('admin.categories.show', $child) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 rounded-md">
                    Không có danh mục con nào
                </div>
            @endif
        </div>

        <!-- Sản phẩm trong danh mục -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Sản phẩm trong danh mục</h2>
                <a href="{{ route('admin.products.index', ['category_id' => $category->id]) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Xem tất cả
                </a>
            </div>

            @if ($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($products as $product)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden flex flex-col">
                            <div class="h-40 bg-gray-200 dark:bg-gray-600 relative">
                                @if ($product->featured_image)
                                    <img src="{{ Storage::url($product->featured_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-2 right-2">
                                    <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->is_active ? 'Hoạt động' : 'Ẩn' }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-4 flex-1 flex flex-col">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $product->name }}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</p>
                                <div class="mt-2 flex items-center">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                                    @if ($product->compare_price > 0)
                                        <span class="ml-2 text-xs line-through text-gray-500">{{ number_format($product->compare_price, 0, ',', '.') }} đ</span>
                                    @endif
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-900 dark:text-indigo-300 dark:hover:bg-indigo-800">
                                        Sửa
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($productsCount > 6)
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.products.index', ['category_id' => $category->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Xem tất cả {{ $productsCount }} sản phẩm
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-12 bg-gray-50 dark:bg-gray-700 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">Không có sản phẩm</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Chưa có sản phẩm nào trong danh mục này.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.products.create', ['category_id' => $category->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Thêm sản phẩm mới
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDelete() {
            if (confirm('Bạn có chắc chắn muốn xóa danh mục "{{ $category->name }}" không?')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
    @endpush
</x-admin-layout>
