<?php

namespace App\Flare\Models;

use Illuminate\Database\Eloquent\Model;

class GuideQuest extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'intro_text',
        'instructions',
        'required_level',
        'required_skill',
        'required_skill_level',
        'required_faction_id',
        'required_faction_level',
        'required_game_map_id',
        'required_quest_id',
        'required_quest_item_id',
        'required_kingdoms',
        'required_kingdom_level',
        'required_kingdom_units',
        'required_passive_skill',
        'required_passive_level',
        'required_gold',
        'required_gold_dust',
        'required_shards',
        'required_stats',
        'required_str',
        'required_dex',
        'required_int',
        'required_dur',
        'required_chr',
        'required_agi',
        'required_focus',
        'faction_points_per_kill',
        'gold_dust_reward',
        'shards_reward',
        'gold_reward',
        'xp_reward'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'required_level'          => 'integer',
        'required_skill'          => 'integer',
        'required_skill_level'    => 'integer',
        'required_faction_id'     => 'integer',
        'required_faction_level'  => 'integer',
        'required_game_map_id'    => 'integer',
        'required_quest_id'       => 'integer',
        'required_quest_item_id'  => 'integer',
        'required_kingdoms'       => 'integer',
        'required_kingdom_level'  => 'integer',
        'required_kingdom_units'  => 'integer',
        'required_passive_skill'  => 'integer',
        'required_passive_level'  => 'integer',
        'required_stats'          => 'integer',
        'required_str'            => 'integer',
        'required_dex'            => 'integer',
        'required_int'            => 'integer',
        'required_dur'            => 'integer',
        'required_chr'            => 'integer',
        'required_agi'            => 'integer',
        'required_focus'          => 'integer',
        'required_gold'           => 'integer',
        'required_gold_dust'      => 'integer',
        'required_shards'         => 'integer',
        'gold_reward'             => 'integer',
        'gold_dust_reward'        => 'integer',
        'shards_reward'           => 'integer',
        'faction_points_per_kill' => 'integer',
        'xp_reward'               => 'integer', 
    ];

    protected $appends = [
        'skill_name',
        'faction_name',
        'game_map_name',
        'quest_name',
        'quest_item_name',
        'passive_name',
    ];

    public function getSkillNameAttribute() {
        $skill = GameSkill::find($this->required_skill);

        if (!is_null($skill)) {
            return $skill->name;
        }

        return null;
    }

    public function getQuestNameAttribute() {
        $quest = Quest::find($this->required_quest_id);

        if (!is_null($quest)) {
            return $quest->name;
        }

        return null;
    }

    public function getPassiveNameAttribute() {
        $passive = PassiveSkill::find($this->required_passive_skill);

        if (!is_null($passive)) {
            return $passive->name;
        }

        return null;
    }

    public function getQuestItemNameAttribute() {
        $questItem = Item::where('type', 'quest')->where('id', $this->required_quest_item_id)->first();

        if (!is_null($questItem)) {
            return $questItem->affix_name;
        }

        return null;
    }

    public function getFactionNameAttribute() {
        return $this->getGameMapNameAttribute();
    }

    public function getGameMapNameAttribute() {
        $gameMap = GameMap::find($this->required_faction_id);

        if (!is_null($gameMap)) {
            return $gameMap->name;
        }

        return null;
    }
}
