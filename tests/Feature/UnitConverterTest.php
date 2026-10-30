<?php

namespace Tests\Feature;

use Tests\TestCase;

class UnitConverterTest extends TestCase
{
    public function test_page_groups_all_supported_measurement_categories(): void
    {
        $this->get('/tools/unit-converter')
            ->assertOk()
            ->assertSee('<optgroup label="Length">', false)
            ->assertSee('<optgroup label="Weight">', false)
            ->assertSee('<optgroup label="Temperature">', false)
            ->assertSee('<optgroup label="Volume">', false)
            ->assertSee('US gallons to litres');
    }

    public function test_legacy_kilometre_conversion_remains_supported(): void
    {
        $this->post('/tools/unit-converter', ['value' => 2, 'type' => 'km_to_m'])
            ->assertOk()
            ->assertSee('2000 m');
    }

    public function test_weight_temperature_and_volume_constants_are_applied(): void
    {
        $this->post('/tools/unit-converter', ['value' => 10, 'type' => 'kg_to_lb'])
            ->assertOk()
            ->assertSee('22.046226 lb');

        $this->post('/tools/unit-converter', ['value' => 25, 'type' => 'c_to_f'])
            ->assertOk()
            ->assertSee('77 °F');

        $this->post('/tools/unit-converter', ['value' => 2, 'type' => 'us_gal_to_l'])
            ->assertOk()
            ->assertSee('7.570824 L');
    }

    public function test_invalid_negative_measurements_and_absolute_zero_are_rejected(): void
    {
        $this->from('/tools/unit-converter')
            ->post('/tools/unit-converter', ['value' => -1, 'type' => 'kg_to_g'])
            ->assertRedirect('/tools/unit-converter')
            ->assertSessionHasErrors('value');

        $this->from('/tools/unit-converter')
            ->post('/tools/unit-converter', ['value' => -274, 'type' => 'c_to_k'])
            ->assertRedirect('/tools/unit-converter')
            ->assertSessionHasErrors('value');
    }
}
