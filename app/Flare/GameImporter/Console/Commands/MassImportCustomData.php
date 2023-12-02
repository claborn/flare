<?php

namespace App\Flare\GameImporter\Console\Commands;

use App\Flare\Models\GameMap;
use App\Flare\Models\InfoPage;
use App\Game\Events\Values\EventType;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class MassImportCustomData extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mass:import-game-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports Game Data in a specific way defined by the programmer';

    /**
     * Execute the console command.
     */
    public function handle() {

        // Handle importing things in a custom format.

        if (GameMap::where('only_during_event_type', EventType::WINTER_EVENT)->count() <= 0) {
            throw new Exception('No map for this type of import was uploaded. Upload the map first.');
        }

        Artisan::call('import:game-data Items');
        Artisan::call('import:game-data Locations');
        Artisan::call('import:game-data Items');
        Artisan::call('import:game-data Skills');
        Artisan::call('import:game-data Monsters');
        Artisan::call('import:game-data Npcs');
        Artisan::call('import:game-data Raids');
        Artisan::call('import:game-data Quests');
        Artisan::call('import:game-data "Admin Section"');

        $this->importInformationSection();

        Artisan::call('reset:trinkets-on-players');

        Artisan::call('generate:monster-cache');
    }

    protected function importInformationSection(): void {
        $data = Storage::disk('data-imports')->get('Admin Section/information.json');

        $data = json_decode(trim($data), true);

        foreach ($data as $modelEntry) {
            InfoPage::updateOrCreate(['id' => $modelEntry['id']], $modelEntry);
        }

        $sourceDirectory      = resource_path('backup/info-sections-images');
        $destinationDirectory = storage_path('app/public');

        $command = 'cp -R ' . escapeshellarg($sourceDirectory) . ' ' . escapeshellarg($destinationDirectory);
        exec($command, $output, $exitCode);

        if ($exitCode === 0) {
            $this->line('Information section images directory copied to public successfully. Information section is now set up.');
        } else {
            $this->line('Failed to copy the information images directory over. You can do this manually from the resources/backup/information-sections-images. Copy the entire directory to app/public');
        }
    }
}
