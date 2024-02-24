import React from "react";
import {formatNumber} from "../../../lib/game/format-number";
import {ItemType} from "../../../sections/items/enums/item-type";
import PrimaryLinkButton from "../../../components/ui/buttons/primary-link-button";
import ItemDefinition from "../../../sections/items/deffinitions/item-definition";
import PrimaryButton from "../../../components/ui/buttons/primary-button";
import SuccessButton from "../../../components/ui/buttons/success-button";
import {shopServiceContainer} from "../../container/shop-container";
import ShopAjax, {SHOP_ACTIONS} from "../../ajax/shop-ajax";
import Shop from "../../shop";

type OnClick = (itemId: number) => void;
type BuyMany = (item: ItemDefinition) => void;

export default class ShopTableColumns {

    private WEAPON_TYPES = [
        ItemType.WEAPON,
        ItemType.BOW,
        ItemType.FAN,
        ItemType.GUN,
        ItemType.HAMMER,
        ItemType.STAVE,
    ];

    private ARMOUR_TYPES = [
        ItemType.BODY,
        ItemType.BOOTS,
        ItemType.GLOVES,
        ItemType.HELMET,
        ItemType.LEGGINGS,
        ItemType.SLEEVES,
        ItemType.SHIELD,
    ];

    private ajax: ShopAjax;

    private component?: Shop;

    constructor() {
        this.ajax = shopServiceContainer().fetch(ShopAjax);
    }

    setComponent(component: Shop): ShopTableColumns {
        this.component = component;

        return this;
    }

    public buildColumns(onClick: OnClick, viewBuyMany: BuyMany, itemType?: ItemType) {


        let shopColumns: any[] = [
            {
                name: 'Name',
                selector: (row: ItemDefinition) => row.name,
                cell: (row: any) => <span>
                    <PrimaryLinkButton button_label={row.name} on_click={() => onClick(row.id)} additional_css={'text-gray-600 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-400'} />
                </span>
            },
            {
                name: 'Type',
                selector: (row: ItemDefinition) => row.type,
                sortable: true,
            },
        ];

        if (typeof itemType === 'undefined') {
            shopColumns = [...shopColumns, ...this.getWeaponColumns(), ...this.getArmourColumns()];
        } else {

            const isWeaponType = this.WEAPON_TYPES.filter((weaponType: ItemType) => {
                return weaponType === itemType
            }).length > 0;

            const isArmorType = this.ARMOUR_TYPES.filter((armorType: ItemType) => {
                return armorType === itemType
            }).length > 0;

            if (isWeaponType) {
                shopColumns = [...shopColumns, ...this.getWeaponColumns()];
            }

            if (isArmorType) {
                shopColumns = [...shopColumns, ...this.getArmourColumns()];
            }
        }

        shopColumns.push({
            name: 'Cost',
            selector: (row: ItemDefinition) => row.cost,
            sortable: true,
            format: (row: any) => formatNumber(row.cost)
        })

        shopColumns = [
            ...shopColumns,
            {
                name: 'Actions',
                selector: (row: ItemDefinition) => row.name,
                cell: (row: ItemDefinition) => <div className={'my-2'}>
                    <div className="w-full mb-2">
                        <PrimaryButton button_label={'Buy'} on_click={() => this.buyItem(row)} additional_css={'w-full'} />
                    </div>
                    <div className="w-full mb-2">
                        <PrimaryButton button_label={'Buy and compare'} on_click={() => {}} additional_css={'w-full'} />
                    </div>
                    <div className="w-full">
                        <SuccessButton button_label={'Buy Multiple'} on_click={viewBuyMany} additional_css={'w-full'} />
                    </div>
                </div>
            },
        ]

        return shopColumns;
    }

    protected getWeaponColumns() {
        return [
            {
                name: 'Attack',
                selector: (row: { base_damage: number; }) => row.base_damage,
                sortable: true,
                format: (row: any) => formatNumber(row.base_damage)
            },
        ];
    }

    protected getArmourColumns() {
        return [
            {
                name: 'Attack',
                selector: (row: { base_ac: number; }) => row.base_ac,
                sortable: true,
                format: (row: any) => formatNumber(row.base_ac)
            },
        ];
    }

    private buyItem(row: ItemDefinition) {

        if (typeof this.component !== 'undefined') {
            this.ajax.doShopAction(this.component, SHOP_ACTIONS.BUY, {
                item_id: row.id,
            });
        }
    }
}
