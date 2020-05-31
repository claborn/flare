<?php

use Illuminate\Database\Seeder;
use App\Flare\Models\Item;

class CreateItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Item::insert([
            [
                'name'                 => 'Rusty bloody broken dagger',
                'type'                 => 'weapon',
                'base_damage'          => 3,
                'base_healing'         => null,
                'base_ac'              => null,
                'cost'                 => 10,
                'base_damage_mod'      => null,
                'description'          => null,
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => null,
                'skill_training_bonus' => null,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => true,
                'skill_level_required' => 0,
                'skill_level_trivial'  => 5,
                'crafting_type'        => 'weapon'
            ],
            [
                'name'                 => 'Chapped, ripped leather breast plate',
                'type'                 => 'body',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => 2,
                'cost'                 => 10,
                'base_damage_mod'      => null,
                'description'          => null,
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => null,
                'skill_training_bonus' => null,
                'default_position'     => 'body',
                'effect'               => null,
                'can_craft'            => true,
                'skill_level_required' => 0,
                'skill_level_trivial'  => 5,
                'crafting_type'        => 'armour'
            ],
            [
                'name'                 => 'Steel rimmed wooden shield',
                'type'                 => 'shield',
                'base_healing'         => null,
                'base_damage'          => null,
                'cost'                 => 10,
                'base_ac'              => 2,
                'base_damage_mod'      => null,
                'description'          => null,
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => null,
                'skill_training_bonus' => null,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => true,
                'skill_level_required' => 2,
                'skill_level_trivial'  => 8,
                'crafting_type'        => 'armour'
            ],
            [
                'name'                 => 'Worn out musty old shoes',
                'type'                 => 'feet',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => 1,
                'cost'                 => 10,
                'base_damage_mod'      => null,
                'description'          => null,
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => null,
                'skill_training_bonus' => null,
                'default_position'     => 'feet',
                'effect'               => null,
                'can_craft'            => true,
                'skill_level_required' => 5,
                'skill_level_trivial'  => 12,
                'crafting_type'        => 'armour'
            ],
            [
                'name'                 => 'Bloody leggings',
                'type'                 => 'leggings',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => 2,
                'cost'                 => 10,
                'base_damage_mod'      => null,
                'description'          => null,
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => null,
                'skill_training_bonus' => null,
                'default_position'     => 'legs',
                'effect'               => null,
                'can_craft'            => true,
                'skill_level_required' => 8,
                'skill_level_trivial'  => 20,
                'crafting_type'        => 'armour'
            ],
            [
                'name'                 => 'Old cotton sleeves',
                'type'                 => 'sleeves',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => 1,
                'cost'                 => 10,
                'base_damage_mod'      => null,
                'description'          => null,
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => null,
                'skill_training_bonus' => null,
                'default_position'     => 'sleeves',
                'effect'               => null,
                'can_craft'            => true,
                'skill_level_required' => 10,
                'skill_level_trivial'  => 25,
                'crafting_type'        => 'armour'
            ],
            [
                'name'                 => 'Ruined and burnt wooden mask',
                'type'                 => 'helmet',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => 1,
                'cost'                 => 10,
                'base_damage_mod'      => null,
                'description'          => null,
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => null,
                'skill_training_bonus' => null,
                'default_position'     => 'head',
                'effect'               => null,
                'can_craft'            => true,
                'skill_level_required' => 12,
                'skill_level_trivial'  => 30,
                'crafting_type'        => 'armour'
            ],
            [
                'name'                 => 'Fingerless ripped gloves',
                'type'                 => 'gloves',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => 1,
                'cost'                 => 10,
                'base_damage_mod'      => null,
                'description'          => null,
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => null,
                'skill_training_bonus' => null,
                'default_position'     => 'hands',
                'effect'               => null,
                'can_craft'            => true,
                'skill_level_required' => 15,
                'skill_level_trivial'  => 50,
                'crafting_type'        => 'armour'
            ],
            [
                'name'                 => 'Ice spell',
                'type'                 => 'spell-damage',
                'base_healing'         => null,
                'base_damage'          => 5,
                'base_ac'              => null,
                'cost'                 => 10,
                'base_damage_mod'      => null,
                'description'          => null,
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => null,
                'skill_training_bonus' => null,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => true,
                'skill_level_required' => 0,
                'skill_level_trivial'  => 5,
                'crafting_type'        => 'spell'
            ],
            [
                'name'                 => 'Cure spell',
                'type'                 => 'spell-healing',
                'base_healing'         => 10,
                'base_damage'          => null,
                'base_ac'              => null,
                'cost'                 => 10,
                'base_damage_mod'      => null,
                'description'          => null,
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => null,
                'skill_training_bonus' => null,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => true,
                'skill_level_required' => 0,
                'skill_level_trivial'  => 5,
                'crafting_type'        => 'spell'
            ],
            [
                'name'                 => 'Basic ring of hatred and despair',
                'type'                 => 'ring',
                'base_healing'         => null,
                'base_damage'          => 3,
                'base_ac'              => null,
                'cost'                 => 10,
                'base_damage_mod'      => null,
                'description'          => null,
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => null,
                'skill_training_bonus' => null,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => true,
                'skill_level_required' => 0,
                'skill_level_trivial'  => 5,
                'crafting_type'        => 'ring'
            ],
            [
                'name'                 => 'Virgins Petrified Blood',
                'type'                 => 'artifact',
                'base_healing'         => null,
                'base_damage'          => 25,
                'base_ac'              => null,
                'cost'                 => 1000,
                'base_damage_mod'      => '0.10',
                'description'          => 'Blood from a virgin thats been petrified.',
                'base_healing_mod'     => '0.25',
                'str_mod'              => '0.00',
                'dur_mod'              => '0.50',
                'dex_mod'              => '0.00',
                'chr_mod'              => '0.00',
                'int_mod'              => '0.00',
                'ac_mod'               => null,
                'skill_name'           => null,
                'skill_training_bonus' => null,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => true,
                'skill_level_required' => 0,
                'skill_level_trivial'  => 5,
                'crafting_type'        => 'artifact'
            ],
            [
                'name'                 => 'Tears of a demon',
                'type'                 => 'artifact',
                'base_healing'         => null,
                'base_damage'          => 25,
                'base_ac'              => null,
                'cost'                 => 1000,
                'base_damage_mod'      => '0.10',
                'description'          => 'A tear from a demon is a rare thing indeed.',
                'base_healing_mod'     => '0.25',
                'str_mod'              => '0.01',
                'dur_mod'              => '0.01',
                'dex_mod'              => '0.01',
                'chr_mod'              => '0.50',
                'int_mod'              => '0.00',
                'ac_mod'               => '0.00',
                'skill_name'           => null,
                'skill_training_bonus' => null,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => true,
                'skill_level_required' => 4,
                'skill_level_trivial'  => 10,
                'crafting_type'        => 'artifact'
            ],
            [
                'name'                 => 'Conjuration Bag',
                'type'                 => 'artifact',
                'base_healing'         => null,
                'base_damage'          => 25,
                'base_ac'              => null,
                'cost'                 => 1000,
                'base_damage_mod'      => '0.10',
                'description'          => 'A witch doctors conjuration bag.',
                'base_healing_mod'     => '0.10',
                'str_mod'              => '0.00',
                'dur_mod'              => '0.10',
                'dex_mod'              => '0.00',
                'chr_mod'              => '0.00',
                'int_mod'              => '0.50',
                'ac_mod'               => '0.00',
                'skill_name'           => null,
                'skill_training_bonus' => null,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => true,
                'skill_level_required' => 8,
                'skill_level_trivial'  => 20,
                'crafting_type'        => 'artifact'
            ],
            [
                'name'                 => 'Ancient Soldiers Broken Sword',
                'type'                 => 'artifact',
                'base_healing'         => null,
                'base_damage'          => 25,
                'base_ac'              => 2,
                'cost'                 => 1000,
                'base_damage_mod'      => '0.30',
                'description'          => 'The hilt of a long sword once used by a mighty warrior.',
                'base_healing_mod'     => '0.10',
                'str_mod'              => '0.50',
                'dur_mod'              => '0.10',
                'dex_mod'              => '0.00',
                'chr_mod'              => '0.00',
                'int_mod'              => '0.00',
                'ac_mod'               => '0.20',
                'skill_name'           => null,
                'skill_training_bonus' => null,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => true,
                'skill_level_required' => 10,
                'skill_level_trivial'  => 30,
                'crafting_type'        => 'artifact'
            ],
            [
                'name'                 => 'Dioxes Bow',
                'type'                 => 'artifact',
                'base_healing'         => null,
                'base_damage'          => 25,
                'base_ac'              => null,
                'cost'                 => 1000,
                'base_damage_mod'      => '0.10',
                'description'          => 'A legendary rangers quiver.',
                'base_healing_mod'     => '0.10',
                'str_mod'              => '0.00',
                'dur_mod'              => '0.00',
                'dex_mod'              => '0.50',
                'chr_mod'              => '0.00',
                'int_mod'              => '0.00',
                'ac_mod'               => '0.00',
                'skill_name'           => null,
                'skill_training_bonus' => null,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => true,
                'skill_level_required' => 18,
                'skill_level_trivial'  => 50,
                'crafting_type'        => 'artifact'
            ],
            [
                'name'                 => 'Flask of Fresh Air',
                'type'                 => 'quest',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => null,
                'cost'                 => null,
                'base_damage_mod'      => null,
                'description'          => 'Allows you to walk on water.',
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => null,
                'skill_training_bonus' => null,
                'default_position'     => null,
                'effect'               => 'walk-on-water',
                'can_craft'            => false,
                'skill_level_required' => null,
                'skill_level_trivial'  => null,
                'crafting_type'        => null,
            ],
            [
                'name'                 => 'Weapon Smiths Book',
                'type'                 => 'quest',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => null,
                'cost'                 => null,
                'base_damage_mod'      => null,
                'description'          => 'Gives you 150% Crafting XP when you craft a weapon.',
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => 'Weapon Crafting',
                'skill_training_bonus' => 1.5,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => false,
                'skill_level_required' => null,
                'skill_level_trivial'  => null,
                'crafting_type'        => null,
            ],
            [
                'name'                 => 'Advanced Weapon Smiths Book',
                'type'                 => 'quest',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => null,
                'cost'                 => null,
                'base_damage_mod'      => null,
                'description'          => 'Gives you 250% Crafting XP when you craft a weapon.',
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => 'Weapon Crafting',
                'skill_training_bonus' => 2.5,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => false,
                'skill_level_required' => null,
                'skill_level_trivial'  => null,
                'crafting_type'        => null,
            ],
            [
                'name'                 => 'Masters Weapon Smiths Book',
                'type'                 => 'quest',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => null,
                'cost'                 => null,
                'base_damage_mod'      => null,
                'description'          => 'Gives you 500% Crafting XP when you craft a weapon.',
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => 'Weapon Crafting',
                'skill_training_bonus' => 5.0,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => false,
                'skill_level_required' => null,
                'skill_level_trivial'  => null,
                'crafting_type'        => null,
            ],
            [
                'name'                 => 'Armour Smiths Book',
                'type'                 => 'quest',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => null,
                'cost'                 => null,
                'base_damage_mod'      => null,
                'description'          => 'Gives you 150% Crafting XP when you craft a piece of armour.',
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => 'Armour Crafting',
                'skill_training_bonus' => 1.5,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => false,
                'skill_level_required' => null,
                'skill_level_trivial'  => null,
                'crafting_type'        => null,
            ],
            [
                'name'                 => 'Advanced Armour Smiths Book',
                'type'                 => 'quest',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => null,
                'cost'                 => null,
                'base_damage_mod'      => null,
                'description'          => 'Gives you 250% Crafting XP when you craft a piece of armour.',
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => 'Armour Crafting',
                'skill_training_bonus' => 2.5,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => false,
                'skill_level_required' => null,
                'skill_level_trivial'  => null,
                'crafting_type'        => null,
            ],
            [
                'name'                 => 'Masters Armour Smiths Book',
                'type'                 => 'quest',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => null,
                'cost'                 => null,
                'base_damage_mod'      => null,
                'description'          => 'Gives you 500% Crafting XP when you craft a piece of armour.',
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => 'Armour Crafting',
                'skill_training_bonus' => 5.0,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => false,
                'skill_level_required' => null,
                'skill_level_trivial'  => null,
                'crafting_type'        => null,
            ],
            [
                'name'                 => 'Ring Smiths Book',
                'type'                 => 'quest',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => null,
                'cost'                 => null,
                'base_damage_mod'      => null,
                'description'          => 'Gives you 150% Crafting XP when you craft a ring.',
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => 'Ring Crafting',
                'skill_training_bonus' => 1.5,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => false,
                'skill_level_required' => null,
                'skill_level_trivial'  => null,
                'crafting_type'        => null,
            ],
            [
                'name'                 => 'Advanced Ring Smiths Book',
                'type'                 => 'quest',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => null,
                'cost'                 => null,
                'base_damage_mod'      => null,
                'description'          => 'Gives you 250% Crafting XP when you craft a ring.',
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => 'Ring Crafting',
                'skill_training_bonus' => 2.5,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => false,
                'skill_level_required' => null,
                'skill_level_trivial'  => null,
                'crafting_type'        => null,
            ],
            [
                'name'                 => 'Masters Ring Smiths Book',
                'type'                 => 'quest',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => null,
                'cost'                 => null,
                'base_damage_mod'      => null,
                'description'          => 'Gives you 500% Crafting XP when you craft a ring.',
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => 'Spell Crafting',
                'skill_training_bonus' => 5.0,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => false,
                'skill_level_required' => null,
                'skill_level_trivial'  => null,
                'crafting_type'        => null,
            ],
            [
                'name'                 => 'Spell Weaving Book',
                'type'                 => 'quest',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => null,
                'cost'                 => null,
                'base_damage_mod'      => null,
                'description'          => 'Gives you 150% Crafting XP when you craft a spell.',
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => 'Spell Crafting',
                'skill_training_bonus' => 1.5,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => false,
                'skill_level_required' => null,
                'skill_level_trivial'  => null,
                'crafting_type'        => null,
            ],
            [
                'name'                 => 'Advanced Spell Weaving Book',
                'type'                 => 'quest',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => null,
                'cost'                 => null,
                'base_damage_mod'      => null,
                'description'          => 'Gives you 250% Crafting XP when you craft a spell.',
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => 'Spell Crafting',
                'skill_training_bonus' => 2.5,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => false,
                'skill_level_required' => null,
                'skill_level_trivial'  => null,
                'crafting_type'        => null,
            ],
            [
                'name'                 => 'Masters Spell Weaving Book',
                'type'                 => 'quest',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => null,
                'cost'                 => null,
                'base_damage_mod'      => null,
                'description'          => 'Gives you 500% Crafting XP when you craft a spell.',
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => 'Spell Crafting',
                'skill_training_bonus' => 5.0,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => false,
                'skill_level_required' => null,
                'skill_level_trivial'  => null,
                'crafting_type'        => null,
            ],
            [
                'name'                 => 'Artifact Crafting Book',
                'type'                 => 'quest',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => null,
                'cost'                 => null,
                'base_damage_mod'      => null,
                'description'          => 'Gives you 150% Crafting XP when you craft an artifact.',
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => 'Artifact Crafting',
                'skill_training_bonus' => 1.5,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => false,
                'skill_level_required' => null,
                'skill_level_trivial'  => null,
                'crafting_type'        => null,
            ],
            [
                'name'                 => 'Advanced Artifact Crafting Book',
                'type'                 => 'quest',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => null,
                'cost'                 => null,
                'base_damage_mod'      => null,
                'description'          => 'Gives you 250% Crafting XP when you craft an artifact.',
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => 'Artifact Crafting',
                'skill_training_bonus' => 2.5,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => false,
                'skill_level_required' => null,
                'skill_level_trivial'  => null,
                'crafting_type'        => null,
            ],
            [
                'name'                 => 'Masters Artifact Crafting Book',
                'type'                 => 'quest',
                'base_healing'         => null,
                'base_damage'          => null,
                'base_ac'              => null,
                'cost'                 => null,
                'base_damage_mod'      => null,
                'description'          => 'Gives you 500% Crafting XP when you craft an artifact.',
                'base_healing_mod'     => null,
                'str_mod'              => null,
                'dur_mod'              => null,
                'dex_mod'              => null,
                'chr_mod'              => null,
                'int_mod'              => null,
                'ac_mod'               => null,
                'skill_name'           => 'Artifact Crafting',
                'skill_training_bonus' => 5.0,
                'default_position'     => null,
                'effect'               => null,
                'can_craft'            => false,
                'skill_level_required' => null,
                'skill_level_trivial'  => null,
                'crafting_type'        => null,
            ],
        ]);
    }
}
