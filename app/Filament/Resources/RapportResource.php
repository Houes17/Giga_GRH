<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Site;
use Filament\Tables;
use App\Models\Rapport;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Database\Seeders\RolePermissionSeeder;
use App\Filament\Resources\RapportResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RapportResource\RelationManagers;

class RapportResource extends Resource
{
    protected static ?string $model = Rapport::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Comptabilité';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyPermission([
            RolePermissionSeeder::permissionCreateRapport,
            RolePermissionSeeder::permissionEditRapport,
            RolePermissionSeeder::permissionListRapport,
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    Select::make('site_id')
                    ->label('Site')
                    ->options(
                        function() {
                            $site = Site::all();
                            $source = [];
    
                            foreach ($site as $key => $value) {
                                $source[$value->id] = "{$value->description}";
                            }
    
                            return $source;
                            
                        }
                    ),
                    Select::make('mois')
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
                    ->required()
                    ->default('05')
                    ,
                    Select::make('année')
                    ->options([
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
                    ->required()
                    ->default('2023')
                    ,
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sites.description')
                ->searchable()
                ,
                TextColumn::make('totalemployé')
                ->label('Total des employés')
                ,
                TextColumn::make('montantdotation')
                ->label('Montant dotation')
                ,
                TextColumn::make('totalsalaire')
                ->label('Salaire total')
                ,
                BadgeColumn::make('depensetotal')
                ->label('Dépense totale')
                ,
                TextColumn::make('mois')
                ,
                TextColumn::make('année')
            ])
            ->filters([
                SelectFilter::make('mois')
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
                SelectFilter::make('année')
                ->options([
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListRapports::route('/'),
            'create' => Pages\CreateRapport::route('/create'),
            'edit' => Pages\EditRapport::route('/{record}/edit'),
        ];
    }    
}
