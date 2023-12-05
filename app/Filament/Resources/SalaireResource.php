<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Employe;
use App\Models\Salaire;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Database\Seeders\RolePermissionSeeder;
use App\Filament\Resources\SalaireResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SalaireResource\RelationManagers;

class SalaireResource extends Resource
{
    protected static ?string $model = Salaire::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Comptabilité';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyPermission([
            RolePermissionSeeder::permissionCreateSalaire,
            RolePermissionSeeder::permissionEditSalaire,
            RolePermissionSeeder::permissionListSalaire,
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    Select::make('employe_id')
                    ->label('Nom de l\'employé')
                    ->required()
                    ->options(
                        function() {
                            $employe = Employe::all();
                            $source = [];

                            foreach ($employe as $key => $value) {
                                $source[$value->id] = "{$value->nomprenoms}";
                            }

                            return $source;
                            
                        }
                    )
                    ,
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
                    Select::make("etat")
                    ->required()
                    ->options([
                        Salaire::Non_Payé => "Non Payé",
                        Salaire::Payé => "Payé",
                    ])
                    ->default(Salaire::Non_Payé)
                    ,
                ])
                ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employe.matricule')
                ->label('Matricule')
                ->searchable()
                ,
                TextColumn::make('employe.nomprenoms')
                ->searchable()
                ->label('Nom et Prénoms')
                ,
                TextColumn::make('nbre_heures_travail')
                ->label('Nbre heures travail')
                ->searchable()
                ,
                TextColumn::make('nbre_heure_repos')
                ->label('Nbre heures repos')
                ->searchable()
                ,
                TextColumn::make('nbre_absence')
                ->label('Nbre jour absence')
                ->searchable()
                ,
                TextColumn::make('salaire total')
                ->label('Salaire net')
                ->searchable()
                ,
                TextColumn::make('mois')
                ->searchable()
                ,
                TextColumn::make('année')
                ->searchable()
                ,
                BadgeColumn::make('etat')
                ->enum([
                    Salaire::Non_Payé => "Non Payé",
                    Salaire::Payé => "Payé",
                ])
                ->colors([
                    'success' =>  Salaire::Payé,
                    'danger' =>   Salaire::Non_Payé
                ]
                )
            ,
            ])
            ->filters([
                SelectFilter::make('etat')
                ->options([
                    Salaire::Non_Payé => "Non Payé",
                    Salaire::Payé => "Payé",
                ])
                ,
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
                Action::make('Télécharger Pdf')
                ->icon('heroicon-o-document-download')
                ->url(fn (Salaire $record) => route('salary.pdf.download', $record))
                ->openUrlInNewTab(),
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
            'index' => Pages\ListSalaires::route('/'),
            'create' => Pages\CreateSalaire::route('/create'),
            'edit' => Pages\EditSalaire::route('/{record}/edit'),
        ];
    }    
}
