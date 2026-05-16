<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Application;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ApplicationStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Applications', Application::count())
                ->description('All time')
                ->color('primary'),

            Stat::make('Pending Review', Application::where('status', 'submitted')->count())
                ->description('Needs attention')
                ->color('warning'),

            Stat::make('Invited for Test', Application::where('status', 'invited_for_test')->count())
                ->description('Test scheduled')
                ->color('info'),

            Stat::make('Accepted', Application::where('status', 'accepted')->count())
                ->description('Successfully recruited')
                ->color('success'),
        ];
    }
}
