<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Filament\Resources\ActivityResource\RelationManagers;
use Spatie\Activitylog\Models\Activity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Site Management';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->defaultPaginationPageOption(50)
            ->paginationPageOptions([50, 100, 250, 'all'])
            ->columns([
                Tables\Columns\TextColumn::make('log_name')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Subject')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Causer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->label('Date Range')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Date From'),
                        Forms\Components\DatePicker::make('until')->label('Date To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn($q, $d) => $q->whereDate('created_at', '>=', $d))
                            ->when($data['until'] ?? null, fn($q, $d) => $q->whereDate('created_at', '<=', $d));
                    }),
                Tables\Filters\Filter::make('causer')
                    ->label('User Filter')
                    ->form([
                        Forms\Components\Select::make('causer_type')
                            ->label('User Type')
                            ->options([
                                \App\Models\Admin::class => 'Admin / Staff',
                                \App\Models\User::class => 'Customer',
                            ])
                            ->live(),
                        Forms\Components\Select::make('causer_id')
                            ->label('Specific User')
                            ->options(function (Forms\Get $get) {
                                if ($get('causer_type') === \App\Models\Admin::class) {
                                    return \App\Models\Admin::pluck('name', 'id');
                                }
                                if ($get('causer_type') === \App\Models\User::class) {
                                    return \App\Models\User::limit(100)->pluck('name', 'id');
                                }
                                return [];
                            })
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['causer_type'] ?? null, fn($q, $v) => $q->where('causer_type', $v))
                            ->when($data['causer_id'] ?? null, fn($q, $v) => $q->where('causer_id', $v));
                    }),
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options(fn () => \Spatie\Permission\Models\Role::pluck('name', 'name')->toArray())
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) return $query;
                        return $query->where('causer_type', \App\Models\Admin::class)
                            ->whereHasMorph('causer', [\App\Models\Admin::class], function ($q) use ($data) {
                                $q->whereHas('roles', fn ($r) => $r->where('name', $data['value']));
                            });
                    }),
                Tables\Filters\SelectFilter::make('subject_type')
                    ->label('Module')
                    ->options(function () {
                        return \Spatie\Activitylog\Models\Activity::select('subject_type')
                            ->distinct()
                            ->pluck('subject_type', 'subject_type')
                            ->filter()
                            ->mapWithKeys(function ($item) {
                                return [$item => class_basename($item)];
                            })->toArray();
                    }),
                Tables\Filters\SelectFilter::make('event')
                    ->label('Action / Status')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                        'restored' => 'Restored',
                        'login' => 'Login',
                        'logout' => 'Logout',
                    ]),
                Tables\Filters\Filter::make('reference_id')
                    ->label('Reference ID')
                    ->form([
                        Forms\Components\TextInput::make('subject_id')->label('Reference ID (Subject ID)')->numeric(),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query->when($data['subject_id'] ?? null, fn($q, $v) => $q->where('subject_id', $v))),
                Tables\Filters\Filter::make('ip_address')
                    ->label('IP Address')
                    ->form([
                        Forms\Components\TextInput::make('ip')->label('IP Address'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query->when($data['ip'] ?? null, fn($q, $v) => $q->where('properties->ip', 'like', "%{$v}%"))),
            ], layout: Tables\Enums\FiltersLayout::Modal)
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }
}
