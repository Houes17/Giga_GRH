<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Ville;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Database\Seeders\RolePermissionSeeder;
use App\Filament\Resources\VilleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VilleResource\RelationManagers;

class VilleResource extends Resource
{
    protected static ?string $model = Ville::class;

    protected static ?string $navigationGroup = 'Gestion des employés';
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    
    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyPermission([
            RolePermissionSeeder::permissionCreateVille,
            RolePermissionSeeder::permissionEditVille,
            RolePermissionSeeder::permissionListVille,
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    TextInput::make('nom')
                    ,
                    Select::make('region')
                    ->options([
                        'région maritime' => 'Région Maritime',
                        'région des plateaux' => ' Région des Plateaux',
                        'région centrale'     => 'Région Centrale',
                        'région de la kara' =>  'Région de la Kara',
                        'région de la savane' => 'Région de la Savane'


                    ])

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')
                ,
                TextColumn::make('region')
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
            'index' => Pages\ListVilles::route('/'),
            'create' => Pages\CreateVille::route('/create'),
            'edit' => Pages\EditVille::route('/{record}/edit'),
        ];
    }    
}
