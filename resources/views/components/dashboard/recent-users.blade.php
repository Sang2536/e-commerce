@props(['users'])

<ul role="list" class="divide-y divide-gray-200">
    @forelse($users as $user)
        <li class="px-6 py-4 flex items-center">
            <div class="min-w-0 flex-1 flex items-center">
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-500">
                        <span class="text-sm font-medium leading-none text-white">{{ substr($user->name, 0, 1) }}</span>
                    </span>
                </div>
                <div class="min-w-0 flex-1 px-4">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                </div>
            </div>
            <div class="ml-4 flex-shrink-0">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    {{ $user->created_at->diffForHumans() }}
                </span>
            </div>
        </li>
    @empty
        <li class="px-6 py-4 text-center text-gray-500">Không có người dùng mới</li>
    @endforelse
</ul>
