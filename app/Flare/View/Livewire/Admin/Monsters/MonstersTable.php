<?php

namespace App\Flare\View\Livewire\Admin\Monsters;

use App\Flare\Models\GameMap;
use App\Flare\Models\Monster;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Flare\Models\Item;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class MonstersTable extends DataTableComponent {

    public function configure(): void {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder {

        return Monster::query()->when(
            $this->getAppliedFilterWithValue('maps'), function ($query, $map) {
                $celestialType = false;

                if (str_contains($map, 'Celestials')) {
                    $celestialType = true;

                    $gameMapId     = GameMap::where('name', trim(str_replace('Celestials', '', $map)))->first()->id;
                } else {
                    $gameMapId     = GameMap::where('name', $map)->first()->id;
                }

                return $query->where('game_map_id', $gameMapId)->where('is_celestial_entity', $celestialType);
            });
    }

    public function filters(): array {
        return [
            SelectFilter::make('Maps')
                ->options([
                    'Surface'                 =>  'Surface',
                    'Labyrinth'               => 'Labyrinth',
                    'Dungeons'                =>  'Dungeons',
                    'Shadow Plane'            => 'Shadow Plane',
                    'Purgatory'               => 'Purgatory',
                    'Surface Celestials'      => 'Surface Celestials',
                    'Labyrinth Celestials'    => 'Labyrinth Celestials',
                    'Dungeons Celestials'     => 'Dungeons Celestials',
                    'Shadow Plane Celestials' => 'Shadow Planes Celestials',
                    'Purgatory Celestials'    => 'Purgatory Celestials',
                ]),
        ];
    }

    public function columns(): array {
        return [
            Column::make('Name')->format(function ($value, $row) {
                $monsterId = Monster::where('name', $value)->first()->id;

                if (auth()->user()->hasRole('Admin')) {
                    return '<a href="/admin/monsters/'. $monsterId.'">'.$row->name . '</a>';
                }

                return '<a href="/monsters/'. $monsterId.'" >'.$row->name . '</a>';
            })->html(),
            Column::make('Plane', 'gameMap.name')->searchable(),

            Column::make('Damage Stat', 'damage_stat')->sortable(),
            Column::make('Xp', 'xp')->sortable()->format(function ($value) {
                return number_format($value);
            }),
            Column::make('Gold Reward', 'gold')->sortable()->format(function ($value) {
                return number_format($value);
            }),
        ];
    }
}