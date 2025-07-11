<x-admin-layout>
    <x-slot name="header">Chỉnh sửa danh mục</x-slot>
<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chỉnh sửa danh mục') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 mt-4">
                    <div>
                        <x-input-label for="name" :value="__('Tên danh mục')" />
                        <x-text-input id="name" type="text" name="name" :value="old('name', $category->name)" required autofocus class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="slug" :value="__('Slug')" />
                        <x-text-input id="slug" type="text" name="slug" :value="old('slug', $category->slug)" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                        <p class="text-sm text-gray-500 mt-1">Để trống để tự động tạo từ tên danh mục</p>
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Mô tả')" />
                        <textarea id="description" name="description" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $category->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="parent_id" :value="__('Danh mục cha')" />
                        <select id="parent_id" name="parent_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Không có danh mục cha</option>
                            @foreach($categories as $cat)
                                @if($cat->id != $category->id && !$category->isChildOf($cat))
                                    <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="image" :value="__('Hình ảnh danh mục')" />
                        @if($category->image)
                            <div class="mt-2 mb-4">
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="h-32 w-auto object-cover rounded">
                            </div>
                        @endif
                        <input id="image" type="file" name="image" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <div class="flex items-center">
                        <input id="is_active" type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        <x-input-label for="is_active" :value="__('Hiển thị danh mục')" class="ml-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                            {{ __('Hủy') }}
                        </a>
                        <x-primary-button>
                            {{ __('Cập nhật danh mục') }}
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
    <div class="max-w-3xl mx-auto">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Thông tin cơ bản -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-5">Thông tin cơ bản</h2>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Tên danh mục -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tên danh mục <span class="text-red-600">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-sm">
                                    /danh-muc/
                                </span>
                                <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Để trống để tự động tạo từ tên danh mục</p>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mô tả -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mô tả</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Danh mục cha -->
                        <div>
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Danh mục cha</label>
                            <select name="parent_id" id="parent_id"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Không có</option>
                                @foreach ($parentCategories as $parentCategory)
                                    @if ($parentCategory->id != $category->id)
                                    <option value="{{ $parentCategory->id }}" {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                                        {{ $parentCategory->name }}
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('parent_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Cài đặt SEO và hiển thị -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-5">Cài đặt hiển thị & SEO</h2>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Ảnh đại diện -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ảnh đại diện</label>
                            <div class="mt-1 flex items-center">
                                <div class="w-32 h-32 border-2 border-gray-300 dark:border-gray-700 border-dashed rounded-lg flex items-center justify-center overflow-hidden relative bg-gray-100 dark:bg-gray-900">
                                    @if ($category->image)
                                        <img id="image-preview" src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover">
                                        <svg id="image-placeholder" class="h-12 w-12 text-gray-400 hidden" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    @else
                                        <img id="image-preview" src="" alt="" class="absolute inset-0 w-full h-full object-cover hidden">
                                        <svg id="image-placeholder" class="h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="ml-5 flex-1">
                                    <input type="file" name="image" id="image" accept="image/*" class="sr-only">
                                    <label for="image" class="py-2 px-3 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer inline-block">
                                        Chọn ảnh
                                    </label>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF tối đa 2MB</p>
                                    @if ($category->image)
                                        <div class="mt-2">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="remove_image" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Xóa ảnh hiện tại</span>
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Trạng thái -->
                        <div>
                            <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trạng thái</label>
                            <select name="is_active" id="is_active"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="1" {{ old('is_active', $category->is_active) == 1 ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('is_active', $category->is_active) == 0 ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Thứ tự hiển thị -->
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Thứ tự hiển thị</label>
                            <input type="number" name="order" id="order" value="{{ old('order', $category->order) }}" min="0"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Số thấp hơn sẽ hiển thị trước</p>
                            @error('order')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta title -->
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta title</label>
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $category->meta_title) }}"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Để trống để sử dụng tên danh mục</p>
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta description -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta description</label>
                            <textarea name="meta_description" id="meta_description" rows="2"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md">{{ old('meta_description', $category->meta_description) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Để trống để sử dụng mô tả danh mục</p>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('admin.categories.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-700 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Hủy
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cập nhật
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // Xử lý xem trước ảnh
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const imagePlaceholder = document.getElementById('image-placeholder');

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    imagePlaceholder.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // Tự động tạo slug từ tên danh mục
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const originalSlug = '{{ $category->slug }}';
        const originalName = '{{ $category->name }}';

        nameInput.addEventListener('input', function() {
            // Chỉ tự động cập nhật slug nếu slug ban đầu trùng với slug được tạo từ tên ban đầu
            // hoặc nếu slug đang trống
            if (!slugInput.value || originalSlug === createSlug(originalName)) {
                slugInput.value = createSlug(nameInput.value);
            }
        });

        function createSlug(text) {
            return text.toString().toLowerCase()
                .replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, 'a')
                .replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, 'e')
                .replace(/ì|í|ị|ỉ|ĩ/g, 'i')
                .replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, 'o')
                .replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, 'u')
                .replace(/ỳ|ý|ỵ|ỷ|ỹ/g, 'y')
                .replace(/đ/g, 'd')
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        }
    </script>
    @endpush
</x-admin-layout>
