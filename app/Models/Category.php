<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính có thể gán hàng loạt.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'image',
        'is_active',
    ];

    /**
     * Các thuộc tính nên được ép kiểu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Lấy danh mục cha của danh mục này.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Lấy tất cả danh mục con của danh mục này.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Lấy tất cả sản phẩm thuộc danh mục này.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Kiểm tra xem một danh mục có phải là con của danh mục hiện tại không.
     *
     * @param Category $category
     * @return bool
     */
    public function isChildOf(Category $category): bool
    {
        // Lấy tất cả ID của danh mục con
        $descendantIds = $this->getAllChildrenIds();

        return in_array($category->id, $descendantIds);
    }

    /**
     * Lấy tất cả ID của danh mục con (bao gồm cả cháu, chắt,...)
     *
     * @return array
     */
    protected function getAllChildrenIds(): array
    {
        $ids = [];
        $this->addChildrenIds($this, $ids);
        return $ids;
    }

    /**
     * Thêm đệ quy tất cả ID con vào mảng.
     *
     * @param Category $category
     * @param array $ids
     * @return void
     */
    protected function addChildrenIds(Category $category, array &$ids): void
    {
        foreach ($category->children as $child) {
            $ids[] = $child->id;
            $this->addChildrenIds($child, $ids);
        }
    }
}
