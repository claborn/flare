<?php

namespace App\Flare\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\ItemAffixFactory;
use App\Flare\Models\Traits\WithSearch;

class ItemAffix extends Model
{
    use HasFactory, WithSearch;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'base_damage_mod',
        'base_ac_mod',
        'type',
        'description',
        'base_healing_mod',
        'str_mod',
        'dur_mod',
        'dex_mod',
        'chr_mod',
        'int_mod',
        'cost',
        'skill_name',
        'skill_training_bonus',
        'int_required',
        'skill_level_required',
        'skill_level_trivial',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'base_damage_mod'      => 'float',
        'base_healing_mod'     => 'float',
        'str_mod'              => 'float',
        'dur_mod'              => 'float',
        'dex_mod'              => 'float',
        'chr_mod'              => 'float',
        'int_mod'              => 'float',
        'skill_training_bonus' => 'float',
        'cost'                 => 'integer',
        'int_required'         => 'integer',
        'skill_level_required' => 'integer',
        'skill_level_trivial'  => 'integer',
    ];

    protected static function newFactory() {
        return ItemAffixFactory::new();
    }
}
