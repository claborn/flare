<?php

namespace App\Game\BattleRewardProcessing\Services;

use App\Flare\Models\Character;
use App\Flare\Models\Event;
use App\Flare\Models\GameMap;
use App\Flare\Models\GlobalEventGoal;
use App\Flare\Models\Monster;
use App\Flare\Services\CharacterRewardService;
use App\Game\BattleRewardProcessing\Handlers\FactionHandler;
use App\Game\BattleRewardProcessing\Handlers\GlobalEventParticipationHandler;
use App\Game\BattleRewardProcessing\Handlers\PurgatorySmithHouseRewardHandler;
use App\Game\BattleRewardProcessing\Jobs\BattleItemHandler;
use App\Game\Core\Services\GoldRush;
use App\Game\Events\Values\EventType;

class BattleRewardService {

    private GameMap $gameMap;
    private Monster $monster;
    private Character $character;
    private FactionHandler $factionHandler;
    private CharacterRewardService $characterRewardService;
    private GoldRush $goldRush;
    private GlobalEventParticipationHandler $globalEventParticipationHandler;
    private PurgatorySmithHouseRewardHandler $purgatorySmithHouseRewardHandler;

    public function __construct(
        FactionHandler $factionHandler,
        CharacterRewardService $characterRewardService,
        GoldRush $goldRush,
        GlobalEventParticipationHandler $globalEventParticipationHandler,
        PurgatorySmithHouseRewardHandler $purgatorySmithHouseRewardHandler
    ) {
        $this->factionHandler                   = $factionHandler;
        $this->characterRewardService           = $characterRewardService;
        $this->goldRush                         = $goldRush;
        $this->globalEventParticipationHandler  = $globalEventParticipationHandler;
        $this->purgatorySmithHouseRewardHandler = $purgatorySmithHouseRewardHandler;
    }

    public function setUp(Monster $monster, Character $character): BattleRewardService {

        $this->character = $character;
        $this->monster   = $monster;
        $this->gameMap   = $monster->gameMap;

        $this->characterRewardService->setCharacter($character);

        return $this;
    }

    public function handleBaseRewards() {
        $this->handleFactionRewards();

        $this->characterRewardService->setCharacter($this->character)
            ->distributeCharacterXP($this->monster)
            ->distributeSkillXP($this->monster)
            ->giveCurrencies($this->monster);

        $this->character = $this->characterRewardService->getCharacter();

        $this->goldRush->processPotentialGoldRush($this->character, $this->monster);

        $this->handleGlobalEventGoals();

        $this->purgatorySmithHouseRewardHandler->handleFightingAtPurgatorySmithHouse($this->character, $this->monster);

        BattleItemHandler::dispatch($this->character, $this->monster);
    }

    protected function handleFactionRewards() {
        if (
            $this->gameMap->mapType()->isPurgatory() ||
            $this->gameMap->mapType()->isTheIcePlane()
        ) {
            return;
        }

        $this->factionHandler->handleFaction($this->character, $this->monster);

        $this->character = $this->character->refresh();
    }

    protected function handleGlobalEventGoals() {
        $event = Event::whereIn('type', [
            EventType::WINTER_EVENT,
        ])->first();

        if (is_null($event)) {
            return;
        }

        $globalEventGoal = GlobalEventGoal::where('event_type', $event->type)->first();

        if (is_null($globalEventGoal)) {
            return;
        }

        $this->globalEventParticipationHandler->handleGlobalEventParticipation($this->character->refresh(), $globalEventGoal->refresh());
    }
}
