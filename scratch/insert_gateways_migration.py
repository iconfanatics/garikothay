import glob

migration_file = glob.glob('/home/sany/Desktop/mmm/e-commerce/database/migrations/*_insert_default_payment_gateways.php')[0]
with open(migration_file, 'r') as f:
    content = f.read()

up_logic = """    public function up(): void
    {
        \\Illuminate\\Support\\Facades\\DB::table('payment_gateways')->insert([
            [
                'name' => 'Cash on Delivery',
                'slug' => 'cod',
                'credentials' => null,
                'is_active' => true,
                'mode' => 'live',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SSLCommerz',
                'slug' => 'sslcommerz',
                'credentials' => json_encode(['store_id' => '', 'store_password' => '']),
                'is_active' => false,
                'mode' => 'sandbox',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'bKash',
                'slug' => 'bkash',
                'credentials' => json_encode(['app_key' => '', 'app_secret' => '', 'username' => '', 'password' => '']),
                'is_active' => false,
                'mode' => 'sandbox',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }"""

down_logic = """    public function down(): void
    {
        \\Illuminate\\Support\\Facades\\DB::table('payment_gateways')->whereIn('slug', ['cod', 'sslcommerz', 'bkash'])->delete();
    }"""

content = content.replace("    public function up(): void\n    {\n        //\n    }", up_logic)
content = content.replace("    public function down(): void\n    {\n        //\n    }", down_logic)

with open(migration_file, 'w') as f:
    f.write(content)
print("Migration updated successfully!")
