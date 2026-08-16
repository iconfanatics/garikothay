<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Filament\Resources\AnnouncementResource\RelationManagers;
use App\Models\Announcement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Forms\Components\Section::make('Announcement Details')->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('summary')
                        ->label('Short Summary')
                        ->maxLength(500)
                        ->columnSpanFull(),
                    Forms\Components\RichEditor::make('content')
                        ->columnSpanFull(),
                    Forms\Components\Select::make('type')
                        ->options([
                            'info' => 'Information',
                            'warning' => 'Warning',
                            'promo' => 'Promotional',
                        ])
                        ->default('info')
                        ->required(),
                ])->columns(2),
                Forms\Components\Section::make('Display Rules')->schema([
                    Forms\Components\Select::make('display_location')
                        ->label('Display Location')
                        ->options([
                            'site_wide' => 'Site-wide',
                            'header_bar' => 'Header Bar',
                            'homepage' => 'Homepage',
                            'shop' => 'Shop',
                            'checkout' => 'Checkout',
                        ])
                        ->default('site_wide')
                        ->required(),
                    Forms\Components\TextInput::make('priority')
                        ->label('Priority / Sort Order')
                        ->numeric()
                        ->default(0)
                        ->helperText('Higher numbers are shown first.'),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->columnSpanFull(),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('Starts At (Optional)')
                            ->minDate(fn (string $context) => $context === 'create' ? now() : null)
                            ->before('expires_at'),
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Expires At (Optional)')
                            ->after('starts_at'),
                    ]),
                ])->columns(2),
                Forms\Components\Section::make('Appearance')->schema([
                    Forms\Components\TextInput::make('button_text')
                        ->label('Button Text')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('button_url')
                        ->label('Button URL')
                        ->maxLength(255),
                    Forms\Components\Toggle::make('open_in_new_tab')
                        ->label('Open Link in New Tab')
                        ->default(false),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->defaultPaginationPageOption(50)
            ->paginationPageOptions([50, 100, 250, 'all'])
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'info',
                        'warning' => 'warning',
                        'success' => 'promo',
                    ]),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime('d M Y, h:i A')
                    ->placeholder('Immediately')
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime('d M Y, h:i A')
                    ->placeholder('Never')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('search')
                    ->label('Search')
                    ->form([
                        Forms\Components\TextInput::make('query')->label('Search Title or Content')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['query'] ?? null,
                            fn (Builder $q, $search) => $q->where('title', 'like', "%{$search}%")
                                ->orWhere('content', 'like', "%{$search}%")
                                ->orWhere('summary', 'like', "%{$search}%")
                        );
                    }),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'info' => 'Information',
                        'warning' => 'Warning',
                        'promo' => 'Promotional',
                    ]),
                Tables\Filters\SelectFilter::make('display_location')
                    ->options([
                        'site_wide' => 'Site-wide',
                        'header_bar' => 'Header Bar',
                        'homepage' => 'Homepage',
                        'shop' => 'Shop',
                        'checkout' => 'Checkout',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive'),
                Tables\Filters\Filter::make('created_at')
                    ->label('Date Range (Created)')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Created From'),
                        Forms\Components\DatePicker::make('created_until')->label('Created Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'] ?? null, fn($q, $d) => $q->whereDate('created_at', '>=', $d))
                            ->when($data['created_until'] ?? null, fn($q, $d) => $q->whereDate('created_at', '<=', $d));
                    }),
            ], layout: Tables\Enums\FiltersLayout::Modal)
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (\Illuminate\Database\Eloquent\Collection $records) => $records->each->update(['is_active' => true]))
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->action(fn (\Illuminate\Database\Eloquent\Collection $records) => $records->each->update(['is_active' => false]))
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

        public static function getWidgets(): array
    {
        return [
            AnnouncementResource\Widgets\AnnouncementStatsOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
