import glob
import os
import re

# 1. Update ShippingZone migration
zone_migration = glob.glob('/home/sany/Desktop/mmm/e-commerce/database/migrations/*_create_shipping_zones_table.php')[0]
with open(zone_migration, 'r') as f:
    content = f.read()

content = content.replace(
    "$table->id();",
    "$table->id();\n            $table->string('name');\n            $table->boolean('is_active')->default(true);"
)
with open(zone_migration, 'w') as f:
    f.write(content)


# 2. Update ShippingMethod migration
method_migration = glob.glob('/home/sany/Desktop/mmm/e-commerce/database/migrations/*_create_shipping_methods_table.php')[0]
with open(method_migration, 'r') as f:
    content = f.read()

content = content.replace(
    "$table->id();",
    "$table->id();\n            $table->foreignId('shipping_zone_id')->constrained()->cascadeOnDelete();\n            $table->string('name');\n            $table->decimal('base_charge', 8, 2)->default(0);\n            $table->decimal('free_shipping_threshold', 10, 2)->nullable();\n            $table->boolean('is_active')->default(true);"
)
with open(method_migration, 'w') as f:
    f.write(content)


# 3. Update Order migration
order_migration = glob.glob('/home/sany/Desktop/mmm/e-commerce/database/migrations/*_add_shipping_method_id_to_orders_table.php')[0]
with open(order_migration, 'r') as f:
    content = f.read()

content = content.replace(
    "Schema::table('orders', function (Blueprint $table) {\n            //\n        });",
    "Schema::table('orders', function (Blueprint $table) {\n            $table->foreignId('shipping_method_id')->nullable()->after('customer_id')->constrained()->nullOnDelete();\n        });"
)
content = content.replace(
    "Schema::table('orders', function (Blueprint $table) {\n            //\n        });",
    "Schema::table('orders', function (Blueprint $table) {\n            $table->dropForeign(['shipping_method_id']);\n            $table->dropColumn('shipping_method_id');\n        });"
)
with open(order_migration, 'w') as f:
    f.write(content)


# 4. Update ShippingZone model
zone_model = "/home/sany/Desktop/mmm/e-commerce/app/Models/ShippingZone.php"
with open(zone_model, 'w') as f:
    f.write("""<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function shippingMethods(): HasMany
    {
        return $this->hasMany(ShippingMethod::class);
    }
}
""")

# 5. Update ShippingMethod model
method_model = "/home/sany/Desktop/mmm/e-commerce/app/Models/ShippingMethod.php"
with open(method_model, 'w') as f:
    f.write("""<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_zone_id', 'name', 'base_charge', 'free_shipping_threshold', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'base_charge' => 'decimal:2',
            'free_shipping_threshold' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function shippingZone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class);
    }
}
""")

print("DB files setup complete!")
