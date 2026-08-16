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

        $serviceCards = $settings->get('theme1_service_cards')?->getCastedValue();
        if (empty($serviceCards)) {
            $serviceCards = [
                ['icon' => '🔧', 'name' => 'Garage Kothay', 'desc' => 'Find trusted garages near you with booking support.', 'href' => '#', 'bg' => 'linear-gradient(135deg,#ef4444,#be123c)'],
                ['icon' => '💦', 'name' => 'CarWash Kothay', 'desc' => 'Book car wash and detailing packages online.', 'href' => '#', 'bg' => 'linear-gradient(135deg,#0ea5e9,#2563eb)'],
                ['icon' => '⛽', 'name' => 'Fuel Kothay', 'desc' => 'Discover nearby fuel stations and route support.', 'href' => '#', 'bg' => 'linear-gradient(135deg,#f59e0b,#ea580c)'],
                ['icon' => '👤', 'name' => 'Driver Kothay', 'desc' => 'Hire verified drivers by the hour, day or month.', 'href' => '#', 'bg' => 'linear-gradient(135deg,#10b981,#16a34a)'],
                ['icon' => '📍', 'name' => 'GPS Tracker', 'desc' => 'Devices, installation and live monitoring plans.', 'href' => '#', 'bg' => 'linear-gradient(135deg,#06b6d4,#0f766e)'],
                ['icon' => '🎫', 'name' => 'Ticket Kothay', 'desc' => 'Bus, train and launch ticket support in one place.', 'href' => '#', 'bg' => 'linear-gradient(135deg,#6366f1,#2563eb)'],
                ['icon' => '🏫', 'name' => 'Driving School', 'desc' => 'Compare driving schools, courses and reviews.', 'href' => '#', 'bg' => 'linear-gradient(135deg,#8b5cf6,#7c3aed)'],
                ['icon' => '🧮', 'name' => 'Fare Calculator', 'desc' => 'Estimate distance, fuel cost and fare quickly.', 'href' => '#', 'bg' => 'linear-gradient(135deg,#eab308,#d97706)'],
                ['icon' => '🛒', 'name' => 'Auto Shop', 'desc' => 'Shop parts, accessories, oils, lights and tools.', 'href' => '/shop', 'bg' => 'linear-gradient(135deg,#ec4899,#be123c)'],
            ];
        }

        $deliveryPartners = $settings->get('theme1_delivery_partners')?->getCastedValue();
        if (empty($deliveryPartners)) {
            $deliveryPartners = [
                ['name' => '🚲 Pathao'],
                ['name' => '✈ Paperfly'],
                ['name' => '🚚 RedX'],
                ['name' => '▣ Sundarban'],
                ['name' => '📦 SA Paribahan'],
                ['name' => '🚀 Steadfast'],
                ['name' => '➤ eCourier'],
                ['name' => '⛴ Janani'],
            ];
        }

        $this->form->fill([
            'theme1_header_logo' => $settings->get('theme1_header_logo')?->value ?? '',
            'theme1_footer_logo' => $settings->get('theme1_footer_logo')?->value ?? '',
            'theme1_payment_banner' => $settings->get('theme1_payment_banner')?->value ?? '',
            'theme1_favicon' => $settings->get('theme1_favicon')?->value ?? '',
            'theme1_top_ticker_speed' => $settings->get('theme1_top_ticker_speed')?->getCastedValue() ?? 12,
            'theme1_top_ticker_style' => $settings->get('theme1_top_ticker_style')?->getCastedValue() ?? 'slide',
            'theme1_top_ticker' => $settings->get('theme1_top_ticker')?->getCastedValue() ?? [],
            'theme1_hero_slides' => $settings->get('theme1_hero_slides')?->getCastedValue() ?? [],
            'theme1_promo_banners' => $settings->get('theme1_promo_banners')?->getCastedValue() ?? [],
            'theme1_trust_features' => $trustFeatures,
            'theme1_stats' => $stats,
            'theme1_service_cards' => $serviceCards,
            'theme1_delivery_partners' => $deliveryPartners,
            'theme1_social_links' => $settings->get('theme1_social_links')?->getCastedValue() ?? [],
            'theme1_show_top_ticker' => $settings->get('theme1_show_top_ticker')?->getCastedValue() ?? true,
            'theme1_show_hero' => $settings->get('theme1_show_hero')?->getCastedValue() ?? true,
            'theme1_show_trust_features' => $settings->get('theme1_show_trust_features')?->getCastedValue() ?? true,
            'theme1_show_mega_sale' => $settings->get('theme1_show_mega_sale')?->getCastedValue() ?? true,
            'theme1_show_new_arrivals' => $settings->get('theme1_show_new_arrivals')?->getCastedValue() ?? true,
            'theme1_show_featured' => $settings->get('theme1_show_featured')?->getCastedValue() ?? true,
            'theme1_show_best_sellers' => $settings->get('theme1_show_best_sellers')?->getCastedValue() ?? true,
            'theme1_show_services' => $settings->get('theme1_show_services')?->getCastedValue() ?? true,
            'theme1_show_reviews' => $settings->get('theme1_show_reviews')?->getCastedValue() ?? true,
            'theme1_show_stats' => $settings->get('theme1_show_stats')?->getCastedValue() ?? true,
            'theme1_show_app' => $settings->get('theme1_show_app')?->getCastedValue() ?? true,
            'theme1_show_blogs' => $settings->get('theme1_show_blogs')?->getCastedValue() ?? true,
            'theme1_show_newsletter' => $settings->get('theme1_show_newsletter')?->getCastedValue() ?? true,
            'theme1_show_partners' => $settings->get('theme1_show_partners')?->getCastedValue() ?? true,
            'theme1_newsletter_title' => $settings->get('theme1_newsletter_title')?->value ?? 'GET DEALS IN YOUR INBOX',
            'theme1_newsletter_subtitle' => $settings->get('theme1_newsletter_subtitle')?->value ?? 'Subscribe for exclusive offers, new arrivals and service discounts.',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Theme Layout')->tabs([
                    Forms\Components\Tabs\Tab::make('General & Visibility')->schema([
                        Forms\Components\Section::make('Section Visibility')
                            ->description('Show or hide sections on the homepage.')
                            ->schema([
                                Forms\Components\Toggle::make('theme1_show_top_ticker')->label('Top Ticker')->default(true),
                                Forms\Components\Toggle::make('theme1_show_hero')->label('Hero Slider & Promos')->default(true),
                                Forms\Components\Toggle::make('theme1_show_trust_features')->label('Trust Features')->default(true),
                                Forms\Components\Toggle::make('theme1_show_mega_sale')->label('Mega Sale')->default(true),
                                Forms\Components\Toggle::make('theme1_show_new_arrivals')->label('New Arrivals')->default(true),
                                Forms\Components\Toggle::make('theme1_show_featured')->label('Featured Products')->default(true),
                                Forms\Components\Toggle::make('theme1_show_best_sellers')->label('Best Sellers')->default(true),
                                Forms\Components\Toggle::make('theme1_show_services')->label('Automotive Services')->default(true),
                                Forms\Components\Toggle::make('theme1_show_reviews')->label('Customer Reviews')->default(true),
                                Forms\Components\Toggle::make('theme1_show_stats')->label('Statistics')->default(true),
                                Forms\Components\Toggle::make('theme1_show_app')->label('Mobile App Promo')->default(true),
                                Forms\Components\Toggle::make('theme1_show_blogs')->label('Automotive Tips (Blog)')->default(true),
                                Forms\Components\Toggle::make('theme1_show_newsletter')->label('Newsletter Subscription')->default(true),
                                Forms\Components\Toggle::make('theme1_show_partners')->label('Delivery Partners')->default(true),
                            ])->columns(3)
                            ->collapsible(),
                        Forms\Components\Section::make('Theme Logos')
                            ->description('Upload logos for the header and footer.')
                            ->schema([
                                Forms\Components\FileUpload::make('theme1_header_logo')
                                    ->label('Header Logo')
                                    ->helperText('Recommended size: 250x60 pixels. PNG format with transparent background is best.')
                                    ->image()
                                    ->directory('theme'),
                                Forms\Components\FileUpload::make('theme1_footer_logo')
                                    ->label('Footer Logo')
                                    ->helperText('Recommended size: 250x60 pixels. PNG format with transparent background is best. White/light logo is recommended for dark footers.')
                                    ->image()
                                    ->directory('theme'),
                                Forms\Components\FileUpload::make('theme1_payment_banner')
                                    ->label('Payment Banner (Footer)')
                                    ->helperText('Payment methods accepted. E.g. SSLCommerz banner. Transparent PNG is best.')
                                    ->image()
                                    ->directory('theme'),
                                Forms\Components\FileUpload::make('theme1_favicon')
                                    ->label('Favicon')
                                    ->helperText('Recommended size: 32x32 pixels or 64x64 pixels (PNG/ICO).')
                                    ->image()
                                    ->directory('theme'),
                            ])->columns(3),
                    ]),

                    Forms\Components\Tabs\Tab::make('Top Bar & Hero')->schema([
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
                                    ->maxValue(300)
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
                    ]),

                    Forms\Components\Tabs\Tab::make('Services & Features')->schema([
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
                            ]),
                        Forms\Components\Section::make('Automotive Services')
                            ->description('Customize the service cards shown on the homepage.')
                            ->schema([
                                Forms\Components\Repeater::make('theme1_service_cards')
                                    ->label('Service Cards')
                                    ->schema([
                                        Forms\Components\TextInput::make('icon')
                                            ->label('Icon (Emoji or SVG)')
                                            ->required()
                                            ->placeholder('e.g. 🔧'),
                                        Forms\Components\TextInput::make('bg')
                                            ->label('Icon Background (CSS gradient or color)')
                                            ->required()
                                            ->placeholder('e.g. linear-gradient(135deg,#ef4444,#be123c)'),
                                        Forms\Components\TextInput::make('name')
                                            ->label('Service Name')
                                            ->required()
                                            ->placeholder('e.g. Garage Kothay'),
                                        Forms\Components\Textarea::make('desc')
                                            ->label('Description')
                                            ->required(),
                                        Forms\Components\TextInput::make('href')
                                            ->label('URL')
                                            ->required()
                                            ->placeholder('e.g. /garages'),
                                    ])
                                    ->columns(2)
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                    ->collapsible()
                                    ->defaultItems(0)
                            ]),
                        Forms\Components\Section::make('Delivery Partners')
                            ->description('Customize the scrolling delivery partners list.')
                            ->schema([
                                Forms\Components\Repeater::make('theme1_delivery_partners')
                                    ->label('Partners')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->label('Logo Image')
                                            ->image()
                                            ->directory('theme')
                                            ->helperText('Recommended size: 120x60 pixels. PNG format with transparent background is best.'),
                                        Forms\Components\TextInput::make('name')
                                            ->label('Name (with icon)')
                                            ->required()
                                            ->placeholder('e.g. 🚀 Steadfast'),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                    ->collapsible()
                                    ->defaultItems(0)
                            ]),
                    ]),

                    Forms\Components\Tabs\Tab::make('Footer & Social')->schema([
                        Forms\Components\Section::make('Newsletter Settings')
                            ->description('Customize the newsletter subscription text.')
                            ->schema([
                                Forms\Components\TextInput::make('theme1_newsletter_title')
                                    ->label('Title')
                                    ->default('GET DEALS IN YOUR INBOX')
                                    ->required(),
                                Forms\Components\TextInput::make('theme1_newsletter_subtitle')
                                    ->label('Subtitle')
                                    ->default('Subscribe for exclusive offers, new arrivals and service discounts.')
                                    ->required(),
                            ]),
                        Forms\Components\Section::make('Social Media Links')
                            ->description('Add social media icons and links to the footer.')
                            ->schema([
                                Forms\Components\Repeater::make('theme1_social_links')
                                    ->label('Social Links')
                                    ->schema([
                                        Forms\Components\Select::make('platform')
                                            ->label('Platform')
                                            ->options([
                                                'facebook' => 'Facebook',
                                                'twitter' => 'Twitter/X',
                                                'instagram' => 'Instagram',
                                                'youtube' => 'YouTube',
                                                'linkedin' => 'LinkedIn',
                                                'tiktok' => 'TikTok',
                                                'whatsapp' => 'WhatsApp',
                                            ])
                                            ->required(),
                                        Forms\Components\TextInput::make('url')
                                            ->label('URL')
                                            ->url()
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->itemLabel(fn (array $state): ?string => ucfirst($state['platform'] ?? 'Link'))
                                    ->defaultItems(0)
                            ])
                    ]),
                ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $settingsMeta = [
            'theme1_header_logo' => ['group' => 'theme', 'type' => SettingType::Image],
            'theme1_footer_logo' => ['group' => 'theme', 'type' => SettingType::Image],
            'theme1_payment_banner' => ['group' => 'theme', 'type' => SettingType::Image],
            'theme1_favicon' => ['group' => 'theme', 'type' => SettingType::Image],
            'theme1_top_ticker_speed' => ['group' => 'theme', 'type' => SettingType::Number],
            'theme1_top_ticker_style' => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_top_ticker' => ['group' => 'theme', 'type' => SettingType::Json],
            'theme1_hero_slides' => ['group' => 'theme', 'type' => SettingType::Json],
            'theme1_promo_banners' => ['group' => 'theme', 'type' => SettingType::Json],
            'theme1_trust_features' => ['group' => 'theme', 'type' => SettingType::Json],
            'theme1_stats' => ['group' => 'theme', 'type' => SettingType::Json],
            'theme1_service_cards' => ['group' => 'theme', 'type' => SettingType::Json],
            'theme1_delivery_partners' => ['group' => 'theme', 'type' => SettingType::Json],
            'theme1_social_links' => ['group' => 'theme', 'type' => SettingType::Json],
            'theme1_show_top_ticker' => ['group' => 'theme', 'type' => SettingType::Boolean],
            'theme1_show_hero' => ['group' => 'theme', 'type' => SettingType::Boolean],
            'theme1_show_trust_features' => ['group' => 'theme', 'type' => SettingType::Boolean],
            'theme1_show_mega_sale' => ['group' => 'theme', 'type' => SettingType::Boolean],
            'theme1_show_new_arrivals' => ['group' => 'theme', 'type' => SettingType::Boolean],
            'theme1_show_featured' => ['group' => 'theme', 'type' => SettingType::Boolean],
            'theme1_show_best_sellers' => ['group' => 'theme', 'type' => SettingType::Boolean],
            'theme1_show_services' => ['group' => 'theme', 'type' => SettingType::Boolean],
            'theme1_show_reviews' => ['group' => 'theme', 'type' => SettingType::Boolean],
            'theme1_show_stats' => ['group' => 'theme', 'type' => SettingType::Boolean],
            'theme1_show_app' => ['group' => 'theme', 'type' => SettingType::Boolean],
            'theme1_show_blogs' => ['group' => 'theme', 'type' => SettingType::Boolean],
            'theme1_show_newsletter' => ['group' => 'theme', 'type' => SettingType::Boolean],
            'theme1_show_partners' => ['group' => 'theme', 'type' => SettingType::Boolean],
            'theme1_newsletter_title' => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_newsletter_subtitle' => ['group' => 'theme', 'type' => SettingType::Text],
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
