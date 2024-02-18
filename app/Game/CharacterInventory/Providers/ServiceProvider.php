<?php

namespace App\Game\CharacterInventory\Providers;

use App\Game\CharacterInventory\AutoEquipHandlers\HandPositionsFromInventory;
use App\Game\CharacterInventory\Handlers\EquipBest\FetchBestItemForPositionFromInventory;
use App\Game\CharacterInventory\Handlers\EquipBest\HandleHands;
use App\Game\CharacterInventory\Handlers\EquipBest\HandleRegularComparisonAndReplace;
use App\Game\CharacterInventory\Handlers\EquipBest\HandleTrinketsAndArtifacts;
use App\Game\CharacterInventory\Handlers\EquipBest\HandleUniquesAndMythics;
use App\Game\CharacterInventory\Handlers\EquipBest\InventoryItemComparison;
use App\Game\CharacterInventory\Services\EquipBestItemForSlotsTypesService;
use App\Game\CharacterInventory\Validations\SetHandsValidation;
use App\Game\Core\Comparison\ItemComparison;
use League\Fractal\Manager;
use Illuminate\Support\ServiceProvider as ApplicationServiceProvider;
use App\Flare\Transformers\CharacterAttackTransformer;
use App\Flare\Transformers\CharacterSheetBaseInfoTransformer;
use App\Flare\Transformers\InventoryTransformer;
use App\Flare\Transformers\Serializers\CoreSerializer;
use App\Flare\Transformers\UsableItemTransformer;
use App\Game\CharacterInventory\Services\CharacterInventoryService;
use App\Game\CharacterInventory\Services\ComparisonService;
use App\Game\CharacterInventory\Services\EquipItemService;
use App\Game\CharacterInventory\Services\InventorySetService;
use App\Game\CharacterInventory\Services\UseItemService;
use App\Game\Core\Values\ValidEquipPositionsValue;
use App\Game\Gems\Services\ItemAtonements;
use App\Game\Skills\Services\MassDisenchantService;
use App\Game\Skills\Services\UpdateCharacterSkillsService;

class ServiceProvider extends ApplicationServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->app->bind(Manager::class, function ($app) {
            $manager = new Manager();

            // Attach the serializer
            $manager->setSerializer(new CoreSerializer());

            return $manager;
        });

        $this->app->bind(SetHandsValidation::class, function () {
            return new SetHandsValidation();
        });

        $this->app->bind(InventorySetService::class, function ($app) {
            return new InventorySetService(
                $app->make(
                    SetHandsValidation::class
                )
            );
        });

        $this->app->bind(EquipItemService::class, function ($app) {
            return new EquipItemService($app->make(Manager::class), $app->make(CharacterAttackTransformer::class), $app->make(InventorySetService::class));
        });

        $this->app->bind(CharacterInventoryService::class, function ($app) {
            return new CharacterInventoryService(
                $app->make(InventoryTransformer::class),
                $app->make(UsableItemTransformer::class),
                $app->make(MassDisenchantService::class),
                $app->make(UpdateCharacterSkillsService::class),
                $app->make(Manager::class)
            );
        });

        $this->app->bind(UseItemService::class, function ($app) {
            return new UseItemService(
                $app->make(Manager::class),
                $app->make(CharacterSheetBaseInfoTransformer::class),
            );
        });

        $this->app->bind(InventoryItemComparison::class, function($app) {
            return new InventoryItemComparison(
                $app->make(ItemComparison::class)
            );
        });

        $this->app->bind(HandleHands::class, function($app) {
            return new HandleHands(
                $app->make(EquipItemService::class),
                $app->make(InventoryItemComparison::class),
            );
        });

        $this->app->bind(HandleUniquesAndMythics::class, function($app) {
            return new HandleUniquesAndMythics(
                $app->make(EquipItemService::class),
                $app->make(InventoryItemComparison::class),
            );
        });

        $this->app->bind(HandleTrinketsAndArtifacts::class, function($app) {
            return new HandleTrinketsAndArtifacts(
                $app->make(EquipItemService::class),
                $app->make(InventoryItemComparison::class),
            );
        });

        $this->app->bind(HandleRegularComparisonAndReplace::class, function($app) {
            return new HandleRegularComparisonAndReplace(
                $app->make(EquipItemService::class),
                $app->make(InventoryItemComparison::class),
            );
        });

        $this->app->bind(FetchBestItemForPositionFromInventory::class, function($app) {
            return new FetchBestItemForPositionFromInventory(
                $app->make(InventoryItemComparison::class)
            );
        });

        $this->app->bind(EquipBestItemForSlotsTypesService::class, function($app) {
            return new EquipBestItemForSlotsTypesService(
                $app->make(FetchBestItemForPositionFromInventory::class),
                $app->make(HandleHands::class),
                $app->make(HandleUniquesAndMythics::class),
                $app->make(HandleTrinketsAndArtifacts::class),
                $app->make(HandleRegularComparisonAndReplace::class),
            );
        });

        $this->app->bind(ComparisonService::class, function ($app) {
            return new ComparisonService(
                $app->make(ValidEquipPositionsValue::class),
                $app->make(CharacterInventoryService::class),
                $app->make(EquipItemService::class),
                $app->make(ItemAtonements::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
    }
}
