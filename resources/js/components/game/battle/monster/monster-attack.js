import CanEntranceEnemy from "../attack/attack-types/enchantments/can-entrance-enemy";
import CanHitCheck from "../attack/attack-types/can-hit-check";
import AttackType from "../attack/attack-type";
import UseItems from "../attack/attack-types/use-items";
import {random} from "lodash";

export default class MonsterAttack {

  constructor(attacker, defender, currentCharacterHealth, currentMonsterHealth) {
    this.attacker               = attacker;
    this.defender               = defender;
    this.currentCharacterHealth = currentCharacterHealth;
    this.currentMonsterHealth   = currentMonsterHealth;
    this.battleMessages         = [];
  }

  doAttack(previousAttackType) {
    const monster = this.attacker.getMonster();
    const damage  = this.attacker.attack();

    if (this.entrancesEnemy(monster, this.defender)) {
      this.useItems(monster);

      this.currentCharacterHealth = this.currentCharacterHealth - damage;

      this.addMessage(monster.name + ' hit for: ' + this.formatNumber(damage));

      return this.setState()
    } else {
      if (this.canHit(monster, this.defender)) {

        if (this.isBlocked(previousAttackType, this.defender, damage)) {
          this.addMessage('The enemies attack was blocked!');

          this.useItems(monster);

          return this.setState()
        }

        this.useItems(monster);

        this.currentCharacterHealth = this.currentCharacterHealth - damage;

        this.addMessage(monster.name + ' hit for: ' + this.formatNumber(damage));

        return this.setState()

      } else {
        this.addMessage(monster.name + ' missed!');

        this.useItems(monster);

        return this.setState();
      }
    }
  }

  setState() {
    return {
      characterCurrentHealth: this.currentCharacterHealth,
      monsterCurrentHealth: this.currentMonsterHealth,
      battleMessages: this.battleMessages,
    }
  }

  entrancesEnemy(attacker, defender) {
    const canEntrance = new CanEntranceEnemy();

    if (canEntrance.monsterCanEntrance(attacker, defender)) {
      this.battleMessages = [...this.battleMessages, ...canEntrance.getBattleMessages()]

      return true;
    }

    this.battleMessages = [...this.battleMessages, ...canEntrance.getBattleMessages()]

    return false;
  }

  canHit(attacker, defender) {
    const canHit = new CanHitCheck()

    if (canHit.canHit(attacker, defender, this.battleMessages)) {
      return true;
    }

    return false;
  }

  isBlocked(attackType, defender, damage) {
    if (AttackType.DEFEND === attackType) {
      const defenderAC = defender.attack_types[AttackType.DEFEND].defence;

      return damage < defenderAC;
    }

    return damage < defender.ac;
  }

  useItems(attacker) {
    const useItems = new UseItems(this.defender, this.currentMonsterHealth, this.currentCharacterHealth);

    useItems.useArtifacts(attacker, this.defender, 'monster');

    this.battleMessages = [...this.battleMessages, ...useItems.getBattleMessage()]

    this.currentCharacterHealth = useItems.getCharacterCurrentHealth();

    this.fireOffAffixes(attacker);
    this.fireOffSpells(attacker);
    this.fireOffHealing(attacker);
  }

  fireOffAffixes(attacker) {
    if (attacker.max_affix_damage > 0) {
      const damage = random(1, attacker.max_affix_damage);
      this.currentCharacterHealth = this.currentCharacterHealth - damage;

      this.addMessage(attacker.name + '\'s enchantments glow, lashing out for: ' + this.formatNumber(damage));
    }
  }

  fireOffSpells(attacker) {
    if (attacker.spell_damage > 0) {
      const evasionChance = 100 - (100 * this.defender.spell_evasion)
      const roll          = random(1, 100);

      if (roll < evasionChance) {
        this.currentCharacterHealth = this.currentCharacterHealth - attacker.spell_damage;

        this.addMessage(attacker.name + '\'s spells burst toward you doing: ' + this.formatNumber(attacker.spell_damage));
      } else {
        this.addMessage(attacker.name + '\'s spells fizzle and fail before your eyes. The enemy looks confused!');
      }
    }
  }

  fireOffHealing(attacker) {
    if (attacker.max_healing > 0) {
      const healFor = Math.ceil(attacker.dur * attacker.max_healing);

      this.currentMonsterHealth = this.currentMonsterHealth + healFor;

      this.addMessage(attacker.name + '\'s healing spells wash over them for: ' + this.formatNumber(healFor));
    }
  }

  addMessage(message) {
    this.battleMessages.push({message: message});
  }

  formatNumber(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

}