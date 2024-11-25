<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ApplicationOverview;

class Dashboard extends \Filament\Pages\Dashboard
{
    /**
     * The navigation icon for the page.
     */
    protected static ?string $navigationIcon = 'heroicon-s-cog';

    /**
     * The view for the page.
     */
    protected static string $view = 'filament.pages.dashboard';

    /**
     * The title for the page.
     */
    protected static ?string $title = 'Control Panel';

    /**
     * Retrieves an array of widgets to be displayed in the header of the page.
     *
     * This function returns an array of widget class names that define the widgets to be displayed
     * in the header of the page, such as the ApplicationOverview widget that displays key metrics
     * about the application.
     *
     * @return array An array of widget class names.
     */
    public function getHeaderWidgets(): array
    {
        return [
            ApplicationOverview::class,
        ];
    }
}
