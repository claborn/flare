<?php

namespace App\Flare\ServerFight\Fight\CharacterAttacks;

use App\Flare\Models\Character;
use App\Flare\ServerFight\Fight\CharacterAttacks\Types\AttackAndCast;
use App\Flare\ServerFight\Fight\CharacterAttacks\Types\CastAndAttack;
use App\Flare\ServerFight\Fight\CharacterAttacks\Types\CastType;
use App\Flare\ServerFight\Fight\CharacterAttacks\Types\Defend;
use App\Flare\ServerFight\Fight\CharacterAttacks\Types\WeaponType;
use App\Flare\ServerFight\Monster\ServerMonster;

class CharacterAttack {

    private WeaponType $weaponType;

    private CastType $castType;

    private AttackAndCast $attackAndCast;

    private CastAndAttack $castAndAttack;

    private Defend $defend;

    private mixed $type;

    public function __construct(WeaponType $weaponType, CastType $castType, AttackAndCast $attackAndCast, CastAndAttack $castAndAttack, Defend $defend) {
        $this->weaponType    = $weaponType;
        $this->castType      = $castType;
        $this->attackAndCast = $attackAndCast;
        $this->castAndAttack = $castAndAttack;
        $this->defend        = $defend;
    }

    public function attack(Character $character, ServerMonster $monster, bool $isPlayerVoided, int $characterHealth, int $monsterHealth): CharacterAttack {
        $this->weaponType->setCharacterHealth($characterHealth)
                         ->setMonsterHealth($monsterHealth)
                         ->setCharacterAttackData($character, $isPlayerVoided)
                         ->doWeaponAttack($character, $monster);

        $this->type = $this->weaponType;

        return $this;
    }

    public function cast(Character $character, ServerMonster $monster, bool $isPlayerVoided, int $characterHealth, int $monsterHealth): CharacterAttack {
        $this->castType->setCharacterHealth($characterHealth)
                         ->setMonsterHealth($monsterHealth)
                         ->setCharacterAttackData($character, $isPlayerVoided)
                         ->castAttack($character, $monster);

        $this->type = $this->castType;

        return $this;
    }

    public function attackAndCast(Character $character, ServerMonster $monster, bool $isPlayerVoided, int $characterHealth, int $monsterHealth): CharacterAttack {
        $this->attackAndCast->setCharacterHealth($characterHealth)
                            ->setMonsterHealth($monsterHealth)
                            ->setCharacterAttackData($character, $isPlayerVoided)
                            ->handleAttack($character, $monster);

        $this->type = $this->attackAndCast;

        return $this;
    }

    public function castAndAttack(Character $character, ServerMonster $monster, bool $isPlayerVoided, int $characterHealth, int $monsterHealth): CharacterAttack {
        $this->castAndAttack->setCharacterHealth($characterHealth)
                            ->setMonsterHealth($monsterHealth)
                            ->setCharacterAttackData($character, $isPlayerVoided)
                            ->handleAttack($character, $monster);

        $this->type = $this->castAndAttack;

        return $this;
    }

    public function defend(Character $character, ServerMonster $monster, bool $isPlayerVoided, int $characterHealth, int $monsterHealth): CharacterAttack {
        $this->defend->setCharacterHealth($characterHealth)
                     ->setMonsterHealth($monsterHealth)
                     ->setCharacterAttackData($character, $isPlayerVoided)
                     ->defend($character, $monster);

        $this->type = $this->defend;

        return $this;
    }

    public function getMessages() {
        return $this->type->getMessages();
    }

    public function resetMessages() {
        $this->type->resetMessages();
    }

    public function getCharacterHealth() {
        return $this->type->getCharacterHealth();
    }

    public function getMonsterHealth() {
        return $this->type->getMonsterHealth();
    }
}
