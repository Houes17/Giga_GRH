<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Site;
use Filament\Tables;
use App\Models\Ville;
use App\Models\Employe;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
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
use App\Filament\Resources\SiteResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\BelongsToManyMultiSelect;
use App\Filament\Resources\SiteResource\RelationManagers;

class SiteResource extends Resource
{
    protected static ?string $model = Site::class;

    protected static ?string $navigationIcon = 'heroicon-o-office-building';
    protected static ?string $navigationGroup = 'Gestion des employés';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyPermission([
            RolePermissionSeeder::permissionCreateSite,
            RolePermissionSeeder::permissionEditSite,
            RolePermissionSeeder::permissionListSite,
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    TextInput::make('description')
                    ->required()
                    ,
                    
                    Select::make('ville_id')
                    ->label('Ville')
                    ->options(
                        function() {
                            $villes = Ville::all();
                            $source = [];

                            foreach ($villes as $key => $value) {
                                $source[$value->id] = "{$value->nom}";
                            }

                            return $source;
                            
                        }
                    )
                   ->required()
                   ,
                   TextInput::make('salaire')
                   ->label('Salaire brut')
                   ->numeric()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')
                ->searchable()
                ,
                TextColumn::make('ville.nom')
                ->label('Ville')
                ->searchable()
                ,
                BadgeColumn::make('controleurs.nomprenoms')
                ->label('Controleur')
                ,
                BadgeColumn::make('chefagent.nomprenoms')
                ->label('Chef agent')
                ,
                BadgeColumn::make('ads.nomprenoms')
                ->label('Les agents')
                ,
               TextColumn::make('salaire')
               ->label('Salaire brut')
              
            ])
            ->filters([
                SelectFilter::make('ville')->relationship('ville', 'nom')
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
            'index' => Pages\ListSites::route('/'),
            'create' => Pages\CreateSite::route('/create'),
            'edit' => Pages\EditSite::route('/{record}/edit'),
        ];
    }    
}
