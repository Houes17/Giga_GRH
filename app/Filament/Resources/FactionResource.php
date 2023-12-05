<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Faction;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Faker\Provider\ar_EG\Text;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FactionResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FactionResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;
use Database\Seeders\RolePermissionSeeder;

class FactionResource extends Resource
{
    protected static ?string $model = Faction::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Gestion des présences';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole([
                RolePermissionSeeder::RoleSuperAdmin,
                RolePermissionSeeder::RoleAdmin
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    TextInput::make('nom')
                    ->required()
                    ,
                    TextInput::make('nbre_heure')
                    ->required()
                    ->numeric()
                    ,
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')
                ,
                TextColumn::make('nbre_heure')
                ->label('Nombre d\'heures')
            ])
            ->filters([
                //
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
            'index' => Pages\ListFactions::route('/'),
            'create' => Pages\CreateFaction::route('/create'),
            'edit' => Pages\EditFaction::route('/{record}/edit'),
        ];
    }    
}
