<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = "heroicon-o-users";
    protected static ?string $navigationGroup = "Sales";
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = "Customer";
    protected static ?string $pluralModelLabel = "Customers";

        public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make("Customer Information")->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make("name")
                        ->label("Full Name")
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make("email")
                        ->label("Email Address")
                        ->email()
                        ->required()
                        ->maxLength(255),
                ]),
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make("phone")
                        ->label("Phone Number")
                        ->tel()
                        ->rule(new \App\Rules\BdPhone())
                        ->maxLength(20),
                    Forms\Components\Select::make("locale")
                        ->label("Preferred Language")
                        ->options(["en" => "English", "bn" => "বাংলা"])
                        ->default("en"),
                ]),
                Forms\Components\Toggle::make("is_active")
                    ->label("Active Account")
                    ->default(true),
            ]),
            Forms\Components\Section::make("Address & Preferences")->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make("address")->label("Address"),
                    Forms\Components\TextInput::make("division")->label("Division"),
                    Forms\Components\TextInput::make("district")->label("District"),
                    Forms\Components\Select::make("preferred_payment_method")
                        ->label("Preferred Payment Method")
                        ->options(\App\Enums\PaymentMethod::options()),
                ]),
            ]),
            Forms\Components\Section::make("Admin Notes")->schema([
                Forms\Components\TagsInput::make("tags")
                    ->label("Customer Tags")
                    ->placeholder("e.g. VIP, Wholesaler, Problematic"),
                Forms\Components\Textarea::make("notes")
                    ->label("Notes (Admin Only)")
                    ->rows(3),
            ]),
        ]);
    }

        public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make("Customer Details")->schema([
                Infolists\Components\Grid::make(3)->schema([
                    Infolists\Components\TextEntry::make("customer_id")
                        ->label("Customer ID")
                        ->state(fn(User $record): string => '#CUST-' . str_pad((string)$record->id, 4, '0', STR_PAD_LEFT))
                        ->weight('bold'),
                    Infolists\Components\TextEntry::make("name")->label("Full Name"),
                    Infolists\Components\TextEntry::make("customer_type")
                        ->label("Customer Type")
                        ->state(fn(User $record): string => $record->orders()->count() > 1 ? 'Returning' : ($record->orders()->count() == 1 ? 'New' : 'No Orders'))
                        ->badge()
                        ->color(fn(string $state): string => match($state) { 'Returning' => 'success', 'New' => 'info', default => 'gray' }),
                    Infolists\Components\TextEntry::make("email")->label("Email"),
                    Infolists\Components\TextEntry::make("phone")->label("Phone")->default("N/A"),
                    Infolists\Components\TextEntry::make("created_at")
                        ->label("Member Since")
                        ->dateTime("d M Y"),
                ]),
                Infolists\Components\Grid::make(3)->schema([
                    Infolists\Components\TextEntry::make("address")->label("Address")->default("N/A"),
                    Infolists\Components\TextEntry::make("division_district")
                        ->label("Division/District")
                        ->state(fn(User $record): string => trim(($record->division ?? '') . '/' . ($record->district ?? ''), '/'))
                        ->default("N/A"),
                    Infolists\Components\TextEntry::make("preferred_payment_method")
                        ->label("Preferred Payment Method")
                        ->formatStateUsing(fn(?string $state) => $state ? \App\Enums\PaymentMethod::tryFrom($state)?->label() ?? $state : 'N/A'),
                ]),
                Infolists\Components\Grid::make(1)->schema([
                    Infolists\Components\TextEntry::make("tags")
                        ->label("Tags")
                        ->badge()
                        ->default("None"),
                    Infolists\Components\TextEntry::make("notes")->label("Notes (Admin Only)")->default("None"),
                ])
            ]),

            Infolists\Components\Section::make("Order & Activity Metrics")->schema([
                Infolists\Components\Grid::make(4)->schema([
                    Infolists\Components\TextEntry::make("total_orders")
                        ->label("Total Orders")
                        ->state(fn(User $record): int => $record->orders()->count()),
                    Infolists\Components\TextEntry::make("total_products")
                        ->label("Total Products Purchased")
                        ->state(fn(User $record): int => (int) \Illuminate\Support\Facades\DB::table('order_items')
                            ->join('orders', 'orders.id', '=', 'order_items.order_id')
                            ->where('orders.user_id', $record->id)
                            ->whereNotIn('orders.status', ['cancelled', 'refunded'])
                            ->sum('order_items.quantity')),
                    Infolists\Components\TextEntry::make("total_spent")
                        ->label("Total Spent")
                        ->state(fn(User $record): string => "৳" . number_format($record->total_spent, 2)),
                    Infolists\Components\TextEntry::make("coupon_usage")
                        ->label("Coupon Usage Count")
                        ->state(fn(User $record): int => $record->orders()->whereNotNull('coupon_id')->whereNotIn('status', ['cancelled'])->count()),
                    
                    Infolists\Components\TextEntry::make("pending_orders")
                        ->label("Pending Orders")
                        ->state(fn(User $record): int => $record->orders()->where('status', 'pending')->count())
                        ->color('warning'),
                    Infolists\Components\TextEntry::make("completed_orders")
                        ->label("Completed Orders")
                        ->state(fn(User $record): int => $record->orders()->where('status', 'delivered')->count())
                        ->color('success'),
                    Infolists\Components\TextEntry::make("cancelled_orders")
                        ->label("Cancelled Orders")
                        ->state(fn(User $record): int => $record->orders()->where('status', 'cancelled')->count())
                        ->color('danger'),
                    Infolists\Components\TextEntry::make("last_order_date")
                        ->label("Last Order Date")
                        ->state(fn(User $record): ?string => $record->orders()->latest()->first()?->created_at?->format('d M Y, h:i A') ?? 'Never'),
                    Infolists\Components\TextEntry::make("last_login_at")
                        ->label("Last Login Date")
                        ->dateTime("d M Y, h:i A")
                        ->placeholder("Never"),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("customer_id")
                    ->label("Customer ID")
                    ->state(fn(\App\Models\User $record): string => '#CUST-' . str_pad((string)$record->id, 4, '0', STR_PAD_LEFT))
                    ->searchable(['id'])
                    ->sortable(['id']),
                Tables\Columns\TextColumn::make("name")
                    ->label("Name")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("email")
                    ->label("Email")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("phone")
                    ->label("Phone")
                    ->default("—")
                    ->searchable(),
                Tables\Columns\TextColumn::make("orders_count")
                    ->label("Orders")
                    ->counts("orders")
                    ->badge()
                    ->color("primary")
                    ->sortable(),
                Tables\Columns\TextColumn::make("email_verified_at")
                    ->label("Verified")
                    ->dateTime("d M Y")
                    ->placeholder("No")
                    ->sortable(),
                Tables\Columns\TextColumn::make("last_order_date")
                    ->label("Last Order")
                    ->dateTime("d M Y")
                    ->placeholder("Never")
                    ->sortable(),
                Tables\Columns\TextColumn::make("last_login_at")
                    ->label("Last Login")
                    ->dateTime("d M Y")
                    ->placeholder("Never")
                    ->sortable(),
                Tables\Columns\TextColumn::make("is_active")
                    ->label("Status")
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Active' : 'Inactive')
                    ->badge()
                    ->color(fn(bool $state): string => $state ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make("created_at")
                    ->label("Joined")
                    ->dateTime("d M Y")
                    ->sortable(),
            ])
            ->defaultSort("created_at", "desc")
            ->filters([
                Tables\Filters\TernaryFilter::make("is_active")
                    ->label("Account Status")
                    ->trueLabel("Active")
                    ->falseLabel("Inactive"),
                Tables\Filters\TernaryFilter::make("email_verified_at")
                    ->label("Email Verified")
                    ->nullable()
                    ->trueLabel("Verified")
                    ->falseLabel("Unverified"),
                Tables\Filters\SelectFilter::make('customer_segment')
                    ->label('Customer Segment')
                    ->options([
                        'new_this_month' => 'New Customers (This Month)',
                        'active_last_30_days' => 'Active Customers (Last 30 Days)',
                        'returning' => 'Returning Customers (>1 Order)',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['value'] === 'new_this_month',
                                fn (Builder $query): Builder => $query->where('created_at', '>=', \Illuminate\Support\Carbon::now()->startOfMonth()),
                            )
                            ->when(
                                $data['value'] === 'active_last_30_days',
                                fn (Builder $query): Builder => $query->whereHas('orders', fn ($q) => $q->where('created_at', '>=', \Illuminate\Support\Carbon::now()->subDays(30))),
                            )
                            ->when(
                                $data['value'] === 'returning',
                                fn (Builder $query): Builder => $query->whereHas('orders', fn ($q) => $q->select('id'), '>', 1),
                            );
                    }),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Registration From'),
                        Forms\Components\DatePicker::make('created_until')->label('Registration Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('last_login_at')
                    ->form([
                        Forms\Components\DatePicker::make('login_from')->label('Last Login From'),
                        Forms\Components\DatePicker::make('login_until')->label('Last Login Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['login_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('last_login_at', '>=', $date),
                            )
                            ->when(
                                $data['login_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('last_login_at', '<=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('last_order_date')
                    ->form([
                        Forms\Components\DatePicker::make('order_from')->label('Last Order From'),
                        Forms\Components\DatePicker::make('order_until')->label('Last Order Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['order_from'],
                                fn (Builder $query, $date): Builder => $query->whereHas('orders', fn($q) => $q->whereDate('created_at', '>=', $date)),
                            )
                            ->when(
                                $data['order_until'],
                                fn (Builder $query, $date): Builder => $query->whereHas('orders', fn($q) => $q->whereDate('created_at', '<=', $date)),
                            );
                    }),
                Tables\Filters\Filter::make('total_orders')
                    ->form([
                        Forms\Components\TextInput::make('min_orders')->label('Min Orders')->numeric(),
                        Forms\Components\TextInput::make('max_orders')->label('Max Orders')->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_orders'],
                                fn (Builder $query, $value): Builder => $query->has('orders', '>=', $value),
                            )
                            ->when(
                                $data['max_orders'],
                                fn (Builder $query, $value): Builder => $query->has('orders', '<=', $value),
                            );
                    }),
                Tables\Filters\Filter::make('total_spending')
                    ->form([
                        Forms\Components\TextInput::make('min_spent')->label('Min Spending')->numeric(),
                        Forms\Components\TextInput::make('max_spent')->label('Max Spending')->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_spent'],
                                fn (Builder $query, $value): Builder => $query->whereHas('orders', function ($q) use ($value) {
                                    $q->selectRaw('user_id, sum(total) as total_sum')
                                      ->groupBy('user_id')
                                      ->havingRaw('sum(total) >= ?', [$value]);
                                }),
                            )
                            ->when(
                                $data['max_spent'],
                                fn (Builder $query, $value): Builder => $query->whereHas('orders', function ($q) use ($value) {
                                    $q->selectRaw('user_id, sum(total) as total_sum')
                                      ->groupBy('user_id')
                                      ->havingRaw('sum(total) <= ?', [$value]);
                                }),
                            );
                    }),
                Tables\Filters\SelectFilter::make('tags')
                    ->label('Customer Tags')
                    ->options(fn() => \App\Models\User::whereNotNull('tags')->pluck('tags')->flatten()->unique()->mapWithKeys(fn($tag) => [$tag => $tag])->toArray())
                    ->searchable()
                    ->multiple()
                    ->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['values'] ?? null,
                            function (Builder $query, $values) {
                                $query->where(function ($q) use ($values) {
                                    foreach ($values as $value) {
                                        $q->orWhereJsonContains('tags', $value);
                                    }
                                });
                            }
                        );
                    }),
                Tables\Filters\SelectFilter::make('division')
                    ->label('Division')
                    ->options(fn() => \App\Models\User::whereNotNull('division')->where('division', '!=', '')->distinct()->pluck('division', 'division')->toArray())
                    ->searchable(),
                Tables\Filters\SelectFilter::make('district')
                    ->label('District')
                    ->options(fn() => \App\Models\User::whereNotNull('district')->where('district', '!=', '')->distinct()->pluck('district', 'district')->toArray())
                    ->searchable(),
            ])
            ->filtersFormColumns(2)
            ->filtersFormWidth('4xl')
                        ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('create_order')
                        ->label('Create Order')
                        ->icon('heroicon-o-shopping-bag')
                        ->url(fn (\App\Models\User $record): string => route('filament.admin.resources.orders.create', ['user_id' => $record->id]))
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('view_orders')
                        ->label('All Orders')
                        ->icon('heroicon-o-list-bullet')
                        ->url(fn (\App\Models\User $record): string => route('filament.admin.resources.orders.index', ['tableFilters[user][value]' => $record->id]))
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('call')
                        ->label('Call')
                        ->icon('heroicon-o-phone')
                        ->url(fn (\App\Models\User $record): string => $record->phone ? 'tel:' . $record->phone : '#')
                        ->visible(fn (\App\Models\User $record): bool => (bool)$record->phone),
                    Tables\Actions\Action::make('whatsapp')
                        ->label('WhatsApp')
                        ->icon('heroicon-o-chat-bubble-left-ellipsis')
                        ->color('success')
                        ->url(fn (\App\Models\User $record): string => $record->phone ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $record->phone) : '#')
                        ->visible(fn (\App\Models\User $record): bool => (bool)$record->phone)
                        ->openUrlInNewTab(),
                ])->icon('heroicon-m-ellipsis-vertical'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListCustomers::route("/"),
            "view" => Pages\ViewCustomer::route("/{record}"),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount("orders")
            ->addSelect([
                'last_order_date' => \App\Models\Order::select('created_at')
                    ->whereColumn('user_id', 'users.id')
                    ->latest()
                    ->take(1),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
