<?php

namespace App\Game\GuideQuests\Providers;

use App\Flare\Builders\CharacterInformationBuilder;
use App\Flare\Builders\RandomItemDropBuilder;
use App\Flare\Models\Skill;
use App\Game\Core\Services\CharacterPassiveSkills;
use App\Game\GuideQuests\Services\GuideQuestService;
use App\Game\PassiveSkills\Services\PassiveSkillTrainingService;
use App\Game\Skills\Services\AlchemyService;
use App\Game\Skills\Services\CraftingService;
use App\Game\Skills\Services\DisenchantService;
use App\Game\Skills\Services\EnchantingService;
use App\Game\Skills\Services\EnchantItemService;
use App\Game\Skills\Services\SkillService;
use Illuminate\Support\ServiceProvider as ApplicationServiceProvider;

class ServiceProvider extends ApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // @codeCoverageIgnoreStart
        //
        // The test coverage never gets here.
        $this->app->bind(GuideQuestService::class, function($app) {
            return new GuideQuestService(
                $app->make(RandomItemDropBuilder::class)
            );
        });
        // @codeCoverageIgnoreEnd
    }
}