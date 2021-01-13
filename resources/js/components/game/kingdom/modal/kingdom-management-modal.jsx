import React          from 'react';
import {Modal, Tab, Tabs} from 'react-bootstrap';
import moment from 'moment';
import ReactDatatable from '@ashvin27/react-datatable';
import BuildingManagementModal from './building-management-modal';
import { CountdownCircleTimer } from 'react-countdown-circle-timer';
import QueueModal from './queue-modal';

export default class KingdomManagementModal extends React.Component {

    constructor(props) {
        super(props)

        this.columns = [
            {
                key: "name",
                text: "Name",
                sortable: true
            },
            {
                key: "level",
                text: "Level",
                sortable: true
            },
            {
                key: "current_durability",
                text: "Current Durability",
                sortable: true
            },
            {
                key: "current_defence",
                text: "Current Defence",
                sortable: true
            },
            {
                key: "wood_cost",
                text: "Wood Cost",
                sortable: true
            },
            {
                key: "clay_cost",
                text: "Clay Cost",
                sortable: true
            },
            {
                key: "stone_cost",
                text: "Stone Cost",
                sortable: true
            },
            {
                key: "iron_cost",
                text: "Iron Cost",
                sortable: true
            },
        ];

        this.config = {
            page_size: 5,
            length_menu: [5, 10, 25],
            show_filter: true,
            show_pagination: true,
            pagination: 'advance',
        }

        this.building_queue_config= {
            page_size: 25,
            length_menu: [25],
            show_filter: true,
            show_pagination: true,
            pagination: 'advance',
        }

        this.building_queue_columns = [
            {
                name: "building-name",
                text: "Building Name",
                sortable: true,
                cell: row => <div data-tag="allowRowEvents"><div>{this.fetchBuildingName(row.building_id)}</div></div>,
            },
            {
                key: "to_level",
                text: "Upgrading To Level",
                sortable: true
            },
            {
                name: "completed-at",
                text: "Completed in",
                sortable: true,
                cell: row => <div data-tag="allowRowEvents"><div>{this.fetchTime(row.completed_at)}</div></div>,

            },
        ];

        this.state = {
            openBuildingManagement: false,
            buildingToManage: null,
            openQueueData: false,
            queue: null,
            kingdom: props.kingdom,
        }
    }

    componentDidUpdate() {
        if (!_.isEqual(this.props.kingdom, this.state.kingdom)) {
            this.setState({
                kingdom: this.props.kingdom,
                openQueueData: false,
                queue: null,
            });
        }
    }

    fetchBuildingName(buildingId) {
        return this.state.kingdom.buildings.filter((b) => b.id === buildingId)[0].name
    }

    fetchTime(time) {
      let now    = moment();
      let then   = moment(time);

      let duration = moment.duration(then.diff(now)).asSeconds();

      if (duration > 0) {
        return (
            <>
                <div className="float-left">
                    <CountdownCircleTimer
                        isPlaying={true}
                        duration={duration}
                        initialRemainingTime={duration}
                        colors={[["#004777", 0.33], ["#F7B801", 0.33], ["#A30000"]]}
                        size={40}
                        strokeWidth={2}
                        onComplete={() => [false, 0]}
                    >
                        {({ remainingTime }) => (remainingTime / 60).toFixed(0) }
                    </CountdownCircleTimer>
                </div>
                <div className="float-left mt-2 ml-3">Minutes</div>
            </>
            
        );
      } else {
        return null;
      } 
    }


    adjust(color, amount) {
        return '#' + color.replace(/^#/, '').replace(/../g, color => ('0'+Math.min(255, Math.max(0, parseInt(color, 16) + amount)).toString(16)).substr(-2));
    }

    getTreasury() {
        if (this.state.kingdom.treasury === null) {
            return 0;
        }
        
        return this.state.kingdom.treasury.toLocaleString('en-US', {maximumFractionDigits:0});
    }

    getResourceIncrease(type) {
        const building = this.state.kingdom.buildings.filter((b) => b[type] !== 0);
        
        if (_.isEmpty(building)) {
            return 0;
        }

        return building[0][type];
    }

    getTotalMoraleIncreasePerHour() {
        let currentMoraleIncrease = 0;

        if (this.state.kingdom.current_morale >= 1.0) {
            return currentMoraleIncrease;
        }

        const buildings = this.state.kingdom.buildings;

        buildings.forEach((building) => {
            if (building.current_durability !== 0) {
                currentMoraleIncrease += building.morale_increase;
            }
        });

        return (currentMoraleIncrease * 100).toFixed(2);
    }

    getTotalMoraleDecreasePerHour() {
        let currentMoraleDecrease = 0;

        if (this.state.kingdom.current_morale === 0) {
            return currentMoraleDecrease;
        }

        const buildings = this.state.kingdom.buildings;

        buildings.forEach((building) => {
            if (building.current_durability === 0) {
                currentMoraleDecrease += building.morale_decrease;
            }
        });

        return (currentMoraleDecrease * 100).toFixed(2);
    }

    getCurrentMorale() {
        return (this.state.kingdom.current_morale * 100).toFixed(2);
    }

    rowClickedHandler(event, data, rowIndex) {
        this.setState({
            openBuildingManagement: true,
            building: data,
        });
    }

    closeBuildingManagement() {
        this.setState({
            openBuildingManagement: false,
            building: null,
        });
    }

    queueData(event, data, rowIndex) {
        this.setState({
            openQueueData: true,
            queue: data
        });
    }

    closeQueueData() {
        this.setState({
            openQueueData: false,
            queue: null,
        });
    }

    render() {
        return (
            <Modal
                show={this.props.show}
                onHide={this.props.close}
                dialogClassName="large-modal"
                aria-labelledby="kingdom-management-modal"
                backdrop="static"
            >
                <Modal.Header closeButton style={{backgroundColor: this.adjust(this.state.kingdom.color, 50)}}>
                    <Modal.Title id="kingdom-management-modal" style={{color: '#fff'}}>
                        {this.state.kingdom.name}
                    </Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <Tabs defaultActiveKey="manage" id="uncontrolled-tab-example">
                        <Tab eventKey="manage" title="Manage">
                            <div className="row mt-3">
                                <div className="col-md-3">
                                    <dl>
                                        <dt><strong>Population</strong>:</dt>
                                        <dd>{this.state.kingdom.current_population} / {this.state.kingdom.max_population}</dd>
                                    </dl>
                                </div>
                                <div className="col-md-3">
                                    <dl>
                                        <dt><strong>Morale</strong>:</dt>
                                        <dd>{this.getCurrentMorale()}%</dd>
                                    </dl>
                                </div>
                                <div className="col-md-3">
                                    <dl>
                                        <dt><strong>Treasury</strong>:</dt>
                                        <dd>{this.getTreasury()}</dd>
                                    </dl>
                                </div>
                                <div className="col-md-3">
                                    <dl>
                                        <dt><strong>Location (X/Y)</strong>:</dt>
                                        <dd>{this.state.kingdom.x_position} / {this.state.kingdom.y_position}</dd>
                                    </dl>
                                </div>
                            </div>
                            <hr />
                            <div className="row">
                                <div className="col-md-3">
                                    <dl>
                                        <dt><strong>Wood</strong>:</dt>
                                        <dd>{this.state.kingdom.current_wood} / {this.state.kingdom.max_wood}</dd>
                                    </dl>
                                </div>
                                <div className="col-md-3">
                                    <dl>
                                        <dt><strong>Clay</strong>:</dt>
                                        <dd>{this.state.kingdom.current_clay} / {this.state.kingdom.max_clay}</dd>
                                    </dl>
                                </div>
                                <div className="col-md-3">
                                    <dl>
                                        <dt><strong>Stone</strong>:</dt>
                                        <dd>{this.state.kingdom.current_stone} / {this.state.kingdom.current_stone}</dd>
                                    </dl>
                                </div>
                                <div className="col-md-3">
                                    <dl>
                                        <dt><strong>Iron</strong>:</dt>
                                        <dd>{this.state.kingdom.current_iron} / {this.state.kingdom.max_iron}</dd>
                                    </dl>
                                </div>
                            </div>
                            <hr />
                            <div className="row">
                                <div className="col-md-3">
                                    <dl>
                                        <dt><strong>Wood Increase/hr</strong>:</dt>
                                        <dd>{this.getResourceIncrease('wood_increase')}</dd>
                                    </dl> 
                                </div>
                                <div className="col-md-3">
                                    <dl>
                                        <dt><strong>Clay Increase/hr</strong>:</dt>
                                        <dd>{this.getResourceIncrease('clay_increase')}</dd>
                                    </dl> 
                                </div>
                                <div className="col-md-3">
                                    <dl>
                                        <dt><strong>Stone Increase/hr</strong>:</dt>
                                        <dd>{this.getResourceIncrease('stone_increase')}</dd>
                                    </dl> 
                                </div>
                                <div className="col-md-3">
                                    <dl>
                                    <dt><strong>Iron Increase/hr</strong>:</dt>
                                        <dd>{this.getResourceIncrease('iron_increase')}</dd>
                                    </dl> 
                                </div>
                            </div>
                            <hr />
                            <div className="row">
                                <div className="col-md-4">
                                    <dl>
                                        <dt><strong>Morale Increase/hr</strong>:</dt>
                                        <dd>{this.getTotalMoraleIncreasePerHour()}%</dd>
                                    </dl>
                                </div>
                                <div className="col-md-4">
                                    <dl>
                                        <dt><strong>Morale Decrease/hr</strong>:</dt>
                                        <dd>{this.getTotalMoraleDecreasePerHour()}%</dd>
                                    </dl>
                                </div>
                                <div className="col-md-4">
                                    <dl>
                                    <dt><strong>Population Increase/hr</strong>:</dt>
                                        <dd>{this.getResourceIncrease('population_increase')}</dd>
                                    </dl> 
                                </div>
                            </div>
                            <hr />
                            <div className="row">
                                <div className="col-md-12">
                                    <ReactDatatable
                                        config={this.config}
                                        records={this.state.kingdom.buildings}
                                        columns={this.columns}
                                        onRowClicked={this.rowClickedHandler.bind(this)}        
                                    />
                                </div>
                            </div>

                            
                            { this.state.openBuildingManagement ?
                            <BuildingManagementModal
                                close={this.closeBuildingManagement.bind(this)}
                                show={this.state.openBuildingManagement}
                                building={this.state.building}
                                kingdom={this.state.kingdom}
                                characterId={this.props.characterId}
                                updateKingdomData={this.props.updateKingdomData}
                                queue={this.state.kingdom.building_queue}
                            /> : null }

                            { this.state.openQueueData ? 
                                <QueueModal
                                    close={this.closeQueueData.bind(this)}
                                    show={this.state.openQueueData}
                                    queueData={this.state.queue}
                                    buildings={this.state.kingdom.buildings}
                                />: null
                            }
                        </Tab>
                        <Tab eventKey="building-queue" title="Building Queue">
                            <div className="mt-3">
                                <ReactDatatable
                                    config={this.building_queue_config}
                                    records={this.state.kingdom.building_queue}
                                    columns={this.building_queue_columns}
                                    onRowClicked={this.queueData.bind(this)}        
                                />
                            </div>
                        </Tab>
                    </Tabs>
                </Modal.Body>
            </Modal>
        );
    }
}