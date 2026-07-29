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
            'site_logo' => $settings->get('site_logo')?->value ?? '',
            'site_favicon' => $settings->get('site_favicon')?->value ?? '',
            'free_shipping_threshold' => $settings->get('free_shipping_threshold')?->value ?? '',
            'dhaka_city_shipping_charge' => $settings->get('dhaka_city_shipping_charge')?->value ?? '80',
            'outside_dhaka_shipping_charge' => $settings->get('outside_dhaka_shipping_charge')?->value ?? $legacyShippingCharge,
            'delivery_time' => $settings->get('delivery_time')?->value ?? '2-5 business days',
            'delivery_partner' => $settings->get('delivery_partner')?->value ?? 'Steadfast',
            'phone' => $settings->get('phone')?->value ?? '',
            'whatsapp' => $settings->get('whatsapp')?->value ?? '',
            'email' => $settings->get('email')?->value ?? '',
            'home_meta_title' => $settings->get('home_meta_title')?->value ?? '',
            'home_meta_description' => $settings->get('home_meta_description')?->value ?? '',
            'google_analytics_code' => $settings->get('google_analytics_code')?->value ?? '',
            'guest_checkout_enabled' => (bool) ($settings->get('guest_checkout_enabled')?->getCastedValue() ?? true),
            'cod_enabled' => (bool) ($settings->get('cod_enabled')?->getCastedValue() ?? true),
            'order_notes_enabled' => (bool) ($settings->get('order_notes_enabled')?->getCastedValue() ?? true),
            'steadfast_api_key' => $settings->get('steadfast_api_key')?->value ?? '',
            'steadfast_secret_key' => $settings->get('steadfast_secret_key')?->value ?? '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General')->schema([
                    Forms\Components\TextInput::make('site_name')->label('Site Name'),
                    Forms\Components\TextInput::make('site_tagline')->label('Site Tagline'),
                    Forms\Components\FileUpload::make('site_logo')->label('Site Logo')->image()->directory('settings'),
                    Forms\Components\FileUpload::make('site_favicon')->label('Favicon')->image()->directory('settings'),
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
                    Forms\Components\TextInput::make('phone')->label('Phone')->rule(new \App\Rules\BdPhone()),
                    Forms\Components\TextInput::make('whatsapp')->label('WhatsApp Number')->rule(new \App\Rules\BdPhone()),
                    Forms\Components\TextInput::make('email')->label('Email')->email(),
                    Forms\Components\Textarea::make('address')->label('Address')->columnSpanFull(),
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
            'site_logo' => ['group' => 'general', 'type' => SettingType::Image],
            'site_favicon' => ['group' => 'general', 'type' => SettingType::Image],
            'home_meta_title' => ['group' => 'seo', 'type' => SettingType::Text],
            'home_meta_description' => ['group' => 'seo', 'type' => SettingType::Textarea],
            'google_analytics_code' => ['group' => 'seo', 'type' => SettingType::Textarea],
            'free_shipping_threshold' => ['group' => 'logistics', 'type' => SettingType::Number],
            'dhaka_city_shipping_charge' => ['group' => 'logistics', 'type' => SettingType::Number],
            'outside_dhaka_shipping_charge' => ['group' => 'logistics', 'type' => SettingType::Number],
            'delivery_time' => ['group' => 'logistics', 'type' => SettingType::Text],
            'delivery_partner' => ['group' => 'logistics', 'type' => SettingType::Text],
            'phone' => ['group' => 'contact', 'type' => SettingType::Text],
            'whatsapp' => ['group' => 'contact', 'type' => SettingType::Text],
            'email' => ['group' => 'contact', 'type' => SettingType::Text],
            'address' => ['group' => 'contact', 'type' => SettingType::Textarea],
            'guest_checkout_enabled' => ['group' => 'checkout', 'type' => SettingType::Boolean],
            'cod_enabled' => ['group' => 'checkout', 'type' => SettingType::Boolean],
            'order_notes_enabled' => ['group' => 'checkout', 'type' => SettingType::Boolean],
            'steadfast_api_key' => ['group' => 'logistics', 'type' => SettingType::Text],
            'steadfast_secret_key' => ['group' => 'logistics', 'type' => SettingType::Text],
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
