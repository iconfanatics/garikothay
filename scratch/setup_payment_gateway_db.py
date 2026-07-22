import glob

migration_file = glob.glob('/home/sany/Desktop/mmm/e-commerce/database/migrations/*_create_payment_gateways_table.php')[0]
with open(migration_file, 'r') as f:
    content = f.read()

content = content.replace(
    "$table->id();",
    "$table->id();\n            $table->string('name');\n            $table->string('slug')->unique();\n            $table->json('credentials')->nullable();\n            $table->boolean('is_active')->default(true);\n            $table->enum('mode', ['sandbox', 'live'])->default('sandbox');"
)
with open(migration_file, 'w') as f:
    f.write(content)

model_file = "/home/sany/Desktop/mmm/e-commerce/app/Models/PaymentGateway.php"
with open(model_file, 'w') as f:
    f.write("""<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'credentials', 'is_active', 'mode'
    ];

    protected function casts(): array
    {
        return [
            'credentials' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
""")

print("PaymentGateway DB setup complete!")
