<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Department;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardTypeOverview extends BaseWidget
{
    protected function getStats(): array
    {

        return [
            Stat::make('Employees', Employee::all()->count()),
            Stat::make('Country', Country::all()->count()),
            Stat::make('State', State::all()->count()),
            Stat::make('City', City::all()->count()),
            Stat::make('Department', Department::all()->count()),
        ];
    }
}
