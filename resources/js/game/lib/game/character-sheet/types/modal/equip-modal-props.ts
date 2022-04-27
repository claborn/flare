import InventoryComparisonAdjustment from "./inventory-comparison-adjustment";

export default interface EquipModalProps {

    is_open: boolean;

    manage_modal: () => void;

    item_to_equip: InventoryComparisonAdjustment;

    equip_item: (type: string, position?: string) => void;
}