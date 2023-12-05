<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Dotation;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Database\Seeders\RolePermissionSeeder;
use App\Filament\Resources\DotationResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\BelongsToManyMultiSelect;
use App\Filament\Resources\DotationResource\RelationManagers;

class DotationResource extends Resource
{
    protected static ?string $model = Dotation::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationGroup = 'Gestion des employés';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyPermission([
            RolePermissionSeeder::permissionCreateDotation,
            RolePermissionSeeder::permissionEditDotation,
            RolePermissionSeeder::permissionListDotation,
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    BelongsToManyMultiSelect::make('employes')
                    ->required()
                    ->label('Nom employé')
                    ->relationship('employes', 'nomprenoms')
                    ->preload()
                    ,
                    TextInput::make('type_dotation')
                    ->required()
                    ->label('Type de dotation')
                    ,
                    TextInput::make('montant')
                    ->numeric()
                    ->required()
                    ,
                    Textarea::make('description')
                    ,
                    DatePicker::make('date')
                    ->default(Carbon::now())
                    ]) 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employes.nomprenoms')
                ->label('Employé(s)')
                ->searchable()
                ,
                TextColumn::make('type_dotation')
                ->label('Type de dotation')
                ,
                TextColumn::make('montant')
                ,
                TextColumn::make('description')
                ,
                TextColumn::make('date')
                ,
            ])
            ->filters([
                SelectFilter::make('month')
                ->label('Mois')
                ->options([
                    '01' => 'Janvier',
                    '02' => 'Février',
                    '03' => 'Mars',
                    '04' => 'Avril',
                    '05' => 'Mai',
                    '06' => 'Juin',
                    '07' => 'Juillet',
                    '08' => 'Aout',
                    '09' => 'Septembre',
                    '10' => 'Octobre',
                    '11' => 'Novembre',
                    '12' => 'Decembre',
                ])
                ,
                SelectFilter::make('year')
                ->label('Année')
                ->options([
                    '2018' => '2018',
                    '2019' => '2019',
                    '2020' => '2020',
                    '2021' => '2021',
                    '2022' => '2022',
                    '2023' => '2023',
                    '2024' => '2024',
                    '2025' => '2025',
                    '2026' => '2026',
                    '2027' => '2027',
                    '2028' => '2028',
                    '2029' => '2029',
                    '2030' => '2030',
                    '2031' => '2031',
                    '2032' => '2032',
                    '2033' => '2033',
                    
                ])
                ->default('2023')
                ,
            ])
            ->actions([
                Tables\Actions\EditAction::make()
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
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
            'index' => Pages\ListDotations::route('/'),
            'create' => Pages\CreateDotation::route('/create'),
            'edit' => Pages\EditDotation::route('/{record}/edit'),
        ];
    }    
}
