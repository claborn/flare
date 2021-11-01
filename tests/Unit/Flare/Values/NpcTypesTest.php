<?php

namespace Tests\Unit\Flare\Values;

use App\Flare\Values\ItemEffectsValue;
use App\Flare\Values\ItemUsabilityType;
use App\Flare\Values\MaxCurrenciesValue;
use App\Flare\Values\NpcTypes;
use Tests\TestCase;

class NpcTypesTest extends TestCase {

    public function testThrowError() {

        $this->expectException(\Exception::class);

        new NpcTypes(67);
    }

}