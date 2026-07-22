import glob

migration_file = glob.glob('/home/sany/Desktop/mmm/e-commerce/database/migrations/*_create_announcements_table.php')[0]
with open(migration_file, 'r') as f:
    content = f.read()

content = content.replace(
    "$table->id();",
    """$table->id();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('type')->default('info'); // info, warning, promo
            $table->datetime('starts_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);"""
)
with open(migration_file, 'w') as f:
    f.write(content)


model_file = "/home/sany/Desktop/mmm/e-commerce/app/Models/Announcement.php"
model_content = """<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'content', 'type', 'starts_at', 'expires_at', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }
}
"""
with open(model_file, 'w') as f:
    f.write(model_content)

print("Announcement Model and Migration setup!")
