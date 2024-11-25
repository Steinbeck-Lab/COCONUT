<?php

namespace App\Filament\Dashboard\Resources\ReportResource\Pages;

use App\Filament\Dashboard\Resources\ReportResource;
use App\Models\Report;
use Archilex\AdvancedTables\AdvancedTables;
use Archilex\AdvancedTables\Components\PresetView;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReports extends ListRecords
{
    use AdvancedTables;

    /**
     * Get the resource for the class.
     */
    protected static string $resource = ReportResource::class;

    /**
     * Returns an array of actions to be displayed in the header.
     *
     * @return array An array containing the create action.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * Returns an array of PresetView objects for the list page.
     *
     * The first preset view is set to default and will be used if no preset view is selected.
     *
     * @return array An array containing the preset views.
     */
    public function getPresetViews(): array
    {
        $presetViews = [
            'submitted' => PresetView::make()
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'submitted'))
                ->favorite()
                ->badge(function () {
                    if (auth()->user()->roles()->exists()) {
                        return Report::query()->where('status', 'submitted')->count();
                    }

                    return Report::query()->where('user_id', auth()->id())->where('status', 'submitted')->count();
                })
                ->preserveAll()
                ->default(),
            'approved' => PresetView::make()
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'approved'))
                ->favorite()
                ->badge(function () {
                    if (auth()->user()->roles()->exists()) {
                        return Report::query()->where('status', 'approved')->count();
                    }

                    return Report::query()->where('user_id', auth()->id())->where('status', 'approved')->count();
                })
                ->preserveAll(),
            'rejected' => PresetView::make()
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'rejected'))
                ->favorite()
                ->badge(function () {
                    if (auth()->user()->roles()->exists()) {
                        return Report::query()->where('status', 'rejected')->count();
                    }

                    return Report::query()->where('user_id', auth()->id())->where('status', 'rejected')->count();
                })
                ->preserveAll(),
        ];
        if (auth()->user()->roles()->exists()) {
            $presetViews['assigned'] = PresetView::make()
                ->modifyQueryUsing(fn ($query) => $query->where('assigned_to', auth()->id()))
                ->favorite()
                ->badge(Report::query()->where('assigned_to', auth()->id())->where('status', 'submitted')->count())
                ->preserveAll();
        }

        return $presetViews;
    }
}
