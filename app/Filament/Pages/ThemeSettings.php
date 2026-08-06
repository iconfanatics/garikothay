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

class ThemeSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $navigationGroup = 'Theme';
    protected static ?string $navigationLabel = 'Theme 1';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::all()->keyBy('key');

        $trustFeatures = $settings->get('theme1_trust_features')?->getCastedValue();
        if (empty($trustFeatures)) {
            $trustFeatures = [
                ['icon' => '🚚', 'title' => 'Free Delivery', 'subtitle' => 'Orders over ৳5,000'],
                ['icon' => '🛡', 'title' => 'Genuine Products', 'subtitle' => '100% authentic items'],
                ['icon' => '↻', 'title' => 'Easy Returns', 'subtitle' => '7-day return policy'],
                ['icon' => '☎', 'title' => '24/7 Support', 'subtitle' => 'Call us anytime'],
            ];
        }

        $stats = $settings->get('theme1_stats')?->getCastedValue();
        if (empty($stats)) {
            $stats = [
                ['value' => '500+', 'label' => 'Total Products'],
                ['value' => '10+', 'label' => 'Service Types'],
                ['value' => '64', 'label' => 'District Reach'],
                ['value' => '24/7', 'label' => 'Support'],
                ['value' => '10K+', 'label' => 'Happy Customers'],
            ];
        }

        $this->form->fill([
            'theme1_top_ticker_speed' => $settings->get('theme1_top_ticker_speed')?->getCastedValue() ?? 12,
            'theme1_top_ticker_style' => $settings->get('theme1_top_ticker_style')?->getCastedValue() ?? 'slide',
            'theme1_top_ticker' => $settings->get('theme1_top_ticker')?->getCastedValue() ?? [],
            'theme1_hero_slides' => $settings->get('theme1_hero_slides')?->getCastedValue() ?? [],
            'theme1_promo_banners' => $settings->get('theme1_promo_banners')?->getCastedValue() ?? [],
            'theme1_trust_features' => $trustFeatures,
            'theme1_stats' => $stats,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Top Ticker (Marquee)')
                    ->description('Customize the scrolling text at the very top of the page.')
                    ->schema([
                        Forms\Components\Radio::make('theme1_top_ticker_style')
                            ->label('Ticker Style')
                            ->options([
                                'slide' => 'Slide (Animated Marquee)',
                                'static' => 'Static (Fixed Center)'
                            ])
                            ->default('slide')
                            ->inline(),
                        Forms\Components\TextInput::make('theme1_top_ticker_speed')
                            ->label('Animation Speed (Seconds)')
                            ->numeric()
                            ->default(12)
                            ->minValue(1)
                            ->maxValue(100)
                            ->helperText('Lower number means faster scrolling. Default is 12.')
                            ->visible(fn (\Filament\Forms\Get $get): bool => $get('theme1_top_ticker_style') === 'slide'),
                        Forms\Components\Repeater::make('theme1_top_ticker')
                            ->label('Ticker Items')
                            ->schema([
                                Forms\Components\TextInput::make('text')
                                    ->label('Text')
                                    ->required()
                                    ->placeholder('e.g. Get More Customers, Grow Faster.'),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['text'] ?? null)
                            ->collapsible()
                            ->defaultItems(0)
                    ]),
                
                Forms\Components\Section::make('Hero Slider')
                    ->description('Customize the main image slider on the homepage.')
                    ->schema([
                        Forms\Components\Repeater::make('theme1_hero_slides')
                            ->label('Slides')
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->label('Background Image')
                                    ->image()
                                    ->directory('theme')
                                    ->helperText('Recommended size: 1600x800 pixels. Use dark/contrasting images for better text readability.')
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('eyebrow')
                                    ->label('Tag / Eyebrow Text')
                                    ->placeholder('e.g. Mega Sale'),
                                Forms\Components\TextInput::make('title')
                                    ->label('Main Title')
                                    ->required()
                                    ->placeholder('e.g. Car Parts Mega Sale'),
                                Forms\Components\Textarea::make('copy')
                                    ->label('Subtitle / Description')
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('btn_primary_text')
                                    ->label('Primary Button Text')
                                    ->placeholder('e.g. Shop Now'),
                                Forms\Components\TextInput::make('btn_primary_url')
                                    ->label('Primary Button URL')
                                    ->placeholder('e.g. /shop'),
                                Forms\Components\TextInput::make('btn_secondary_text')
                                    ->label('Secondary Button Text')
                                    ->placeholder('e.g. View Lookbook'),
                                Forms\Components\TextInput::make('btn_secondary_url')
                                    ->label('Secondary Button URL'),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->collapsible()
                            ->defaultItems(0)
                    ]),
                
                Forms\Components\Section::make('Promo Banners')
                    ->description('Customize the 2 mini promo banners next to the slider.')
                    ->schema([
                        Forms\Components\Repeater::make('theme1_promo_banners')
                            ->label('Banners')
                            ->schema([
                                Forms\Components\TextInput::make('kicker')
                                    ->label('Small Top Text')
                                    ->placeholder('e.g. UP TO 50% OFF'),
                                Forms\Components\TextInput::make('title')
                                    ->label('Main Text')
                                    ->required()
                                    ->placeholder('e.g. Brake Pads'),
                                Forms\Components\TextInput::make('link')
                                    ->label('URL')
                                    ->required()
                                    ->placeholder('e.g. /shop'),
                                Forms\Components\ColorPicker::make('bg_start')
                                    ->label('Background Gradient Start Color')
                                    ->default('#111827'),
                                Forms\Components\ColorPicker::make('bg_end')
                                    ->label('Background Gradient End Color')
                                    ->default('#be123c'),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->collapsible()
                            ->maxItems(2)
                            ->defaultItems(0)
                    ]),
                    
                Forms\Components\Section::make('Trust Features')
                    ->description('Customize the trust features (e.g. Free Delivery, Genuine Products) shown below the hero section.')
                    ->schema([
                        Forms\Components\Repeater::make('theme1_trust_features')
                            ->label('Features')
                            ->schema([
                                Forms\Components\TextInput::make('icon')
                                    ->label('Icon (Emoji or SVG)')
                                    ->required()
                                    ->placeholder('e.g. 🚚 or SVG code'),
                                Forms\Components\TextInput::make('title')
                                    ->label('Title')
                                    ->required()
                                    ->placeholder('e.g. Free Delivery'),
                                Forms\Components\TextInput::make('subtitle')
                                    ->label('Subtitle')
                                    ->placeholder('e.g. Orders over ৳5,000'),
                            ])
                            ->columns(3)
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->collapsible()
                            ->defaultItems(0)
                            ->maxItems(4)
                    ]),
                Forms\Components\Section::make('Stats Counter')
                    ->description('Customize the statistics numbers shown on the homepage.')
                    ->schema([
                        Forms\Components\Repeater::make('theme1_stats')
                            ->label('Stats')
                            ->schema([
                                Forms\Components\TextInput::make('value')
                                    ->label('Value (e.g. 500+)')
                                    ->required(),
                                Forms\Components\TextInput::make('label')
                                    ->label('Label')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->collapsible()
                            ->defaultItems(0)
                            ->maxItems(5)
                    ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $settingsMeta = [
            'theme1_top_ticker_speed' => ['group' => 'theme', 'type' => SettingType::Number],
            'theme1_top_ticker_style' => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_top_ticker' => ['group' => 'theme', 'type' => SettingType::Json],
            'theme1_hero_slides' => ['group' => 'theme', 'type' => SettingType::Json],
            'theme1_promo_banners' => ['group' => 'theme', 'type' => SettingType::Json],
            'theme1_trust_features' => ['group' => 'theme', 'type' => SettingType::Json],
            'theme1_stats' => ['group' => 'theme', 'type' => SettingType::Json],
        ];

        foreach ($data as $key => $value) {
            $meta = $settingsMeta[$key] ?? ['group' => 'theme', 'type' => SettingType::Json];

            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'group' => $meta['group'],
                    'value' => is_array($value) ? json_encode($value) : $value,
                    'type' => $meta['type'],
                ]
            );
            Cache::forget("setting:{$key}");
        }

        Notification::make()
            ->title('Theme settings saved successfully')
            ->success()
            ->send();
    }
}
