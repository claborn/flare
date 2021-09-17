<?php

namespace App\Game\Core\Controllers;

use App\Flare\Models\Character;
use App\Flare\Models\GameSkill;
use App\Game\Core\Requests\UseMultipleItemsRequest;
use App\Game\Core\Services\UseItemService;
use App\Game\Skills\Values\SkillTypeValue;
use App\Http\Controllers\Controller;
use League\Fractal\Manager;
use App\Flare\Transformers\CharacterAttackTransformer;
use App\Flare\Models\Item;
use App\Flare\Traits\Controllers\ItemsShowInformation;


class ItemsController extends Controller {

    use ItemsShowInformation;

    /**
     * @var Manager $manager
     */
    private $useItemService;

    /**
     * ItemsController constructor.
     *
     * @param UseItemService $useItemService
     */
    public function  __construct(UseItemService $useItemService) {
        $this->useItemService = $useItemService;
    }

    public function show(Item $item) {
        return $this->renderItemShow('game.items.item', $item);
    }
}
