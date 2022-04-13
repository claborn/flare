<?php

namespace App\Game\Skills\Controllers\Api;

use App\Game\Core\Events\UpdateTopBarEvent;
use App\Flare\Models\Inventory;
use App\Flare\Models\InventorySlot;
use App\Game\Core\Events\CharacterInventoryDetailsUpdate;
use App\Game\Core\Events\CharacterInventoryUpdateBroadCastEvent;
use App\Game\Messages\Events\ServerMessageEvent;
use App\Http\Controllers\Controller;
use App\Flare\Models\Item;
use App\Game\Skills\Services\DisenchantService;
use Illuminate\Support\Facades\Cache;

class DisenchantingController extends Controller {

    /**
     * @var DisenchantService $disenchantingService
     */
    private $disenchantingService;

    /**
     * Constructor
     *
     * @param DisenchantService $disenchantService
     */
    public function __construct(DisenchantService $disenchantService) {
        $this->disenchantingService = $disenchantService;
    }

    public function disenchant(Item $item) {
        $character = auth()->user()->character;

        $inventory = Inventory::where('character_id', $character->id)->first();

        $foundItem = InventorySlot::where('equipped', false)->where('item_id', $item->id)->where('inventory_id', $inventory->id)->first();

        if (is_null($foundItem)) {
            return response()->json(['message' => 'This item cannot be disenchanted!'], 422);
        }

        if (is_null($foundItem->item->item_suffix_id) && is_null($foundItem->item->item_prefix_id)) {
            return response()->json(['message' => 'This item cannot be disenchanted!'], 422);
        }

        if (!is_null($foundItem)) {
            if ($foundItem->item->type === 'quest') {
                event(new ServerMessageEvent($character->user, 'Item cannot be destroyed or does not exist. (Quest items cannot be destroyed or disenchanted)'));
                return response()->json([], 200);
            }

            $this->disenchantingService->disenchantWithSkill($character, $foundItem);

            event(new CharacterInventoryUpdateBroadCastEvent($character->user, 'inventory'));

            event(new CharacterInventoryDetailsUpdate($character->user));

            event(new UpdateTopBarEvent($character->refresh()));
        }

        return response()->json([], 200);
    }

    public function destroy(Item $item) {
        $character = auth()->user()->character;

        $inventory = Inventory::where('character_id', $character->id)->first();

        $foundSlot = InventorySlot::where('item_id', $item->id)->where('inventory_id', $inventory->id)->first();

        if (!is_null($foundSlot)) {
            if ($foundSlot->item->type === 'quest') {
                event(new ServerMessageEvent($character->user, 'Item cannot be destroyed or does not exist. (Quest items cannot be destroyed or disenchanted)'));
                return response()->json([], 200);
            }

            $name = $foundSlot->item->affix_name;

            $foundSlot->delete();

            event(new ServerMessageEvent($character->user, 'Destroyed: ' . $name));

            event(new CharacterInventoryUpdateBroadCastEvent($character->user, 'inventory'));

            event(new CharacterInventoryDetailsUpdate($character->user));

            event(new UpdateTopBarEvent($character->refresh()));
        }

        return response()->json([], 200);
    }
}
