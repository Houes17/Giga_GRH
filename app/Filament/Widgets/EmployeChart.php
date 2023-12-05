<?php

namespace App\Filament\Widgets;

use App\Models\Employe;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\LineChartWidget;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\DB; // Importez la classe DB

class EmployeChart extends LineChartWidget
{
    protected static ?string $heading = 'Employés';

    protected function getData(): array
    {
        // Utilisez une requête SQL brute pour sélectionner la colonne date engagement
        $data = DB::table('employes')
            ->selectRaw('YEAR(`date engagement`) as year, MONTH(`date engagement`) as month, COUNT(*) as count')
            ->whereBetween('date engagement', [now()->startOfYear(), now()->endOfYear()])
            ->groupBy('year', 'month')
            ->orderBy('year', 'ASC')
            ->orderBy('month', 'ASC')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Employés engagés',
                    'data' => $data->pluck('count'),
                ],
            ],
            'labels' => $data->map(function ($record) {
                return date('Y-m', mktime(0, 0, 0, $record->month, 1, $record->year));
            }),
        ];
    }
    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole([
            RolePermissionSeeder::RoleAdmin,
            RolePermissionSeeder::RoleSuperAdmin,
        ]);
    }
}




