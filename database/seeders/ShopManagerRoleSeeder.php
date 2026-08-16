<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ShopManagerRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Define the target permissions for Shop Manager
        $targetPermissions = [
            // Dashboard Widgets
            'widget_StatsOverview',
            'widget_LatestOrdersTable',
            'widget_LowStockAlert',
            'widget_OutOfStockProducts',
            'widget_ReviewStatsOverview',
            'widget_CouponPerformance',

            // Orders
            'view_any_order',
            'view_order',
            'update_order',
            
            // Invoices / Packing Slips
            'view_any_invoice',
            'view_invoice',
            'create_invoice',
            'update_invoice',

            // Products
            'view_any_product',
            'view_product',
            'update_product',

            // Categories
            'view_any_category',
            'view_category',

            // Brands
            'view_any_brand',
            'view_brand',

            // Inventory / Stock
            // Assuming stock management is handled within product or has its own resource
            // Giving basic product update allows stock updates in Filament unless restricted

            // Coupons
            'view_any_coupon',
            'view_coupon',

            // Customers
            'view_any_customer',
            'view_customer',

            // Reviews
            'view_any_review',
            'view_review',
            'update_review',

            // Shipments / Deliveries (Shipping Methods/Zones)
            'view_any_shipping::method',
            'view_shipping::method',
            'view_any_shipping::zone',
            'view_shipping::zone',
        ];

        // Ensure role exists
        $role = Role::firstOrCreate(['name' => 'Shop Manager', 'guard_name' => 'admin']);

        // Get all existing permissions
        $existingPermissions = Permission::whereIn('name', $targetPermissions)->where('guard_name', 'admin')->pluck('name')->toArray();

        // Sync only valid permissions to avoid exceptions
        $role->syncPermissions($existingPermissions);
    }
}
