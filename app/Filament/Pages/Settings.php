<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\SettingType;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

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

        $this->form->fill([
            'site_name' => $settings->get('site_name')?->value ?? '',
            'site_logo' => $settings->get('site_logo')?->value ?? '',
            'free_shipping_threshold' => $settings->get('free_shipping_threshold')?->value ?? '',
            'phone' => $settings->get('phone')?->value ?? '',
            'email' => $settings->get('email')?->value ?? '',
            'address' => $settings->get('address')?->value ?? '',
            'guest_checkout_enabled' => (bool) ($settings->get('guest_checkout_enabled')?->getCastedValue() ?? true),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General')->schema([
                    Forms\Components\TextInput::make('site_name')->label('Site Name'),
                    Forms\Components\FileUpload::make('site_logo')->label('Site Logo')->image()->directory('settings'),
                    Forms\Components\TextInput::make('free_shipping_threshold')->label('Free Shipping Threshold (৳)')->numeric(),
                ])->columns(2),
                Forms\Components\Section::make('Contact')->schema([
                    Forms\Components\TextInput::make('phone')->label('Phone')->rule(new \App\Rules\BdPhone()),
                    Forms\Components\TextInput::make('email')->label('Email')->email(),
                    Forms\Components\Textarea::make('address')->label('Address')->columnSpanFull(),
                ])->columns(2),
                Forms\Components\Section::make('Checkout')->schema([
                    Forms\Components\Toggle::make('guest_checkout_enabled')
                        ->label('Guest checkout')
                        ->helperText('Allow customers to place orders without logging in.')
                        ->default(true),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $settingsMeta = [
            'site_name' => ['group' => 'general', 'type' => SettingType::Text],
            'site_logo' => ['group' => 'general', 'type' => SettingType::Image],
            'free_shipping_threshold' => ['group' => 'general', 'type' => SettingType::Number],
            'phone' => ['group' => 'contact', 'type' => SettingType::Text],
            'email' => ['group' => 'contact', 'type' => SettingType::Text],
            'address' => ['group' => 'contact', 'type' => SettingType::Textarea],
            'guest_checkout_enabled' => ['group' => 'checkout', 'type' => SettingType::Boolean],
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
        }

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
