<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use App\Models\Country;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $ar = Country::where('country_code', 'AR')->withCount('employees')->first();
        $br = Country::where('country_code', 'BR')->withCount('employees')->first();
        $ur = Country::where('country_code', 'UR')->withCount('employees')->first();

        return [
            Stat::make('All Employees', Employee::all()->count()),
            Stat::make('Argentina', $ar ? $ar->employees_count: 0),
            Stat::make('Brasil', $br ? $br->employees_count: 0),
            Stat::make('Uruguay', $ur ? $ur->employees_count: 0)
        ];
    }
}
