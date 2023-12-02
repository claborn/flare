import React, { Fragment } from "react";
import DropDown from "../../../../components/ui/drop-down/drop-down";
import InventoryTable from "./inventory-tabs/inventory-table";
import UsableItemsTable from "./inventory-tabs/usable-items-table";
import InventoryDetails from "../../../../lib/game/character-sheet/types/inventory/inventory-details";
import InventoryActionConfirmationModal from "../modals/inventory-action-confirmation-modal";
import { isEqual } from "lodash";
import SuccessAlert from "../../../../components/ui/alerts/simple-alerts/success-alert";
import InventoryTabSectionProps from "../../../../lib/game/character-sheet/types/tabs/inventory-tab-section-props";
import InventoryTabSectionState from "../../../../lib/game/character-sheet/types/tabs/inventory-tab-section-state";
import clsx from "clsx";
import UsableItemsDetails from "../../../../lib/game/character-sheet/types/inventory/usable-items-details";
import InventoryUseManyItems from "../modals/inventory-use-many-items";
import { GemBagTable } from "./inventory-tabs/gem-bag-table";
import OrangeButton from "../../../../components/ui/buttons/orange-button";
import WarningAlert from "../../../../components/ui/alerts/simple-alerts/warning-alert";

export default class InventoryTabSection extends React.Component<
    InventoryTabSectionProps,
    InventoryTabSectionState
> {
    constructor(props: InventoryTabSectionProps) {
        super(props);

        this.state = {
            table: "inventory",
            data: this.props.inventory,
            usable_items: this.props.usable_items,
            show_destroy_all: false,
            show_disenchant_all: false,
            show_sell_all: false,
            show_use_many: false,
            show_destroy_all_alchemy: false,
            show_equip_best: false,
            success_message: null,
            search_string: "",
        };
    }

    componentDidUpdate() {
        if (
            !isEqual(this.state.data, this.props.inventory) &&
            this.state.search_string.length === 0
        ) {
            this.setState({
                data: this.props.inventory,
            });
        }

        if (
            !isEqual(this.state.usable_items, this.props.usable_items) &&
            this.state.search_string.length === 0
        ) {
            this.setState({
                usable_items: this.props.usable_items,
            });
        }
    }

    setSuccessMessage(message: string) {
        this.setState({
            success_message: message,
        });
    }

    switchTable(type: string) {
        this.setState({
            table: type,
        });
    }

    search(e: React.ChangeEvent<HTMLInputElement>) {
        const value: string = e.target.value;

        if (this.state.table === "Inventory") {
            this.setState({
                data: this.props.inventory
                    .filter((item: InventoryDetails) => {
                        const itemName = item.item_name.toLowerCase();
                        const itemType = item.type.toLowerCase();

                        if (
                            itemName.includes(value.toLowerCase()) ||
                            itemType.includes(value.toLowerCase())
                        ) {
                            return item;
                        }
                    })
                    .filter((item: InventoryDetails) => item),
                search_string: value,
            });
        } else {
            this.setState({
                usable_items: this.props.usable_items
                    .filter((item: UsableItemsDetails) => {
                        const itemName = item.item_name.toLowerCase();

                        if (itemName.includes(value.toLowerCase())) {
                            return item;
                        }
                    })
                    .filter((item: UsableItemsDetails) => item),
                search_string: value,
            });
        }
    }

    handleEquipBest() {
        this.setState({
            show_equip_best: !this.state.show_equip_best,
        })
    }


    manageDisenchantAll() {
        this.setState({
            show_disenchant_all: !this.state.show_disenchant_all,
        });
    }

    manageDestroyAll() {
        this.setState({
            show_destroy_all: !this.state.show_destroy_all,
        });
    }

    manageDestroyAllAlchemy() {
        this.setState({
            show_destroy_all_alchemy: !this.state.show_destroy_all_alchemy,
        });
    }

    manageSellAll() {
        this.setState({
            show_sell_all: !this.state.show_sell_all,
        });
    }

    manageUseManyItems() {
        this.setState({
            show_use_many: !this.state.show_use_many,
        });
    }

    closeSuccess() {
        this.setState({
            success_message: null,
        });
    }

    createActionsDropDown() {
        if (this.state.table === "inventory") {
            return [
                {
                    name: "Destroy All",
                    icon_class: "far fa-trash-alt",
                    on_click: () => this.manageDestroyAll(),
                },
                {
                    name: "Disenchant All",
                    icon_class: "ra ra-fire",
                    on_click: () => this.manageDisenchantAll(),
                },
                {
                    name: "Sell All",
                    icon_class: "far fa-money-bill-alt",
                    on_click: () => this.manageSellAll(),
                },
            ];
        }

        return [
            {
                name: "Use many",
                icon_class: "ra ra-bottle-vapors",
                on_click: () => this.manageUseManyItems(),
            },
            {
                name: "Destroy All",
                icon_class: "far fa-trash-alt",
                on_click: () => this.manageDestroyAllAlchemy(),
            },
        ];
    }

    isDropDownHidden() {
        if (this.state.table === "inventory") {
            return this.state.data.length === 0;
        } else {
            return this.props.usable_items.length === 0;
        }
    }

    updateInventory(inventory: { [key: string]: InventoryDetails[] }) {
        this.setState(
            {
                search_string: "",
            },
            () => {
                this.props.update_inventory(inventory);
            }
        );
    }

    renderTables(): JSX.Element | null {
        switch (this.state.table) {
            case "inventory":
                return (
                    <InventoryTable
                        dark_table={this.props.dark_tables}
                        character_id={this.props.character_id}
                        inventory={this.state.data}
                        is_dead={this.props.is_dead}
                        update_inventory={this.updateInventory.bind(this)}
                        usable_sets={this.props.usable_sets}
                        set_success_message={this.setSuccessMessage.bind(this)}
                        is_automation_running={this.props.is_automation_running}
                        manage_skills={this.props.manage_skills}
                    />
                );
            case "usable-items":
                return (
                    <UsableItemsTable
                        dark_table={this.props.dark_tables}
                        character_id={this.props.character_id}
                        usable_items={this.state.usable_items}
                        is_dead={this.props.is_dead}
                        update_inventory={this.updateInventory.bind(this)}
                        set_success_message={this.setSuccessMessage.bind(this)}
                        is_automation_running={this.props.is_automation_running}
                    />
                );
            case "gems":
                return (
                    <GemBagTable
                        dark_table={this.props.dark_tables}
                        character_id={this.props.character_id}
                        is_dead={this.props.is_dead}
                    />
                );
            default:
                return null;
        }
    }

    render() {
        return (
            <Fragment>
                {this.state.success_message !== null ? (
                    <SuccessAlert
                        close_alert={this.closeSuccess.bind(this)}
                        additional_css={"mt-4 mb-4"}
                    >
                        {this.state.success_message}
                    </SuccessAlert>
                ) : null}

                <div className="flex flex-row flex-wrap items-center">
                    <div>
                        <DropDown
                            menu_items={[
                                {
                                    name: "Inventory",
                                    icon_class: "fas fa-shopping-bag",
                                    on_click: () =>
                                        this.switchTable("inventory"),
                                },
                                {
                                    name: "Usable",
                                    icon_class: "ra ra-bubbling-potion",
                                    on_click: () =>
                                        this.switchTable("usable-items"),
                                },
                                {
                                    name: "Gem Bag",
                                    icon_class: "fas fa-gem",
                                    on_click: () => this.switchTable("gems"),
                                },
                            ]}
                            button_title={"Type"}
                            selected_name={this.state.table}
                            disabled={this.props.is_dead}
                        />
                    </div>
                    <div
                        className={clsx("ml-2", {
                            hidden: this.isDropDownHidden(),
                        })}
                    >
                        <DropDown
                            menu_items={this.createActionsDropDown()}
                            button_title={"Actions"}
                            selected_name={this.state.table}
                            disabled={this.props.is_dead}
                        />
                    </div>
                    <div className={"ml-2"}>
                        <OrangeButton button_label={'Equip Best'} on_click={this.handleEquipBest.bind(this)} />
                    </div>
                    <div className="sm:ml-4 md:ml-0 my-4 md:my-0 md:absolute md:right-[10px]">
                        <input
                            type="text"
                            name="search"
                            className="form-control"
                            onChange={this.search.bind(this)}
                            placeholder={"Search"}
                            value={this.state.search_string}
                        />
                    </div>
                </div>

                {this.renderTables()}

                {this.state.show_destroy_all ? (
                    <InventoryActionConfirmationModal
                        is_open={this.state.show_destroy_all}
                        manage_modal={this.manageDestroyAll.bind(this)}
                        title={"Destroy all"}
                        url={
                            "character/" +
                            this.props.character_id +
                            "/inventory/destroy-all"
                        }
                        update_inventory={this.props.update_inventory}
                        set_success_message={this.setSuccessMessage.bind(this)}
                    >
                        <p>
                            Are you sure you want to do this? This action will
                            destroy all items in your inventory. You cannot undo
                            this action.
                        </p>
                        <p className="mt-2">
                            Make sure you move any items you want to a set or
                            equip the items you want, before destroying all.
                        </p>
                        <p className="mt-2">
                            It is advised that players do not destroy enchanted
                            items (names with *'s) or uniques (green items), but
                            instead sell them on the market or{" "}
                            <a
                                href={"/information/skill-information"}
                                target="_blank"
                            >
                                disenchant{" "}
                                <i className="fas fa-external-link-alt"></i>
                            </a>{" "}
                            them to make{" "}
                            <a href={"/information/currencies"} target="_blank">
                                Gold Dust{" "}
                                <i className="fas fa-external-link-alt"></i>
                            </a>
                            .
                        </p>
                    </InventoryActionConfirmationModal>
                ) : null}

                {this.state.show_destroy_all_alchemy ? (
                    <InventoryActionConfirmationModal
                        is_open={this.state.show_destroy_all_alchemy}
                        manage_modal={this.manageDestroyAllAlchemy.bind(this)}
                        title={"Destroy all Alchemy Items"}
                        url={
                            "character/" +
                            this.props.character_id +
                            "/inventory/destroy-all-alchemy-items"
                        }
                        update_inventory={this.props.update_inventory}
                        set_success_message={this.setSuccessMessage.bind(this)}
                    >
                        <p>
                            Are you sure you want to do this? This action will
                            destroy all (Alchemy) items in your inventory. You
                            cannot undo this action.
                        </p>
                    </InventoryActionConfirmationModal>
                ) : null}

                {this.state.show_disenchant_all ? (
                    <InventoryActionConfirmationModal
                        is_open={this.state.show_disenchant_all}
                        manage_modal={this.manageDisenchantAll.bind(this)}
                        title={"Disenchant all"}
                        url={
                            "character/" +
                            this.props.character_id +
                            "/inventory/disenchant-all"
                        }
                        update_inventory={this.props.update_inventory}
                        set_success_message={this.setSuccessMessage.bind(this)}
                    >
                        <p>
                            Are you sure you want to do this? This action will
                            disenchant all items in your inventory. You cannot
                            undo this action.
                        </p>
                        <p className="mt-2">
                            When you disenchant items you will get some{" "}
                            <a href={"/information/currencies"} target="_blank">
                                Gold Dust{" "}
                                <i className="fas fa-external-link-alt"></i>
                            </a>{" "}
                            and experience towards{" "}
                            <a
                                href={"/information/skill-information"}
                                target="_blank"
                            >
                                Disenchanting{" "}
                                <i className="fas fa-external-link-alt"></i>
                            </a>{" "}
                            and half XP towards Enchanting.
                        </p>
                        <p className="mt-2">
                            Tip for crafters/enchanters: Equip a set that's full
                            enchanting when doing your mass disenchanting,
                            because the XP you get, while only half, can be
                            boosted. For new players, you should be crafting and
                            enchanting and then disenchanting or selling your
                            equipment on the market, if it is not viable for
                            you.
                        </p>
                    </InventoryActionConfirmationModal>
                ) : null}

                {this.state.show_sell_all ? (
                    <InventoryActionConfirmationModal
                        is_open={this.state.show_sell_all}
                        manage_modal={this.manageSellAll.bind(this)}
                        title={"Sell all"}
                        url={
                            "character/" +
                            this.props.character_id +
                            "/inventory/sell-all"
                        }
                        update_inventory={this.props.update_inventory}
                        set_success_message={this.setSuccessMessage.bind(this)}
                    >
                        <p>
                            Are you sure? You are about to sell all items in
                            your inventory (this does not effect Alchemy, Quest
                            items, Sets or Equipped items). This action cannot
                            be undone. Also, trinkets cannot be sold to the
                            shop. They can be listed to the market or destroyed.
                        </p>
                        <p className="mt-2">
                            <strong>Note</strong>: The amount of gold you will
                            get back for items that are enchanted or crafted
                            over the price of two billion gold will never be
                            sold for <strong>more than</strong> two billion
                            gold. Ie, a 36 billion gold item will only sell for
                            two billion gold before taxes.
                        </p>
                        <p className="mt-2">
                            It is highly recommended you use the market place to
                            sell anything beyond shop gear to make your money
                            back.
                        </p>
                    </InventoryActionConfirmationModal>
                ) : null}

                {this.state.show_equip_best ? (
                    <InventoryActionConfirmationModal
                        is_open={this.state.show_equip_best}
                        manage_modal={this.handleEquipBest.bind(this)}
                        title={"Equip best in slot"}
                        url={
                            "character/" +
                            this.props.character_id +
                            "/inventory/equip-best-in-slot"
                        }
                        update_inventory={this.props.update_inventory}
                        set_success_message={this.setSuccessMessage.bind(this)}
                    >
                        <WarningAlert additional_css={'my-4'}>
                            <p className={'mb-4'}>
                                This is a highly experimental feature which might break or give unexpected results
                            </p>
                            <p className={'mb-4'}>
                                The core of this feature is to allow you to have the game automatically equip what it think's is
                                the best equipment. This does not take into account any skills on the item or that you are training and
                                does not take into account your current class in relation to whats might be best for it.
                            </p>
                            <p>
                                If you encounter issues or bugs please, with as much details as you can, post the issues in {" "}
                                <a href='https://discord.gg/hcwdqJUerh' target="_blank">
                                    Discord #bugs <i
                                    className="fas fa-external-link-alt"></i>
                                </a>
                            </p>
                        </WarningAlert>
                        <p>
                            Based on equippable positions, what you may or may not have equipped and following <a href='/information/equipment-sets' target="_blank">
                            Rules of equipment <i
                            className="fas fa-external-link-alt"></i>
                        </a>, we will compare whats in your inventory to whats equipped and replace or equip
                            what's considered better for your character.
                        </p>
                    </InventoryActionConfirmationModal>
                ) : null}

                {this.state.show_use_many &&
                this.state.usable_items.length > 0 ? (
                    <InventoryUseManyItems
                        is_open={this.state.show_use_many}
                        manage_modal={this.manageUseManyItems.bind(this)}
                        items={this.state.usable_items}
                        update_inventory={this.props.update_inventory}
                        character_id={this.props.character_id}
                        set_success_message={this.setSuccessMessage.bind(this)}
                    />
                ) : null}
            </Fragment>
        );
    }
}
