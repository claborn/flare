<?php

namespace App\Game\Core\Listeners;

use App\Game\Core\Traits\CanHaveQuestItem;
use App\Game\Core\Events\CharacterInventoryUpdateBroadCastEvent;
use App\Game\Core\Events\DropsCheckEvent;
use App\Flare\Builders\RandomItemDropBuilder;
use App\Flare\Events\ServerMessageEvent;
use App\Flare\Models\Item;
use App\Flare\Models\ItemAffix;
use App\Game\Messages\Events\GlobalMessageEvent;
use Facades\App\Flare\Calculators\DropCheckCalculator;

class DropsCheckListener
{

    use CanHaveQuestItem;

    /**
     * Handle the event.
     *
     * @param DropsCheckEvent $event
     * @return void
     */
    public function handle(DropsCheckEvent $event)
    {
        $lootingChance  = $event->character->skills->where('name', '=', 'Looting')->first()->skill_bonus;
        $gameMap        = $event->character->map->gameMap;
        $gameMapBonus   = 0.0;

        if (!is_null($gameMap->drop_chance_bonus)) {
            $gameMapBonus = $gameMap->drop_chance_bonus;
        }

        $canGetDrop     = DropCheckCalculator::fetchDropCheckChance($event->monster, $lootingChance, $gameMapBonus, $event->adventure);

        if ($canGetDrop) {
            $drop = resolve(RandomItemDropBuilder::class)
                        ->setItemAffixes(ItemAffix::where('can_drop', true)->get())
                        ->setMonsterPlane($event->monster->gameMap->name)
                        ->setCharacterLevel($event->character->level)
                        ->setMonsterMaxLevel($event->monster->max_level)
                        ->generateItem();

            if (!is_null($drop)) {
                if (!is_null($drop->itemSuffix) || !is_null($drop->itemPrefix)) {
                    $this->attemptToPickUpItem($event, $drop);

                    event(new CharacterInventoryUpdateBroadCastEvent($event->character->user));
                }
            }
        }

        if (!is_null($event->monster->quest_item_id)) {
            $canGetQuestItem = DropCheckCalculator::fetchQuestItemDropCheck($event->monster, $lootingChance, $gameMapBonus, $event->adventure);

            if ($canGetQuestItem) {
                $this->attemptToPickUpItem($event, $event->monster->questItem);

                event(new CharacterInventoryUpdateBroadCastEvent($event->character->user));
            }
        }
    }

    protected function attemptToPickUpItem(DropsCheckEvent $event, Item $item) {
        dump($event->character->isInventoryFull());
        if (!$event->character->isInventoryFull()) {

            if ($this->canHaveItem($event->character, $item)) {
                $event->character->inventory->slots()->create([
                    'item_id' => $item->id,
                    'inventory_id' => $event->character->inventory->id,
                ]);

                if ($item->type === 'quest') {
                    $message = $event->character->name . ' has found: ' . $item->affix_name;

                    broadcast(new GlobalMessageEvent($message));
                }

                event(new ServerMessageEvent($event->character->user, 'gained_item', $item->affix_name, route('game.items.item', [
                    'item' => $item
                ]), $item->id));
            }
        } else {
            event(new ServerMessageEvent($event->character->user, 'inventory_full'));
        }
    }
}
