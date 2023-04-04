<?php

namespace App\Game\Shop\Services;

use App\Flare\Models\GemBagSlot;
use App\Flare\Values\MaxCurrenciesValue;
use App\Game\Core\Events\UpdateTopBarEvent;
use App\Game\Core\Gems\Values\GemTierValue;
use App\Game\Core\Services\CharacterGemBagService;
use App\Game\Core\Traits\ResponseBuilder;

class GemShopService {

    use ResponseBuilder;

    private CharacterGemBagService $characterGemBagService;

    public function __construct(CharacterGemBagService $characterGemBagService) {
        $this->characterGemBagService = $characterGemBagService;
    }

    public function sellGem(Character $character, GemBagSlot $gemBagSlot): array {
        $gemBagSlot = $character->gemBag->gemBagSlots->find($gemBagSlot->id);

        if (!is_null($gemBagSlot)) {
            return $this->errorResult('Gem not found. Nothing to sell.');
        }

        $cost = $this->getCurrencyBack($gemBagSlot);

        $newGoldDust    = $cost['gold_dust'] + $character->gold_dust;
        $newShards      = $cost['shards'] + $character->shards;
        $newCopperCoins = $cost['copper_coins'] + $character->copper_coins;

        if ($newGoldDust >= MaxCurrenciesValue::MAX_GOLD_DUST) {
            $newGoldDust = MaxCurrenciesValue::MAX_GOLD_DUST;
        }

        if ($newShards >= MaxCurrenciesValue::MAX_SHARDS) {
            $newShards = MaxCurrenciesValue::MAX_SHARDS;
        }

        if ($newCopperCoins >= MaxCurrenciesValue::MAX_COPPER) {
            $newCopperCoins = MaxCurrenciesValue::MAX_COPPER;
        }

        $character->update([
            'gold_dust'    => $newGoldDust,
            'shards'       => $newShards,
            'copper_coins' => $newCopperCoins,
        ]);

        event(new UpdateTopBarEvent($character->refresh()));

        $gemBagSlot->delete();

        $message = 'You sold the gem for: ' . number_format($newGoldDust) . ' Gold Dust, ' .
            number_format($newShards) . ' Shards and ' . number_format($newCopperCoins) . ' Copper Coins.';

        return $this->successResult([
            'gems'    => $this->characterGemBagService->getGems($character->refresh())['gem_slots'],
            'message' => $message,
        ]);

    }

    public function sellAllGems(Character $character): array {

        $newGoldDust    = 0;
        $newShards      = 0;
        $newCopperCoins = 0;

        foreach ($character->gemBag->gemBagSlots as $slot) {
            $cost = $this->getCurrencyBack($slot);

            $newGoldDust    += $cost['gold_dust'] + $character->gold_dust;
            $newShards      += $cost['shards'] + $character->shards;
            $newCopperCoins += $cost['copper_coins'] + $character->copper_coins;

            $slot->delete();
        }

        if ($newGoldDust >= MaxCurrenciesValue::MAX_GOLD_DUST) {
            $newGoldDust = MaxCurrenciesValue::MAX_GOLD_DUST;
        }

        if ($newShards >= MaxCurrenciesValue::MAX_SHARDS) {
            $newShards = MaxCurrenciesValue::MAX_SHARDS;
        }

        if ($newCopperCoins >= MaxCurrenciesValue::MAX_COPPER) {
            $newCopperCoins = MaxCurrenciesValue::MAX_COPPER;
        }

        $character->update([
            'gold_dust'    => $newGoldDust,
            'shards'       => $newShards,
            'copper_coins' => $newCopperCoins,
        ]);

        event(new UpdateTopBarEvent($character->refresh()));

        $message = 'You sold the gem for: ' . number_format($newGoldDust) . ' Gold Dust, ' .
            number_format($newShards) . ' Shards and ' . number_format($newCopperCoins) . ' Copper Coins.';

        return $this->successResult([
            'gems'    => $this->characterGemBagService->getGems($character->refresh())['gem_slots'],
            'message' => $message,
        ]);
    }

    protected function getCurrencyBack(GemBagSlot $gemBagSlot): array {
        $cost = (new GemTierValue($gemBagSlot->gem->tier))->maxForTier()['cost'];

        return [
            'gold_dust'    => floor($cost['gold_dust'] * 0.15),
            'shards'       => floor($cost['shards'] * 0.15),
            'copper_coins' => floor($cost['copper_coins'] * 0.15),
        ];
    }
}