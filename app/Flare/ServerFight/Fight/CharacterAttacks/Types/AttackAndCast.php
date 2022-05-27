<?php

namespace App\Flare\ServerFight\Fight\CharacterAttacks\Types;

use App\Flare\Builders\Character\CharacterCacheData;
use App\Flare\Models\Character;
use App\Flare\ServerFight\BattleBase;
use App\Flare\ServerFight\Fight\Affixes;
use App\Flare\ServerFight\Fight\CanHit;
use App\Flare\ServerFight\Fight\Entrance;
use App\Flare\ServerFight\Monster\ServerMonster;

class AttackAndCast extends BattleBase
{

    private array $attackData;

    private bool $isVoided;

    private Entrance $entrance;

    private CanHit $canHit;

    private Affixes $affixes;

    private WeaponType $weaponType;

    private CastType $castType;

    public function __construct(CharacterCacheData $characterCacheData, Entrance $entrance, CanHit $canHit, Affixes $affixes, WeaponType $weaponType, CastType $castType)
    {
        parent::__construct($characterCacheData);

        $this->entrance           = $entrance;
        $this->canHit             = $canHit;
        $this->affixes            = $affixes;
        $this->weaponType         = $weaponType;
        $this->castType           = $castType;
    }

    public function setCharacterAttackData(Character $character, bool $isVoided): AttackAndCast
    {

        $this->attackData = $this->characterCacheData->getDataFromAttackCache($character, $isVoided ? 'voided_attack_and_cast' : 'attack_and_cast');
        $this->isVoided = $isVoided;

        return $this;
    }

    public function resetMessages()
    {
        $this->clearMessages();
        $this->entrance->clearMessages();
    }

    public function handleAttack(Character $character, ServerMonster $monster) {
        $this->entrance->playerEntrance($character, $monster, $this->attackData);

        $this->mergeMessages($this->entrance->getMessages());

        if ($this->entrance->isEnemyEntranced()) {
            $this->handleWeaponAttack($character, $monster);
            $this->handleCastAttack($character, $monster);
            $this->secondaryAttack($character, $monster);

            return $this;
        }

        if ($this->canHit->canPlayerAutoHit($character)) {
            $this->handleWeaponAttack($character, $monster);
            $this->handleCastAttack($character, $monster);
            $this->secondaryAttack($character, $monster);

            return $this;
        }

        $this->weaponAttack($character, $monster);
        $this->castAttack($character, $monster);

        return $this;
    }

    protected function weaponAttack(Character $character, ServerMonster $monster) {
        if ($this->canHit->canPlayerHitMonster($character, $monster, $this->isVoided)) {

            $weaponDamage = $this->attackData['weapon_damage'];

            if ($monster->getMonsterStat('ac') > $weaponDamage) {
                $this->addMessage('Your weapon was blocked!', 'enemy-action');
            } else {
                $this->handleWeaponAttack($character, $monster);

                $this->secondaryAttack($character, $monster);
            }
        } else {
            $this->addMessage('Your attack missed!', 'enemy-action');

            $this->secondaryAttack($character, $monster);
        }
    }

    protected function castAttack(Character $character, ServerMonster $monster) {
        if ($this->canHit->canPlayerCastSpell($character, $monster, $this->isVoided)) {

            $spellDamage = $this->attackData['spell_damage'];

            if ($monster->getMonsterStat('ac') > $spellDamage) {
                $this->addMessage('Your weapon was blocked!', 'enemy-action');
            } else {
                $this->handleCastAttack($character, $monster);

                $this->secondaryAttack($character, $monster);
            }
        } else {
            $this->addMessage('Your attack missed!', 'enemy-action');

            $this->secondaryAttack($character, $monster);
        }
    }

    protected function secondaryAttack(Character $character, ServerMonster $monster) {
        if (!$this->isVoided) {
            $this->affixLifeStealingDamage($character, $monster);
            $this->affixDamage($character, $monster);
            $this->ringDamage();
        } else {
            $this->addMessage('You are voided, none of your rings or enchantments fire ...', 'enemy-action');
        }
    }

    protected function affixDamage(Character $character, ServerMonster $monster) {
        $damage = $this->affixes->getCharacterAffixDamage($character, $monster, $this->attackData);

        if ($damage > 0) {
            $this->monsterHealth -= $damage;
        }

        $this->mergeMessages($this->affixes->getMessages());

        $this->affixes->clearMessages();
    }

    protected function affixLifeStealingDamage(Character $character, ServerMonster $monster) {
        if ($this->monsterHealth <= 0) {
            return;
        }

        $lifeStealing = $this->affixes->getAffixLifeSteal($character, $monster, $this->attackData);

        $damage = $monster->getHealth() * $lifeStealing;

        if ($damage > 0) {
            $this->monsterHealth   -= $damage;
            $this->characterHealth += $damage;

            $maxCharacterHealth = $this->characterCacheData->getCachedCharacterData($character, 'health');

            if ($this->characterHealth >= $maxCharacterHealth) {
                $this->characterHealth = $maxCharacterHealth;
            }
        }

        $this->mergeMessages($this->affixes->getMessages());

        $this->affixes->clearMessages();
    }

    protected function ringDamage() {
        $ringDamage = $this->attackData['ring_damage'];

        if ($ringDamage > 0) {
            $this->monsterHealth -= ($ringDamage - $ringDamage * $this->attackData['damage_deduction']);

            $this->addMessage('Your rings hit for: ' . number_format($ringDamage), 'player-action');
        }
    }

    protected function handleWeaponAttack(Character $character, ServerMonster $monster) {
        $weaponDamage = $this->attackData['weapon_damage'];

        $this->weaponType->setMonsterHealth($this->monsterHealth);
        $this->weaponType->setCharacterHealth($this->characterHealth);
        $this->weaponType->setCharacterAttackData($character, $this->isVoided);
        $this->weaponType->weaponDamage($character, $monster->getName(), $weaponDamage);

        $this->mergeMessages($this->weaponType->getMessages());

        $this->characterHealth = $this->weaponType->getCharacterHealth();
        $this->monsterHealth   = $this->weaponType->getMonsterHealth();

        $this->weaponType->resetMessages();
    }

    protected function handleCastAttack(Character $character, ServerMonster $monster) {
        $spellDamage = $this->attackData['spell_damage'];

        $this->castType->setMonsterHealth($this->monsterHealth);
        $this->castType->setCharacterHealth($this->characterHealth);
        $this->castType->setCharacterAttackData($character, $this->isVoided);
        $this->castType->spellDamage($character, $monster, $spellDamage, $this->entrance->isEnemyEntranced());

        $this->mergeMessages($this->castType->getMessages());

        $this->characterHealth = $this->castType->getCharacterHealth();
        $this->monsterHealth   = $this->castType->getMonsterHealth();

        $this->castType->resetMessages();
    }
}