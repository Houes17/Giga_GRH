<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Site;
use Filament\Tables;
use App\Models\Employe;
use App\Models\Faction;
use App\Models\Presence;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Database\Seeders\RolePermissionSeeder;
use App\Filament\Resources\PresenceResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\PresenceResource\RelationManagers;
use App\Models\Departement;
use Database\Seeders\DepartementSeeder;

class PresenceResource extends Resource
{
    protected static ?string $model = Presence::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Gestion des présences';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyPermission([
            RolePermissionSeeder::permissionListPresence,
        ]);
    }

    public static function getDependencies(): array
    {
        return [
            'sites' => Site::all(),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    Select::make('employe_id')
                    ->label('Nom Employé')
                    ->required()
                    ->searchable()
                    ->options(function () {
                        $employe = Employe::all();
                            $source = [];

                            foreach ($employe as $key => $value) {
                                $source[$value->id] = "{$value->nomprenoms}";
                            }

                            return $source;
                    })
                    ,
                    Select::make('site_id')
                    ->label('Site')
                    ->required()
                    ->reactive()
                    ->options(function () {
                        return Site::pluck('description', 'id');
                    })
                    ->searchable()
                    ,
                    Select::make("faction_id")
                            ->label('Présence')
                            ->searchable()
                            ->required()
                            ->options(function(){
                                $factions = Faction::all();

                                $source = [];

                                foreach ($factions as $key => $value) {
                                    $source[$value->id] = "{$value->nom}";
                                }
    
                                return $source;
                            })
                    ,
                    TextInput::make('effectuer par')
                    ->disabled()
                    ->default(Auth()->user()->name)
                    ,
                    Textarea::make('observation')
                    ,
                    DatePicker::make('date')
                            ->required()
                            ->default(Carbon::now())
                    ,

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        // Vérifier si l'utilisateur a le rôle d'administrateur
        $isAdmin = Auth::user()->hasRole(RolePermissionSeeder::RoleAdmin) || Auth::user()->hasRole(RolePermissionSeeder::RoleSuperAdmin) || Auth::user()->hasRole(RolePermissionSeeder::RoleComptable) ;

        // Ajouter les colonnes de la table
        $table->columns([
            TextColumn::make('employe.matricule')
                ->label('Matricule')
                ->searchable()
                ,
                TextColumn::make('employe.nomprenoms')
                ->searchable()
                ->label('Nom et Prénoms')
                ,
                BadgeColumn::make('faction.nom')
                    ->label('Faction')
                ,
                BadgeColumn::make('faction.nbre_heure')
                    ->label('Nombre d\'heure')
                ,
                TextColumn::make('effectuer par')
                ,
                TextColumn::make('date')
                ,
                TextColumn::make('site.description')
                ->label("Site")
                ,
                TextColumn::make('observation')
                ,
        ])
        ->defaultSort('date', 'desc');

        // Ajouter les autres filtres
        $table->filters([
            Filter::make('date')
                ->form([
                    Forms\Components\DatePicker::make('du'),
                    Forms\Components\DatePicker::make('au'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['du'],
                            fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                        )
                        ->when(
                            $data['au'],
                            fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                        );
                }),

            SelectFilter::make('site')->relationship('site', 'description'),

            SelectFilter::make('faction_id')
            ->label('Faction')
            ->options(function(){
                $factions = Faction::all();

                $source = [];

                foreach ($factions as $key => $value) {
                    $source[$value->id] = "{$value->nom}";
                }

                return $source;
            })
        ]);

        // Ajouter le SelectFilter "effectuer par" uniquement si l'utilisateur n'est pas un administrateur
        if (!$isAdmin) {
            $table->filters([
                SelectFilter::make("effectuer par")
                    ->default(Auth()->user()->name)
                    ,
                Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('du'),
                        Forms\Components\DatePicker::make('au'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['du'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['au'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
    
                SelectFilter::make('site')->relationship('site', 'description'),
    
                SelectFilter::make('faction_id')
                ->label('Faction')
                ->options(function(){
                    $factions = Faction::all();
    
                    $source = [];
    
                    foreach ($factions as $key => $value) {
                        $source[$value->id] = "{$value->nom}";
                    }
    
                    return $source;

                
                    
                })
            ]);
        }

        // Ajouter les actions de la table
        $table->actions([
            Tables\Actions\EditAction::make(),
        ]);

        // Ajouter les actions groupées de la table
        $table->bulkActions([
            Tables\Actions\DeleteBulkAction::make()
            ->before(function() {
                abort_unless(
                    auth()->user()->hasPermissionTo(RolePermissionSeeder::permissionEditPresence),
                    403,
                    "Permission non accordé"
                );

            })
            ,
            ExportBulkAction::make()
        ]);

        return $table;
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
            'index' => Pages\ListPresences::route('/'),
            'create' => Pages\CreatePresence::route('/create'),
            'edit' => Pages\EditPresence::route('/{record}/edit'),
        ];
    }    
}
