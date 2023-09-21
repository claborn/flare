<?php

namespace App\Flare\Transformers;

use App\Flare\Models\Skill;
use App\Flare\Models\Character;
use App\Flare\Models\GameClass;
use App\Flare\Models\GameSkill;
use App\Flare\Values\AutomationType;
use App\Flare\Values\ClassAttackValue;
use App\Game\Skills\Values\SkillTypeValue;
use App\Flare\Builders\CharacterInformation\CharacterStatBuilder;

class CharacterSheetBaseInfoTransformer extends BaseTransformer {

    private bool $ignoreReductions = false;

    public function setIgnoreReductions(bool $ignoreReductions): void {
        $this->ignoreReductions = $ignoreReductions;
    }

    /**
     * Gets the response data for the character sheet
     *
     * @param Character $character
     * @return array
     */
    public function transform(Character $character): array {
        $characterStatBuilder         = resolve(CharacterStatBuilder::class)->setCharacter($character, $this->ignoreReductions);
        $gameClass                    = GameClass::find($character->game_class_id);

        return [
            'id'                          => $character->id,
            'user_id'                     => $character->user_id,
            'name'                        => $character->name,
            'class'                       => $gameClass->name,
            'class_id'                    => $gameClass->id,
            'race'                        => $character->race->name,
            'race_id'                     => $character->race->id,
            'to_hit_stat'                 => $character->class->to_hit_stat,
            'damage_stat'                 => $character->class->damage_stat,
            'inventory_max'               => $character->inventory_max,
            'inventory_count'             => $character->getInventoryCount(),
            'level'                       => number_format($character->level),
            'max_level'                   => number_format($this->getMaxLevel($character)),
            'xp'                          => (int) $character->xp,
            'xp_next'                     => (int) $character->xp_next,
            'str_modded'                  => $characterStatBuilder->statMod('str'),
            'dur_modded'                  => $characterStatBuilder->statMod('dur'),
            'dex_modded'                  => $characterStatBuilder->statMod('dex'),
            'chr_modded'                  => $characterStatBuilder->statMod('chr'),
            'int_modded'                  => $characterStatBuilder->statMod('int'),
            'agi_modded'                  => $characterStatBuilder->statMod('agi'),
            'focus_modded'                => $characterStatBuilder->statMod('focus'),
            'attack'                      => $characterStatBuilder->buildTotalAttack(),
            'health'                      => $characterStatBuilder->buildHealth(),
            'ac'                          => $characterStatBuilder->buildDefence(),
            'extra_action_chance'         => (new ClassAttackValue($character))->buildAttackData(),
            'gold'                        => number_format($character->gold),
            'gold_dust'                   => number_format($character->gold_dust),
            'shards'                      => number_format($character->shards),
            'copper_coins'                => number_format($character->copper_coins),
            'is_dead'                     => $character->is_dead,
            'killed_in_pvp'               => $character->killed_in_pvp,
            'can_craft'                   => $character->can_craft,
            'can_attack'                  => $character->can_attack,
            'can_spin'                    => $character->can_spin,
            'is_mercenary_unlocked'       => $character->is_mercenary_unlocked,
            'can_engage_celestials'       => $character->can_engage_celestials,
            'can_engage_celestials_again_at' => now()->diffInSeconds($character->can_engage_celestials_again_at),
            'can_attack_again_at'         => now()->diffInSeconds($character->can_attack_again_at),
            'can_craft_again_at'          => now()->diffInSeconds($character->can_craft_again_at),
            'can_spin_again_at'           => now()->diffInSeconds($character->can_spin_again_at),
            'is_automation_running'       => $character->currentAutomations()->where('type', AutomationType::EXPLORING)->get()->isNotEmpty(),
            'automation_completed_at'     => $this->getTimeLeftOnAutomation($character),
            'is_silenced'                 => $character->user->is_silenced,
            'can_talk_again_at'           => $character->user->can_talk_again_at,
            'can_move'                    => $character->can_move,
            'can_move_again_at'           => now()->diffInSeconds($character->can_move_again_at),
            'force_name_change'           => $character->force_name_change,
            'is_alchemy_locked'           => $this->isAlchemyLocked($character),
            'can_use_work_bench'          => false,
            'can_access_queen'            => false,
            'can_access_hell_forged'      => false,
            'can_access_purgatory_chains' => false,
            'is_in_timeout'               => !is_null($character->user->timeout_until),
            // 'base_position' => [
            //   'x' => $character->map->character_position_x,
            //   'y' => $character->map->character_position_y,
            //   'game_map_id' => $character->map->game_map_id,
            // ],
        ];
    }

    public function isAlchemyLocked(Character $character): bool {
        return Skill::where('character_id', $character->id)->where('game_skill_id', GameSkill::where('type', SkillTypeValue::ALCHEMY)->first()->id)->first()->is_locked;
    }

    protected function getTimeLeftOnAutomation(Character $character) {
        $automation = $character->currentAutomations()->where('type', AutomationType::EXPLORING)->first();

        if (!is_null($automation)) {
            return now()->diffInSeconds($automation->completed_at);
        }

        return 0;
    }
}
