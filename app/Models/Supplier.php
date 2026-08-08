<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'contact_person', 'contact_number', 'address', 'is_active',
        'alternative_contact_number', 'email', 'company_name', 'website',
        'business_type', 'division', 'district', 'postal_code', 'trade_license_no', 'bin_vat_no', 'visiting_card_image',
        'payment_terms', 'minimum_order_quantity',
        'bank_name', 'account_name', 'account_number', 'mobile_banking_provider', 'mobile_banking_number',
        'preferred_supplier', 'supplier_code', 'notes',
        'whatsapp_number', 'facebook_page',
        'pickup_address', 'delivery_coverage', 'preferred_courier',
        'supports_return', 'warranty_support', 'average_delivery_time_days',
        'account_manager_id', 'last_contact_date', 'last_purchase_date', 'remarks',
        'total_purchase_amount', 'outstanding_due', 'total_purchase_orders'
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'preferred_supplier' => 'boolean',
            'supports_return' => 'boolean',
            'warranty_support' => 'boolean',
            'last_contact_date' => 'date',
            'last_purchase_date' => 'date',
            'total_purchase_amount' => 'decimal:2',
            'outstanding_due' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($supplier) {
            if (empty($supplier->supplier_code)) {
                $lastSupplier = static::orderBy('id', 'desc')->first();
                $lastId = $lastSupplier ? $lastSupplier->id : 0;
                $supplier->supplier_code = 'SUP-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, Product::class);
    }

    public function accountManager()
    {
        return $this->belongsTo(User::class, 'account_manager_id');
    }
}
