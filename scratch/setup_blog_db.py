import glob

# 1. Update add_seo_fields_to_blogs_table
seo_migration_file = glob.glob('/home/sany/Desktop/mmm/e-commerce/database/migrations/*_add_seo_fields_to_blogs_table.php')[0]
with open(seo_migration_file, 'r') as f:
    content = f.read()

content = content.replace(
    "public function up(): void\n    {\n        Schema::table('blogs', function (Blueprint $table) {\n            //\n        });\n    }",
    """public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('seo_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('tags')->nullable();
        });
    }"""
)
content = content.replace(
    "public function down(): void\n    {\n        Schema::table('blogs', function (Blueprint $table) {\n            //\n        });\n    }",
    """public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'meta_description', 'tags']);
        });
    }"""
)
with open(seo_migration_file, 'w') as f:
    f.write(content)

# 2. Update create_blog_comments_table
comments_migration_file = glob.glob('/home/sany/Desktop/mmm/e-commerce/database/migrations/*_create_blog_comments_table.php')[0]
with open(comments_migration_file, 'r') as f:
    content = f.read()

content = content.replace(
    "$table->id();",
    """$table->id();
            $table->foreignId('blog_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->text('comment');
            $table->boolean('is_approved')->default(false);"""
)
with open(comments_migration_file, 'w') as f:
    f.write(content)

# 3. Update BlogComment Model
model_file = "/home/sany/Desktop/mmm/e-commerce/app/Models/BlogComment.php"
model_content = """<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id', 'name', 'email', 'comment', 'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
        ];
    }

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }
}
"""
with open(model_file, 'w') as f:
    f.write(model_content)

# 4. Update Blog Model to add tags cast and comments relation
blog_model_file = "/home/sany/Desktop/mmm/e-commerce/app/Models/Blog.php"
with open(blog_model_file, 'r') as f:
    blog_content = f.read()

blog_content = blog_content.replace("'is_published' => 'boolean',\n            'published_at' => 'datetime',", "'is_published' => 'boolean',\n            'published_at' => 'datetime',\n            'tags' => 'array',")
if "'tags'" not in blog_content:
    blog_content = blog_content.replace("'blog_category_id', 'slug', 'featured_image', 'author_id', 'is_published\n', 'published_at',", "'blog_category_id', 'slug', 'featured_image', 'author_id', 'is_published', 'published_at', 'seo_title', 'meta_description', 'tags',")
    blog_content = blog_content.replace("'blog_category_id', 'slug', 'featured_image', 'author_id', 'is_published', 'published_at',", "'blog_category_id', 'slug', 'featured_image', 'author_id', 'is_published', 'published_at', 'seo_title', 'meta_description', 'tags',")

if "comments()" not in blog_content:
    blog_content = blog_content.replace("public function category(): BelongsTo", "public function comments(): HasMany\n    {\n        return $this->hasMany(BlogComment::class);\n    }\n\n    public function category(): BelongsTo")

with open(blog_model_file, 'w') as f:
    f.write(blog_content)

print("DB setup script created!")
