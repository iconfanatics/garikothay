<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\SettingType;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Site Settings';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::all()->keyBy('key');
        $legacyShippingCharge = $settings->get('shipping_charge')?->value ?? '150';

        $this->form->fill([
            'site_name' => $settings->get('site_name')?->value ?? '',
            'site_tagline' => $settings->get('site_tagline')?->value ?? '',
            'trade_license_number' => $settings->get('trade_license_number')?->value ?? '',
            'free_shipping_threshold' => $settings->get('free_shipping_threshold')?->value ?? '',
            'dhaka_city_shipping_charge' => $settings->get('dhaka_city_shipping_charge')?->value ?? '80',
            'outside_dhaka_shipping_charge' => $settings->get('outside_dhaka_shipping_charge')?->value ?? $legacyShippingCharge,
            'delivery_time' => $settings->get('delivery_time')?->value ?? '2-5 business days',
            'delivery_partner' => $settings->get('delivery_partner')?->value ?? 'Steadfast',
            'phone' => $settings->get('phone')?->value ?? '',
            'phone_2' => $settings->get('phone_2')?->value ?? '',
            'phone_3' => $settings->get('phone_3')?->value ?? '',
            'whatsapp' => $settings->get('whatsapp')?->value ?? '',
            'whatsapp_message' => $settings->get('whatsapp_message')?->value ?? '',
            'email' => $settings->get('email')?->value ?? '',
            'email_2' => $settings->get('email_2')?->value ?? '',
            'email_3' => $settings->get('email_3')?->value ?? '',
            'support_hours' => $settings->get('support_hours')?->value ?? '',
            'address' => $settings->get('address')?->value ?? '',
            'address_2' => $settings->get('address_2')?->value ?? '',
            'home_meta_title' => $settings->get('home_meta_title')?->value ?? '',
            'home_meta_description' => $settings->get('home_meta_description')?->value ?? '',
            'google_analytics_code' => $settings->get('google_analytics_code')?->value ?? '',
            'guest_checkout_enabled' => (bool) ($settings->get('guest_checkout_enabled')?->getCastedValue() ?? true),
            'cod_enabled' => (bool) ($settings->get('cod_enabled')?->getCastedValue() ?? true),
            'order_notes_enabled' => (bool) ($settings->get('order_notes_enabled')?->getCastedValue() ?? true),
            'order_success_title' => $settings->get('order_success_title')?->value ?? '',
            'order_success_message' => $settings->get('order_success_message')?->value ?? '',
            'steadfast_api_key' => $settings->get('steadfast_api_key')?->value ?? '',
            'steadfast_secret_key' => $settings->get('steadfast_secret_key')?->value ?? '',
            'enable_email_notifications' => (bool) ($settings->get('enable_email_notifications')?->getCastedValue() ?? true),
            'enable_sms_notifications' => (bool) ($settings->get('enable_sms_notifications')?->getCastedValue() ?? false),
            'enable_whatsapp_notifications' => (bool) ($settings->get('enable_whatsapp_notifications')?->getCastedValue() ?? false),
            'notify_admin_on_new_order' => (bool) ($settings->get('notify_admin_on_new_order')?->getCastedValue() ?? true),
            'notify_customer_on_order_status_change' => (bool) ($settings->get('notify_customer_on_order_status_change')?->getCastedValue() ?? true),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General')->schema([
                    Forms\Components\TextInput::make('site_name')->label('Site Name'),
                    Forms\Components\TextInput::make('site_tagline')->label('Site Tagline'),
                    Forms\Components\TextInput::make('trade_license_number')
                        ->label('Trade License Number')
                        ->placeholder('e.g. TRAD/DNCC/12345/2026'),
                ])->columns(2),
                Forms\Components\Section::make('SEO & Analytics')->schema([
                    Forms\Components\TextInput::make('home_meta_title')
                        ->label('Home Page Meta Title')
                        ->helperText('Title for the home page (SEO)'),
                    Forms\Components\Textarea::make('home_meta_description')
                        ->label('Home Page Meta Description')
                        ->helperText('Description for the home page (SEO)'),
                    Forms\Components\Textarea::make('google_analytics_code')
                        ->label('Global Header Scripts (Google Analytics, Meta Pixel, etc.)')
                        ->helperText('This code will be injected into the <head> of every page.')
                        ->columnSpanFull(),
                ])->columns(2),
                Forms\Components\Section::make('Logistics')->schema([
                    Forms\Components\TextInput::make('dhaka_city_shipping_charge')
                        ->label('Inside Dhaka City Charge (৳)')
                        ->helperText('Applied when both division and city are Dhaka.')
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                    Forms\Components\TextInput::make('outside_dhaka_shipping_charge')
                        ->label('Outside Dhaka Charge (৳)')
                        ->helperText('Applied to every delivery outside Dhaka city.')
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                    Forms\Components\TextInput::make('free_shipping_threshold')
                        ->label('Free Shipping Threshold (৳)')
                        ->helperText('Orders at or above this amount get free shipping. Set 0 to disable.')
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                    Forms\Components\TextInput::make('delivery_time')
                        ->label('Delivery Time')
                        ->placeholder('2-5 business days')
                        ->maxLength(100)
                        ->required(),
                    Forms\Components\Select::make('delivery_partner')
                        ->label('Preferred Delivery Partner')
                        ->options([
                            'Steadfast' => 'Steadfast',
                            'Pathao Courier' => 'Pathao Courier',
                            'RedX' => 'RedX',
                            'eCourier' => 'eCourier',
                            'Paperfly' => 'Paperfly',
                            'Sundarban Courier' => 'Sundarban Courier',
                            'SA Paribahan' => 'SA Paribahan',
                            'Janani Express' => 'Janani Express',
                            'Own Delivery' => 'Own Delivery',
                        ])
                        ->searchable()
                        ->required(),
                ])->columns(2),
                Forms\Components\Section::make('Steadfast Courier API')->schema([
                    Forms\Components\TextInput::make('steadfast_api_key')
                        ->label('API Key')
                        ->helperText('Get this from Steadfast Merchant Portal'),
                    Forms\Components\TextInput::make('steadfast_secret_key')
                        ->label('Secret Key')
                        ->helperText('Get this from Steadfast Merchant Portal'),
                ])->columns(2),
                Forms\Components\Section::make('Contact')->schema([
                    Forms\Components\TextInput::make('phone')->label('Sales Phone')->rule(new \App\Rules\BdPhone()),
                    Forms\Components\TextInput::make('phone_2')->label('Support Phone (Optional)')->rule(new \App\Rules\BdPhone()),
                    Forms\Components\TextInput::make('phone_3')->label('Corporate Phone (Optional)')->rule(new \App\Rules\BdPhone()),
                    Forms\Components\TextInput::make('whatsapp')->label('WhatsApp Number')->rule(new \App\Rules\BdPhone()),
                    Forms\Components\TextInput::make('whatsapp_message')->label('WhatsApp Default Message')->placeholder('e.g. Hi, I need help from Garikothay'),
                    Forms\Components\TextInput::make('email')->label('Sales Email')->email(),
                    Forms\Components\TextInput::make('email_2')->label('Support Email (Optional)')->email(),
                    Forms\Components\TextInput::make('email_3')->label('Corporate Email (Optional)')->email(),
                    Forms\Components\TextInput::make('support_hours')->label('Support Hours')->placeholder('e.g. 10:00 AM - 6:00 PM, Saturday to Thursday'),
                    Forms\Components\Textarea::make('address')->label('Address 1')->columnSpanFull(),
                    Forms\Components\Textarea::make('address_2')->label('Address 2 (Optional)')->columnSpanFull(),
                ])->columns(2),
                Forms\Components\Section::make('Checkout')->schema([
                    Forms\Components\Toggle::make('guest_checkout_enabled')
                        ->label('Guest checkout')
                        ->helperText('Allow customers to place orders without logging in.')
                        ->default(true),
                    Forms\Components\Toggle::make('cod_enabled')
                        ->label('Cash on Delivery')
                        ->helperText('Enable COD payment method.')
                        ->default(true),
                    Forms\Components\Toggle::make('order_notes_enabled')
                        ->label('Order Notes')
                        ->helperText('Allow customers to leave notes during checkout.')
                        ->default(true),
                    Forms\Components\TextInput::make('order_success_title')
                        ->label('Order Success Title')
                        ->placeholder('Order Placed Successfully!'),
                    Forms\Components\Textarea::make('order_success_message')
                        ->label('Order Success Message')
                        ->placeholder('Thank you for your order. We\'ll start preparing it right away!'),
                ])->columns(3),
                Forms\Components\Section::make('Notifications')->schema([
                    Forms\Components\Toggle::make('enable_email_notifications')
                        ->label('Email Notifications')
                        ->helperText('Send emails to customers and admins.')
                        ->default(true),
                    Forms\Components\Toggle::make('enable_sms_notifications')
                        ->label('SMS Notifications')
                        ->helperText('Send SMS to customers (requires SMS gateway setup).'),
                    Forms\Components\Toggle::make('enable_whatsapp_notifications')
                        ->label('WhatsApp Notifications')
                        ->helperText('Send WhatsApp alerts (requires API setup).'),
                    Forms\Components\Toggle::make('notify_admin_on_new_order')
                        ->label('Admin Alert: New Order')
                        ->helperText('Send an alert to admins when a new order is placed.')
                        ->default(true),
                    Forms\Components\Toggle::make('notify_customer_on_order_status_change')
                        ->label('Customer Alert: Order Status')
                        ->helperText('Notify customers when their order status is updated (e.g., Shipped).')
                        ->default(true),
                ])->columns(3),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $settingsMeta = [
            'site_name' => ['group' => 'general', 'type' => SettingType::Text],
            'site_tagline' => ['group' => 'general', 'type' => SettingType::Text],
            'trade_license_number' => ['group' => 'general', 'type' => SettingType::Text],
            'home_meta_title' => ['group' => 'seo', 'type' => SettingType::Text],
            'home_meta_description' => ['group' => 'seo', 'type' => SettingType::Textarea],
            'google_analytics_code' => ['group' => 'seo', 'type' => SettingType::Textarea],
            'free_shipping_threshold' => ['group' => 'logistics', 'type' => SettingType::Number],
            'dhaka_city_shipping_charge' => ['group' => 'logistics', 'type' => SettingType::Number],
            'outside_dhaka_shipping_charge' => ['group' => 'logistics', 'type' => SettingType::Number],
            'delivery_time' => ['group' => 'logistics', 'type' => SettingType::Text],
            'delivery_partner' => ['group' => 'logistics', 'type' => SettingType::Text],
            'phone' => ['group' => 'contact', 'type' => SettingType::Text],
            'phone_2' => ['group' => 'contact', 'type' => SettingType::Text],
            'phone_3' => ['group' => 'contact', 'type' => SettingType::Text],
            'whatsapp' => ['group' => 'contact', 'type' => SettingType::Text],
            'whatsapp_message' => ['group' => 'contact', 'type' => SettingType::Text],
            'email' => ['group' => 'contact', 'type' => SettingType::Text],
            'email_2' => ['group' => 'contact', 'type' => SettingType::Text],
            'email_3' => ['group' => 'contact', 'type' => SettingType::Text],
            'support_hours' => ['group' => 'contact', 'type' => SettingType::Text],
            'address' => ['group' => 'contact', 'type' => SettingType::Textarea],
            'address_2' => ['group' => 'contact', 'type' => SettingType::Textarea],
            'guest_checkout_enabled' => ['group' => 'checkout', 'type' => SettingType::Boolean],
            'cod_enabled' => ['group' => 'checkout', 'type' => SettingType::Boolean],
            'order_notes_enabled' => ['group' => 'checkout', 'type' => SettingType::Boolean],
            'order_success_title' => ['group' => 'checkout', 'type' => SettingType::Text],
            'order_success_message' => ['group' => 'checkout', 'type' => SettingType::Textarea],
            'steadfast_api_key' => ['group' => 'logistics', 'type' => SettingType::Text],
            'steadfast_secret_key' => ['group' => 'logistics', 'type' => SettingType::Text],
            'enable_email_notifications' => ['group' => 'notifications', 'type' => SettingType::Boolean],
            'enable_sms_notifications' => ['group' => 'notifications', 'type' => SettingType::Boolean],
            'enable_whatsapp_notifications' => ['group' => 'notifications', 'type' => SettingType::Boolean],
            'notify_admin_on_new_order' => ['group' => 'notifications', 'type' => SettingType::Boolean],
            'notify_customer_on_order_status_change' => ['group' => 'notifications', 'type' => SettingType::Boolean],
        ];

        foreach ($data as $key => $value) {
            $meta = $settingsMeta[$key] ?? ['group' => 'general', 'type' => SettingType::Text];

            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'group' => $meta['group'],
                    'value' => is_bool($value) ? (string) (int) $value : $value,
                    'type' => $meta['type'],
                ]
            );
            Cache::forget("setting:{$key}");
        }

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
