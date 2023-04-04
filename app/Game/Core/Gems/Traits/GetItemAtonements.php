<?php

namespace App\Game\Core\Gems\Traits;

use App\Flare\Models\Item;
use App\Game\Core\Gems\Values\GemTypeValue;
use Illuminate\Support\Facades\DB;

trait GetItemAtonements {

    /**
     * Get atonement data based on gem array data.
     *
     * @param array $gemData
     * @return array
     */
    public function getElementAtonementFromArray(array $gemData): array {

        $atonements = [
            'atonements'       => [],
            'elemental_damage' => [],
        ];

        foreach (GemTypeValue::getNames() as $type => $name) {
            if (empty($gemData)) {
                $atonements['atonements'][] = ['name' => $name, 'total' => 0.0];
            } else {
                $atonements['atonements'][] = $this->fetchSummedValueFromArray($gemData, $type, $name);
            }
        }

        return $this->determineHighestValue($atonements);
    }

    /**
     * Get elemental atonement details from gems on item.
     *
     * @param Item $item
     * @return array
     */
    public function getElementAtonement(Item $item): array {
        $atonements = [
            'atonements'       => [],
            'elemental_damage' => [],
        ];

        foreach (GemTypeValue::getNames() as $type => $name) {
            if ($item->socket_count <= 0) {
                $atonements['atonements'][] = ['name' => $name, 'total' => 0.0];
            } else {
                $atonements['atonements'][] = $this->fetchSummedValue($item, $type, $name);
            }
        }

        return $this->determineHighestValue($atonements);
    }

    /**
     * Sum the atonement types from an array of gem data.
     *
     * @param array $gemData
     * @param int $type
     * @param string $name
     * @return array
     */
    protected function fetchSummedValueFromArray(array $gemData, int $type, string $name): array {
        $result = [];

        foreach ($gemData as $gem) {
            $atonementType =
                $gem["primary_atonement_type"] == $type ? "primary_atonement_amount" :
                    ($gem["secondary_atonement_type"] == $type ? "secondary_atonement_amount" :
                        ($gem["tertiary_atonement_type"] == $type ? "tertiary_atonement_amount" : ""));

            if ($atonementType) {
                if (array_key_exists($name, $result)) {
                    $result[$name] += $gem[$atonementType];
                } else {
                    $result[$name] = $gem[$atonementType];
                }
            }
        }

        return array_map(function($name, $total) {
            return ["name" => $name, "total" => $total];
        }, array_keys($result), $result)[0];
    }

    /**
     * Fetch summed values for type.
     *
     * @param Item $item
     * @param int $type
     * @param string $name
     * @return array
     */
    protected function fetchSummedValue(Item $item, int $type, string $name): array {
        $value = $item->sockets()->join('gems', function ($join) use ($type) {
            $join->on('item_sockets.gem_id', '=', 'gems.id')
                ->where(function ($query) use($type) {
                    $query->where('gems.primary_atonement_type', '=', $type)
                        ->orWhere('gems.secondary_atonement_type', '=', $type)
                        ->orWhere('gems.tertiary_atonement_type', '=', $type);
                });
        })->sum(DB::raw("CASE
                    WHEN gems.primary_atonement_type = $type THEN gems.primary_atonement_amount
                    WHEN gems.secondary_atonement_type = $type THEN gems.secondary_atonement_amount
                    WHEN gems.tertiary_atonement_type = $type THEN gems.tertiary_atonement_amount
                    ELSE 0
                END"));

        return [
            'name'  => $name,
            'total' => $value
        ];
    }

    /**
     * Determine highest value.
     *
     * @param array $atonements
     * @return array
     */
    protected function determineHighestValue(array $atonements): array {
        $highest = null;

        foreach ($atonements['atonements'] as $atonement) {
            if ($highest === null || $atonement['total'] > $highest['total']) {
                $highest = $atonement;
            }
        }

        $highestName = $highest['name'];
        $highestValue = $highest['total'];

        if ($highestValue <= 0) {
            $atonements['elemental_damage'] = [
                'name'   => 'N/A',
                'amount' => 0.0,
            ];

            return $atonements;
        }

        $atonements['elemental_damage'] = [
            'name'   => $highestName,
            'amount' => $highestValue,
        ];

        return $atonements;
    }
}