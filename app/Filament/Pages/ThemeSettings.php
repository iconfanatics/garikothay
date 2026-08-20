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
            'theme1_header_logo'              => $settings->get('theme1_header_logo')?->value ?? '',
            'theme1_footer_logo'              => $settings->get('theme1_footer_logo')?->value ?? '',
            'theme1_payment_banner'           => $settings->get('theme1_payment_banner')?->value ?? '',
            'theme1_favicon'                  => $settings->get('theme1_favicon')?->value ?? '',
            'theme1_top_ticker_speed'         => $settings->get('theme1_top_ticker_speed')?->getCastedValue() ?? 12,
            'theme1_top_ticker_style'         => $settings->get('theme1_top_ticker_style')?->getCastedValue() ?? 'slide',
            'theme1_top_ticker'               => $settings->get('theme1_top_ticker')?->getCastedValue() ?? [],
            'theme1_trust_features'           => $trustFeatures,
            'theme1_stats'                    => $stats,
            'theme1_service_cards'            => $serviceCards,
            'theme1_delivery_partners'        => $deliveryPartners,
            'theme1_social_links'             => $settings->get('theme1_social_links')?->getCastedValue() ?? [],
            'theme1_show_top_ticker'          => $settings->get('theme1_show_top_ticker')?->getCastedValue() ?? true,
            'theme1_show_hero'                => $settings->get('theme1_show_hero')?->getCastedValue() ?? true,
            'theme1_show_trust_features'      => $settings->get('theme1_show_trust_features')?->getCastedValue() ?? true,
            'theme1_show_mega_sale'           => $settings->get('theme1_show_mega_sale')?->getCastedValue() ?? true,
            'theme1_show_new_arrivals'        => $settings->get('theme1_show_new_arrivals')?->getCastedValue() ?? true,
            'theme1_show_featured'            => $settings->get('theme1_show_featured')?->getCastedValue() ?? true,
            'theme1_show_best_sellers'        => $settings->get('theme1_show_best_sellers')?->getCastedValue() ?? true,
            'theme1_show_services'            => $settings->get('theme1_show_services')?->getCastedValue() ?? true,
            'theme1_show_reviews'             => $settings->get('theme1_show_reviews')?->getCastedValue() ?? true,
            'theme1_show_stats'               => $settings->get('theme1_show_stats')?->getCastedValue() ?? true,
            'theme1_show_app'                 => $settings->get('theme1_show_app')?->getCastedValue() ?? true,
            'theme1_show_blogs'               => $settings->get('theme1_show_blogs')?->getCastedValue() ?? true,
            'theme1_show_newsletter'          => $settings->get('theme1_show_newsletter')?->getCastedValue() ?? true,
            'theme1_show_partners'            => $settings->get('theme1_show_partners')?->getCastedValue() ?? true,
            // Section Titles — English
            'theme1_mega_sale_title_en'       => $settings->get('theme1_mega_sale_title_en')?->value ?? '',
            'theme1_mega_sale_subtitle_en'    => $settings->get('theme1_mega_sale_subtitle_en')?->value ?? '',
            'theme1_new_arrivals_title_en'    => $settings->get('theme1_new_arrivals_title_en')?->value ?? '',
            'theme1_new_arrivals_subtitle_en' => $settings->get('theme1_new_arrivals_subtitle_en')?->value ?? '',
            'theme1_featured_title_en'        => $settings->get('theme1_featured_title_en')?->value ?? '',
            'theme1_featured_subtitle_en'     => $settings->get('theme1_featured_subtitle_en')?->value ?? '',
            'theme1_best_sellers_title_en'    => $settings->get('theme1_best_sellers_title_en')?->value ?? '',
            'theme1_best_sellers_subtitle_en' => $settings->get('theme1_best_sellers_subtitle_en')?->value ?? '',
            'theme1_services_title_en'        => $settings->get('theme1_services_title_en')?->value ?? '',
            'theme1_services_subtitle_en'     => $settings->get('theme1_services_subtitle_en')?->value ?? '',
            'theme1_reviews_title_en'         => $settings->get('theme1_reviews_title_en')?->value ?? '',
            'theme1_reviews_subtitle_en'      => $settings->get('theme1_reviews_subtitle_en')?->value ?? '',
            'theme1_blog_title_en'            => $settings->get('theme1_blog_title_en')?->value ?? '',
            'theme1_blog_subtitle_en'         => $settings->get('theme1_blog_subtitle_en')?->value ?? '',
            'theme1_partners_title_en'        => $settings->get('theme1_partners_title_en')?->value ?? '',
            // Section Titles — Bangla
            'theme1_mega_sale_title_bn'       => $settings->get('theme1_mega_sale_title_bn')?->value ?? '',
            'theme1_mega_sale_subtitle_bn'    => $settings->get('theme1_mega_sale_subtitle_bn')?->value ?? '',
            'theme1_new_arrivals_title_bn'    => $settings->get('theme1_new_arrivals_title_bn')?->value ?? '',
            'theme1_new_arrivals_subtitle_bn' => $settings->get('theme1_new_arrivals_subtitle_bn')?->value ?? '',
            'theme1_featured_title_bn'        => $settings->get('theme1_featured_title_bn')?->value ?? '',
            'theme1_featured_subtitle_bn'     => $settings->get('theme1_featured_subtitle_bn')?->value ?? '',
            'theme1_best_sellers_title_bn'    => $settings->get('theme1_best_sellers_title_bn')?->value ?? '',
            'theme1_best_sellers_subtitle_bn' => $settings->get('theme1_best_sellers_subtitle_bn')?->value ?? '',
            'theme1_services_title_bn'        => $settings->get('theme1_services_title_bn')?->value ?? '',
            'theme1_services_subtitle_bn'     => $settings->get('theme1_services_subtitle_bn')?->value ?? '',
            'theme1_reviews_title_bn'         => $settings->get('theme1_reviews_title_bn')?->value ?? '',
            'theme1_reviews_subtitle_bn'      => $settings->get('theme1_reviews_subtitle_bn')?->value ?? '',
            'theme1_blog_title_bn'            => $settings->get('theme1_blog_title_bn')?->value ?? '',
            'theme1_blog_subtitle_bn'         => $settings->get('theme1_blog_subtitle_bn')?->value ?? '',
            'theme1_partners_title_bn'        => $settings->get('theme1_partners_title_bn')?->value ?? '',
            // Newsletter & App
            'theme1_newsletter_title'         => $settings->get('theme1_newsletter_title')?->value ?? '',
            'theme1_newsletter_subtitle'      => $settings->get('theme1_newsletter_subtitle')?->value ?? '',
            'theme1_app_kicker'               => $settings->get('theme1_app_kicker')?->value ?? '',
            'theme1_app_title'                => $settings->get('theme1_app_title')?->value ?? '',
            'theme1_app_desc'                 => $settings->get('theme1_app_desc')?->value ?? '',
            'theme1_app_google_play'          => $settings->get('theme1_app_google_play')?->value ?? '#',
            'theme1_app_app_store'            => $settings->get('theme1_app_app_store')?->value ?? '#',
            'theme1_app_qr_image'             => $settings->get('theme1_app_qr_image')?->value ?? '',
            'theme1_app_qr_text'              => $settings->get('theme1_app_qr_text')?->value ?? '',
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
                                    ->maxValue(500)
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
                    ]),

                    Forms\Components\Tabs\Tab::make('Section Titles')
                        ->icon('heroicon-o-language')
                        ->schema([
                            Forms\Components\Section::make('Mega Sale')
                                ->schema([
                                    Forms\Components\Tabs::make('mega_sale_lang')->tabs([
                                        Forms\Components\Tabs\Tab::make('English')->schema([
                                            Forms\Components\TextInput::make('theme1_mega_sale_title_en')->label('Title')->placeholder('Mega Sale'),
                                            Forms\Components\TextInput::make('theme1_mega_sale_subtitle_en')->label('Subtitle')->placeholder('Best prices on selected automotive products.'),
                                        ])->columns(2),
                                        Forms\Components\Tabs\Tab::make('বাংলা')->schema([
                                            Forms\Components\TextInput::make('theme1_mega_sale_title_bn')->label('শিরোনাম')->placeholder('মেগা সেল'),
                                            Forms\Components\TextInput::make('theme1_mega_sale_subtitle_bn')->label('সাবটাইটেল')->placeholder('বিশেষ সেলে সর্বোচ্চ ছাড়ে অটোমোটিভ পণ্য।'),
                                        ])->columns(2),
                                    ]),
                                ])->collapsible(),
                            Forms\Components\Section::make('New Arrivals')
                                ->schema([
                                    Forms\Components\Tabs::make('new_arrivals_lang')->tabs([
                                        Forms\Components\Tabs\Tab::make('English')->schema([
                                            Forms\Components\TextInput::make('theme1_new_arrivals_title_en')->label('Title')->placeholder('New Arrivals'),
                                            Forms\Components\TextInput::make('theme1_new_arrivals_subtitle_en')->label('Subtitle')->placeholder('Freshly added parts and accessories.'),
                                        ])->columns(2),
                                        Forms\Components\Tabs\Tab::make('বাংলা')->schema([
                                            Forms\Components\TextInput::make('theme1_new_arrivals_title_bn')->label('শিরোনাম')->placeholder('নতুন আগমন'),
                                            Forms\Components\TextInput::make('theme1_new_arrivals_subtitle_bn')->label('সাবটাইটেল')->placeholder('সদ্য যোগ হওয়া যন্ত্রাংশ ও অ্যাক্সেসরিজ।'),
                                        ])->columns(2),
                                    ]),
                                ])->collapsible(),
                            Forms\Components\Section::make('Featured Products')
                                ->schema([
                                    Forms\Components\Tabs::make('featured_lang')->tabs([
                                        Forms\Components\Tabs\Tab::make('English')->schema([
                                            Forms\Components\TextInput::make('theme1_featured_title_en')->label('Title')->placeholder('Featured Products'),
                                            Forms\Components\TextInput::make('theme1_featured_subtitle_en')->label('Subtitle')->placeholder('Hand-picked products for your vehicle.'),
                                        ])->columns(2),
                                        Forms\Components\Tabs\Tab::make('বাংলা')->schema([
                                            Forms\Components\TextInput::make('theme1_featured_title_bn')->label('শিরোনাম')->placeholder('ফিচার্ড পণ্য'),
                                            Forms\Components\TextInput::make('theme1_featured_subtitle_bn')->label('সাবটাইটেল')->placeholder('আপনার গাড়ির জন্য বিশেষভাবে বাছাই করা পণ্য।'),
                                        ])->columns(2),
                                    ]),
                                ])->collapsible(),
                            Forms\Components\Section::make('Best Sellers')
                                ->schema([
                                    Forms\Components\Tabs::make('best_sellers_lang')->tabs([
                                        Forms\Components\Tabs\Tab::make('English')->schema([
                                            Forms\Components\TextInput::make('theme1_best_sellers_title_en')->label('Title')->placeholder('Best Sellers'),
                                            Forms\Components\TextInput::make('theme1_best_sellers_subtitle_en')->label('Subtitle')->placeholder('Popular picks from Gari Kothay customers.'),
                                        ])->columns(2),
                                        Forms\Components\Tabs\Tab::make('বাংলা')->schema([
                                            Forms\Components\TextInput::make('theme1_best_sellers_title_bn')->label('শিরোনাম')->placeholder('বেস্ট সেলার'),
                                            Forms\Components\TextInput::make('theme1_best_sellers_subtitle_bn')->label('সাবটাইটেল')->placeholder('গাড়ি কোথায় গ্রাহকদের সর্বাধিক জনপ্রিয় পণ্য।'),
                                        ])->columns(2),
                                    ]),
                                ])->collapsible(),
                            Forms\Components\Section::make('Automotive Services')
                                ->schema([
                                    Forms\Components\Tabs::make('services_lang')->tabs([
                                        Forms\Components\Tabs\Tab::make('English')->schema([
                                            Forms\Components\TextInput::make('theme1_services_title_en')->label('Title')->placeholder('Automotive Services'),
                                            Forms\Components\TextInput::make('theme1_services_subtitle_en')->label('Subtitle')->placeholder('Everything your vehicle needs...'),
                                        ])->columns(2),
                                        Forms\Components\Tabs\Tab::make('বাংলা')->schema([
                                            Forms\Components\TextInput::make('theme1_services_title_bn')->label('শিরোনাম')->placeholder('অটোমোটিভ সার্ভিস'),
                                            Forms\Components\TextInput::make('theme1_services_subtitle_bn')->label('সাবটাইটেল')->placeholder('আপনার গাড়ির যা প্রয়োজন সবি...'),
                                        ])->columns(2),
                                    ]),
                                ])->collapsible(),
                            Forms\Components\Section::make('Customer Reviews')
                                ->schema([
                                    Forms\Components\Tabs::make('reviews_lang')->tabs([
                                        Forms\Components\Tabs\Tab::make('English')->schema([
                                            Forms\Components\TextInput::make('theme1_reviews_title_en')->label('Title')->placeholder('What Customers Say'),
                                            Forms\Components\TextInput::make('theme1_reviews_subtitle_en')->label('Subtitle')->placeholder('Real experiences from Gari Kothay customers.'),
                                        ])->columns(2),
                                        Forms\Components\Tabs\Tab::make('বাংলা')->schema([
                                            Forms\Components\TextInput::make('theme1_reviews_title_bn')->label('শিরোনাম')->placeholder('গ্রাহকরা কী বলেন'),
                                            Forms\Components\TextInput::make('theme1_reviews_subtitle_bn')->label('সাবটাইটেল')->placeholder('গাড়ি কোথায় গ্রাহকদের বাস্তব অভিজ্ঞতা।'),
                                        ])->columns(2),
                                    ]),
                                ])->collapsible(),
                            Forms\Components\Section::make('Automotive Tips (Blog)')
                                ->schema([
                                    Forms\Components\Tabs::make('blog_lang')->tabs([
                                        Forms\Components\Tabs\Tab::make('English')->schema([
                                            Forms\Components\TextInput::make('theme1_blog_title_en')->label('Title')->placeholder('Automotive Tips'),
                                            Forms\Components\TextInput::make('theme1_blog_subtitle_en')->label('Subtitle')->placeholder('Guides, updates and practical advice for vehicle owners.'),
                                        ])->columns(2),
                                        Forms\Components\Tabs\Tab::make('বাংলা')->schema([
                                            Forms\Components\TextInput::make('theme1_blog_title_bn')->label('শিরোনাম')->placeholder('অটোমোটিভ টিপস'),
                                            Forms\Components\TextInput::make('theme1_blog_subtitle_bn')->label('সাবটাইটেল')->placeholder('যানবাহন মালিকদের জন্য গাইড ও পরামর্শ।'),
                                        ])->columns(2),
                                    ]),
                                ])->collapsible(),
                            Forms\Components\Section::make('Delivery Partners Heading')
                                ->schema([
                                    Forms\Components\Tabs::make('partners_lang')->tabs([
                                        Forms\Components\Tabs\Tab::make('English')->schema([
                                            Forms\Components\TextInput::make('theme1_partners_title_en')->label('Title')->placeholder('Our Delivery Partners'),
                                        ]),
                                        Forms\Components\Tabs\Tab::make('বাংলা')->schema([
                                            Forms\Components\TextInput::make('theme1_partners_title_bn')->label('শিরোনাম')->placeholder('আমাদের ডেলিভারি পার্টনার'),
                                        ]),
                                    ]),
                                ])->collapsible(),
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
                        Forms\Components\Section::make('Mobile App Promo Settings')
                            ->description('Customize the content of the mobile app promotional section.')
                            ->schema([
                                Forms\Components\TextInput::make('theme1_app_kicker')->label('Subtitle/Kicker')->default('Mobile App'),
                                Forms\Components\TextInput::make('theme1_app_title')->label('Title')->default('Take Gari Kothay With You'),
                                Forms\Components\Textarea::make('theme1_app_desc')->label('Description')->default('Order parts, book services, track your GPS device and discover trusted vehicle services from your phone.'),
                                Forms\Components\TextInput::make('theme1_app_google_play')->label('Google Play Link')->default('#')->url(),
                                Forms\Components\TextInput::make('theme1_app_app_store')->label('App Store Link')->default('#')->url(),
                                Forms\Components\FileUpload::make('theme1_app_qr_image')->label('QR Code Image')->directory('theme')->image(),
                                Forms\Components\TextInput::make('theme1_app_qr_text')->label('QR Text')->default('Scan to Download'),
                            ])->columns(2),
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
            'theme1_top_ticker' => ['group' => 'theme', 'type' => SettingType::Json],
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
            'theme1_newsletter_title'         => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_newsletter_subtitle'       => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_app_kicker'                => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_app_title'                 => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_app_desc'                  => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_app_google_play'           => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_app_app_store'             => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_app_qr_image'              => ['group' => 'theme', 'type' => SettingType::Image],
            'theme1_app_qr_text'               => ['group' => 'theme', 'type' => SettingType::Text],
            // Section Titles EN
            'theme1_mega_sale_title_en'        => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_mega_sale_subtitle_en'     => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_new_arrivals_title_en'     => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_new_arrivals_subtitle_en'  => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_featured_title_en'         => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_featured_subtitle_en'      => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_best_sellers_title_en'     => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_best_sellers_subtitle_en'  => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_services_title_en'         => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_services_subtitle_en'      => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_reviews_title_en'          => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_reviews_subtitle_en'       => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_blog_title_en'             => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_blog_subtitle_en'          => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_partners_title_en'         => ['group' => 'theme', 'type' => SettingType::Text],
            // Section Titles BN
            'theme1_mega_sale_title_bn'        => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_mega_sale_subtitle_bn'     => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_new_arrivals_title_bn'     => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_new_arrivals_subtitle_bn'  => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_featured_title_bn'         => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_featured_subtitle_bn'      => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_best_sellers_title_bn'     => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_best_sellers_subtitle_bn'  => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_services_title_bn'         => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_services_subtitle_bn'      => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_reviews_title_bn'          => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_reviews_subtitle_bn'       => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_blog_title_bn'             => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_blog_subtitle_bn'          => ['group' => 'theme', 'type' => SettingType::Text],
            'theme1_partners_title_bn'         => ['group' => 'theme', 'type' => SettingType::Text],
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

        Cache::forget('all_settings');
        // also reset static property if possible, though since this is HTTP request it doesn't matter as much, 
        // but better safe:
        if (property_exists(Setting::class, 'loadedSettings')) {
            // we can't reset protected property easily from outside, but Cache::forget is enough for next request.
        }

        Notification::make()
            ->title('Theme settings saved successfully')
            ->success()
            ->send();
    }
}
