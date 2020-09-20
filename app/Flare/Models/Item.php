<?php

namespace App\Flare\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Flare\Models\ItemAffix;
use Database\Factories\ItemFactory;

class Item extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'item_suffix_id',
        'item_prefix_id',
        'type',
        'default_position',
        'base_damage',
        'base_ac',
        'base_healing',
        'cost',
        'base_damage_mod',
        'description',
        'base_healing_mod',
        'base_ac_mod',
        'str_mod',
        'dur_mod',
        'dex_mod',
        'chr_mod',
        'int_mod',
        'ac_mod',
        'effect',
        'can_craft',
        'skill_level_required',
        'skill_level_trivial',
        'skill_name',
        'skill_training_bonus',
        'can_craft',
        'skill_level_required',
        'skill_level_trivial',
        'crafting_type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'base_damage'          => 'integer',
        'base_healing'         => 'integer',
        'base_ac'              => 'integer',
        'cost'                 => 'integer',
        'base_damage_mod'      => 'float',
        'base_healing_mod'     => 'float',
        'base_ac_mod'          => 'float',
        'str_mod'              => 'float',
        'dur_mod'              => 'float',
        'dex_mod'              => 'float',
        'chr_mod'              => 'float',
        'int_mod'              => 'float',
        'ac_mod'               => 'float',
        'skill_training_bonus' => 'float',
        'can_craft'            => 'boolean',
        'skill_level_required' => 'integer',
        'skill_level_trivial'  => 'integer',
        'can_craft'            => 'boolean',
        'skill_level_required' => 'integer',
        'skill_level_trivial'  => 'integer',
    ];

    public function itemSuffix() {
        return $this->hasOne(ItemAffix::class, 'id', 'item_suffix_id');
    }

    public function itemPrefix() {
        return $this->hasOne(ItemAffix::class, 'id', 'item_prefix_id');
    }

    public function slot() {
        return $this->belongsTo(InventorySlot::class, 'id', 'item_id');
    }

    public function scopeGetTotalDamage(): float {
        $baseDamage = is_null($this->base_damage) ? 0 : $this->base_damage;
        $damage     = $baseDamage;

        if (!is_null($this->itemPrefix)) {
            $damage += ($baseDamage * $this->itemPrefix->base_damage_mod);
        }

        if (!is_null($this->itemSuffix)) {
            $damage += ($baseDamage * $this->itemSuffix->base_damage_mod);
        }

        return $damage;
    }

    public function scopeGetTotalDefence(): float {
        $baseAc = is_null($this->base_ac) ? 0 : $this->base_ac;
        $ac     = $baseAc;

        if (!is_null($this->itemPrefix)) {
            $ac += ($baseAc * $this->itemPrefix->base_ac_mod);
        }

        if (!is_null($this->itemSuffix)) {
            $ac += ($baseAc * $this->itemSuffix->base_ac_mod);
        }

        return $ac;
    }

    public function scopeGetTotalHealing(): float {
        $baseHealing = is_null($this->base_healing) ? 0 : $this->base_healing;
        $healFor     = $baseHealing;

        if (!is_null($this->itemPrefix)) {
            $healFor += ($baseHealing * $this->itemPrefix->base_heal_mod);
        }

        if (!is_null($this->itemSuffix)) {
            $healFor += ($baseHealing * $this->itemSuffix->base_heal_mod);
        }

        return $healFor;
    }

    public function scopeGetTotalPercentageForStat($qeury, string $stat): float {
        $baseStat = is_null($this->{$stat . '_mod'}) ? 0.0 : $this->{$stat . '_mod'};

        if (!is_null($this->itemPrefix)) {
            $stat      = $this->itemPrefix->{$stat . '_mod'};
            $baseStat += !is_null($stat) ? $stat : 0.0;
        }

        if (!is_null($this->itemSuffix)) {
            $stat      = $this->itemSuffix->{$stat . '_mod'};
            $baseStat += !is_null($stat) ? $stat : 0.0;
        }

        return $baseStat;
    }

    public function scopeGetSkillTrainingBonus($query, string $skillName): float {
        $baseSkillTraining = is_null($this->skill_training_bonus) ? 0.0 : $this->skill_training_bonus;

        if (!is_null($this->itemPrefix)) {
            if ($this->itemPrefix->skill_name === $skillName) {
                $stat               = $this->itemPrefix->skill_training_bonus;
                $baseSkillTraining += !is_null($stat) ? $stat : 0.0;
            }
        }

        if (!is_null($this->itemSuffix)) {
            if ($this->itemSuffix->skill_name === $skillName) {
                $stat               = $this->itemSuffix->skill_training_bonus;
                $baseSkillTraining += !is_null($stat) ? $stat : 0.0;
            }
            
        }

        return $baseSkillTraining;
    }

    protected static function newFactory() {
        return ItemFactory::new();
    }
}
