<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Site;
use Filament\Tables;
use App\Models\Ville;
use Pages\ViewEmploye;
use App\Models\Employe;
use App\Models\Departement;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Select;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Database\Seeders\RolePermissionSeeder;
use App\Filament\Resources\EmployeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\BelongsToManyMultiSelect;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\EmployeResource\RelationManagers;
use Phpsa\FilamentAuthentication\Resources\UserResource\Pages\ViewUser;

class EmployeResource extends Resource
{
    protected static ?string $model = Employe::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Gestion des employés';

   

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Employé')
                ->tabs([
                    Tabs\Tab::make('Informations personnelles')
                        ->schema([

                            TextInput::make('cin')
                            ->label('Numéro de la pièce d\'identité')
                            ,
                            TextInput::make('matricule')
                            ->required()
                            ->label('Matricule')
                            ,
                            TextInput::make('nomprenoms')
                            ->label('Nom et Prénoms')
                            ->required()
                            ,
                            DatePicker::make('date naissance')
                            ->label('Date de naissance')
                            ->required()
                            ,
                            Select::make('genre')
                            ->options([
                                'M' => 'Masculin',
                                'F' => 'Feminin'
                            ])
                            ->required()
                            ,
                            Select::make('situation')
                            ->label('Situation familiale')
                            ->options([
                                'Marié' => 'Marié',
                                'Célibataire' => 'Célibataire'
                            ])
                            ,
                              
                        ]),
                    Tabs\Tab::make('Résidence')
                        ->schema([
                            TextInput::make('adresse')
                            ->label('Lieu de résidence')
                            ->required()
                            ,
                           
                        ]),
                    Tabs\Tab::make('Contact')
                        ->schema([
                                TextInput::make('telephone')
                                ->label('Télephone 1')
                                ->tel()
                                ,
                                TextInput::make('telephone')
                                ->label('Télephone 2')
                                ->tel()
                                ,
                        ]),
                    Tabs\Tab::make('Informations internes')
                        ->schema([
                            Select::make('departement_id')
                            ->label('Fonction')
                            ->options(
                                function() {
                                    $departements = Departement::all();
                                    $source = [];

                                    foreach ($departements as $key => $value) {
                                        $source[$value->id] = "{$value->nom}";
                                    }

                                    return $source;
                                    
                                }
                            )
                            ->required()
                            ,
                            BelongsToManyMultiSelect::make('sites')
                            ->label('Site')
                            ->relationship('sites', 'description')
                            ->multiple()
                            ->preload()
                            ,
                            Select::make('contrat')
                            ->label('Type de contrat')
                            ->required()
                            ->options([
                                Employe::CDD => 'CDD',
                                Employe::STAGE => 'STAGE',
                                Employe::CDI => 'CDI',
                                Employe::ESSAI => 'ESSAI'
                            ])
                            ,
                            TextInput::make('categorie')
                            ->label('Catégorie')
                            ->required()
                            ,
                            DatePicker::make('date engagement')
                            ->label('Date d\'engagement')
                            ->required()
                            ,
                            DatePicker::make('datefinengagement')
                            ->label('Date fin d\'engagement')
                            ,
                            /*TextInput::make('salaire')
                            ->numeric()
                            ->label('Salaire')
                            ->required()
                            ,
                            */

                            Select::make('statut')
                            ->options([
                                Employe::EN_ACTIVITE => 'En activité',
                                Employe::NON_ACTIVITE => 'Non activité'
                            ])
                            ->default(Employe::EN_ACTIVITE)
                            ,
                            FileUpload::make('photo')
                            ->preserveFilenames()
                            ->image()
                            ->enableOpen()

                            ,
                            FileUpload::make('document')
                            ->label('Les documents')
                            ->preserveFilenames()
                            ->enableReordering()
                            ->enableOpen()
                            ->acceptedFileTypes(['application/pdf'])
                            ->multiple()
                            ,     
                        ]),
                ])
                ->activeTab(1),
                
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('matricule')
                ->sortable()
                ->searchable()
                ,
                TextColumn::make('nomprenoms')
                ->label('Nom et Prénoms')
                ->sortable()
                ->searchable()
                ,
                TextColumn::make('adresse')
                ->sortable()
                ,
                TextColumn::make('telephone')
                ->sortable()
                ,

                TextColumn::make('departement.nom')
                ->label('Fonction')
                ->searchable()
                ->sortable()
                ,
                BadgeColumn::make('sites.description')
                ->sortable()
                ->label('Site')
                ,
                BadgeColumn::make('contrat')
                ->sortable()
                ->label('Type de contrat')
                ,
                TextColumn::make('date engagement')
                ->sortable()
                ->label('Date d\'engagement')
                ,
                TextColumn::make('datefinengagement')
                ->sortable()
                ->label('Date fin d\'engagement')
                ,
                BadgeColumn::make('statut')
                ->sortable()
                    ->enum([
                        Employe::EN_ACTIVITE => "En activité",
                        Employe::NON_ACTIVITE => "Non activité"
                    ])

                ,
                TextColumn::make('categorie')
                ->sortable()
                ->searchable()
                ,
            ])
            ->filters([
                Filter::make('En activité')
                ->query(fn(Builder $query): Builder => $query->where('statut', Employe::EN_ACTIVITE))
                ,
                Filter::make('Non activité')
                ->query(fn(Builder $query): Builder => $query->where('statut', Employe::NON_ACTIVITE))
                ,
                SelectFilter::make('departement')->relationship('departement', 'nom')
                ->label('Fonction')
                ,
                SelectFilter::make('sites')->relationship('sites', 'description')
                ,
                Filter::make('date engagement')
                ->form([
                    Forms\Components\DatePicker::make('du')
                    ->label('Engagé du')
                    ,
                    Forms\Components\DatePicker::make('au')
                    ,
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['du'],
                            fn (Builder $query, $date): Builder => $query->whereDate('date engagement', '>=', $date),
                        )
                        ->when(
                            $data['au'],
                            fn (Builder $query, $date): Builder => $query->whereDate('date engagement', '<=', $date),
                        );
                }),
                
               
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ,
                Tables\Actions\EditAction::make()             
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                ->requiresConfirmation()
                ->before(function() {
                    abort_unless(
                        auth()->user()->hasPermissionTo(RolePermissionSeeder::permissionCreateEmploye),
                        403,
                        "Permission non accordé"
                    );

                })
                ,
                ExportBulkAction::make()
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
            'index' => Pages\ListEmployes::route('/'),
            'create' => Pages\CreateEmploye::route('/create'),
            'edit' => Pages\EditEmploye::route('/{record}/edit'),
            'view' => Pages\ViewEmploye::route('/{record}'),
           
        ];
    }   
     
}
