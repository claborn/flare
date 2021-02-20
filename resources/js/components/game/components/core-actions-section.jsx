import React from 'react';
import {Row, Col} from 'react-bootstrap';
import BattleAction from '../battle/battle-action';
import AdditionalCoreActionsDropDown from './additional-core-actions-dropdown';
import Card from './templates/card';
import ContentLoader from 'react-content-loader';

export default class CoreActionsSection extends React.Component {

  constructor(props) {
    super(props);

    this.state = {
      isDead: false,
      isAdventuring: false,
      changeCraftingType: false,
      showCrafting: false,
      characterId: null,
      isLoading: true,
      character: null,
      monsters: null,
      canCraft: true,
    };
  }

  componentDidMount() {
    axios.get('/api/actions', {
      params: {
        user_id: this.props.userId
      }
    }).then((result) => {
      this.setState({
        character: result.data.character,
        monsters: result.data.monsters,
        isLoading: false,
        isDead: result.data.character.is_dead,
      }, () => {
        this.props.setCharacterId(this.state.character.id);
      });
    });
  }

  characterIsDead(isDead) {
    this.setState({
      isDead: isDead,
    });
  }

  characterIsAdventuring(adventuring) {
    this.setState({
      isAdventuring: adventuring,
    });
  }

  changeCraftingType(change) {
    this.setState({
      changeCraftingType: change,
    });
  }

  updateShowCrafting(show) {
    this.setState({
      showCrafting: show,
    });
  }

  updateShowEnchanting(show) {
    this.setState({
      showEnchanting: show,
    });
  }

  updateCanCraft(can) {
    this.setState({
      canCraft: can,
    });
  }

  render() {

    if (this.state.isLoading) {
      return (
        <Card>
          <ContentLoader viewBox="0 0 380 30">
            {/* Only SVG shapes */}
            <rect x="0" y="0" rx="4" ry="4" width="250" height="5" />
            <rect x="0" y="8" rx="3" ry="3" width="250" height="5" />
            <rect x="0" y="16" rx="4" ry="4" width="250" height="5" />
          </ContentLoader>
        </Card>
      );
    }

    return (
      <Card>
        <Row>
          <Col xs={12} sm={12} md={12} lg={12} xl={2}>
            <AdditionalCoreActionsDropDown
              isDead={this.state.isDead}
              isAdventuring={this.state.isAdventuring}
              changeCraftingType={this.changeCraftingType.bind(this)}
              updateShowCrafting={this.updateShowCrafting.bind(this)}
              updateShowEnchanting={this.updateShowEnchanting.bind(this)}
              canCraft={this.state.canCraft}
            />
            {
              this.props.kingdomData.can_attack ?
              <button className="btn btn-success btn-sm mb-2">Attack Kingdom</button> 
              : null
            }

            {
              this.props.kingdomData.can_settle ?
              <button disabled={this.state.isDead || this.state.isAdventuring} onClick={this.props.openKingdomModal} className="btn btn-success btn-sm mb-2">Settle Kingdom</button> 
              : null
            }

            {
              this.props.kingdomData.is_mine ?
              <button disabled={this.state.isDead || this.state.isAdventuring} onClick={() => this.props.openKingdomManagement(true)} className="btn btn-success btn-sm mb-2">Manage Kingdom</button> 
              : null
            }
          </Col>
          <Col xs={12} sm={12} md={12} lg={12} xl={10}>
            <BattleAction
              userId={this.props.userId}
              character={this.state.character}
              monsters={this.state.monsters}
              showCrafting={this.state.showCrafting}
              showEnchanting={this.state.showEnchanting}
              isDead={this.state.isDead}
              shouldChangeCraftingType={this.state.changeCraftingType}
              isCharacterDead={this.characterIsDead.bind(this)}
              isCharacterAdventuring={this.characterIsAdventuring.bind(this)}
              changeCraftingType={this.changeCraftingType.bind(this)}
              updateCanCraft={this.updateCanCraft.bind(this)}
              canAttack={this.props.canAttack}
            />
          </Col>
        </Row>
      </Card>
    );
  }
}