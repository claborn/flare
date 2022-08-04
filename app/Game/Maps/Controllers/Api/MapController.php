<?php

namespace App\Game\Maps\Controllers\Api;

use App\Flare\Models\GameMap;
use App\Game\Maps\Requests\QuestDataRequest;
use Cache;
use App\Flare\Models\Npc;
use App\Flare\Models\Quest;
use App\Flare\Values\AutomationType;
use App\Game\Maps\Requests\TraverseRequest;
use App\Game\Messages\Events\ServerMessageEvent;
use App\Http\Controllers\Controller;
use App\Flare\Models\User;
use App\Flare\Models\Character;
use App\Flare\Models\Location;
use App\Game\Maps\Services\LocationService;
use App\Game\Maps\Services\MovementService;
use App\Game\Maps\Values\MapTileValue;
use App\Game\Maps\Requests\IsWaterRequest;
use App\Game\Maps\Requests\MoveRequest;
use App\Game\Maps\Requests\SetSailValidation;
use App\Game\Maps\Requests\TeleportRequest;

class MapController extends Controller {

    /**
     * @var MapTileValue $mapTile
     */
    private $mapTile;

    /**
     * @var MovementService $movementService
     */
    private $movementService;

    /**
     * Constructor
     *
     * @param MapTileValue $mapTile
     * @param MovementService $movementService
     */
    public function __construct(MapTileValue $mapTile, MovementService $movementService) {
        $this->mapTile         = $mapTile;
        $this->movementService = $movementService;

        $this->middleware('is.character.dead')->except(['mapInformation', 'fetchQuests']);
    }

    public function mapInformation(Character $character, LocationService $locationService) {
        return response()->json($locationService->getLocationData($character), 200);
    }

    public function move(MoveRequest $request, Character $character, MovementService $movementService) {
        if (!$character->can_move) {
            return response()->json(['invalid input'], 429);
        }

        $xPosition    = $request->character_position_x;
        $yPosition    = $request->character_position_y;

        $location = Location::where('x', $xPosition)
                            ->where('y', $yPosition)
                            ->where('game_map_id', $character->map->game_map_id)
                            ->first();

        if (!is_null($location)) {
            if (!is_null($location->enemy_strength_type) && $character->currentAutomations()->where('type', AutomationType::EXPLORING)->get()->isNotEmpty()) {
                event(new ServerMessageEvent($character->user, 'No. You are currently auto battling and the monsters here are different. Stop auto battling, then enter, then begin again.'));
                return response()->json(['message' => 'You\'re too busy.'], 422);
            }

            if (!$location->can_players_enter) {
                event(new ServerMessageEvent($character->user, 'You cannot enter this location. This is the PVP arena that is only open once per month.'));
                return response()->json(['message' => 'Not allowed to enter.'], 422);
            }
        }

        $response = $movementService->updateCharacterPosition($character, $request->all());

        $status = $response['status'];

        unset($response['status']);

        return response()->json($response, $status);
    }

    public function traverseMaps() {

        return response()->json($this->movementService->getMapsToTraverse(auth()->user()->character));
    }

    public function traverse(TraverseRequest $request, Character $character, MovementService $movementService) {
        if (!$character->can_move) {
            return response()->json(['invalid input'], 429);
        }

        $response = $movementService->updateCharacterPlane($request->map_id, $character);

        $status   = $response['status'];

        unset($response['status']);

        return response()->json($response, $status);
    }

    public function teleport(TeleportRequest $request, Character $character, MovementService $movementService) {
        if (!$character->can_move) {
            return response()->json(['invalid input'], 429);
        }

        $response = $movementService->teleport($character, $request->x, $request->y, $request->cost, $request->timeout);

        $status = $response['status'];

        unset($response['status']);

        return response()->json($response, $status);
    }

    public function setSail(SetSailValidation $request, Character $character, MovementService $movementService) {
        if (!$character->can_move) {
            return response()->json(['invalid input'], 429);
        }

        $response = $movementService->setSail($character, $request->all());

        $status = $response['status'];

        unset($response['status']);

        return response()->json($response, $status);
    }

    public function fetchQuests(QuestDataRequest $request, Character $character) {
        if (!Cache::has('all-quests')) {
            Cache::put('all-quests', Quest::where('is_parent', true)->with('childQuests', 'factionMap', 'rewardItem', 'item', 'npc', 'npc.commands', 'npc.gameMap')->get());
        }

        $data = [
            'quests'           => Cache::get('all-quests'),
            'completed_quests' => $character->questsCompleted()->pluck('quest_id'),
            'map_name'         => $character->map->gameMap->name,
        ];

        $cacheToReset = Cache::get('character-quest-reset');
        $needsRefresh = false;

        if (!is_null($cacheToReset)) {
            $needsRefresh = in_array($character->id, $cacheToReset);
            $index        = array_search($character->id, $cacheToReset);

            if ($index !== false) {
                unset($cacheToReset[$index]);
            }

            Cache::put('character-quest-reset', $cacheToReset);
        }

        if (!$request->completed_quests_only || $needsRefresh) {
            $data['all_quests'] = Cache::get('all-quests');
        }

        $data['was_reset'] = (!$request->completed_quests_only || $needsRefresh);

        return response()->json($data);
    }
}
