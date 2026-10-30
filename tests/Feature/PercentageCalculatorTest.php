<?php

namespace Tests\Feature;

use Tests\TestCase;

class PercentageCalculatorTest extends TestCase
{
    public function test_page_explains_all_three_percentage_modes(): void
    {
        $this->get('/tools/percentage-calculator')
            ->assertOk()
            ->assertSee('What is X% of Y?')
            ->assertSee('X is what percent of Y?')
            ->assertSee('Percentage increase or decrease')
            ->assertSee('Percentage change from an original value of zero is undefined');
    }

    public function test_it_calculates_a_percentage_of_a_number(): void
    {
        $this->post('/tools/percentage-calculator', [
            'mode' => 'percent_of',
            'first_number' => 20,
            'second_number' => 250,
        ])->assertOk()
            ->assertSee('20% of 250 is')
            ->assertSee('50.00');
    }

    public function test_it_calculates_a_value_as_a_percentage_of_a_total(): void
    {
        $this->post('/tools/percentage-calculator', [
            'mode' => 'find_percent',
            'first_number' => 45,
            'second_number' => 60,
        ])->assertOk()
            ->assertSee('75.00%');
    }

    public function test_it_labels_percentage_increase_and_decrease(): void
    {
        $this->post('/tools/percentage-calculator', [
            'mode' => 'percent_change',
            'first_number' => 80,
            'second_number' => 100,
        ])->assertOk()
            ->assertSee('25.00% increase');

        $this->post('/tools/percentage-calculator', [
            'mode' => 'percent_change',
            'first_number' => 100,
            'second_number' => 80,
        ])->assertOk()
            ->assertSee('20.00% decrease');
    }

    public function test_it_rejects_a_zero_denominator(): void
    {
        $this->from('/tools/percentage-calculator')
            ->post('/tools/percentage-calculator', [
                'mode' => 'percent_change',
                'first_number' => 0,
                'second_number' => 10,
            ])->assertRedirect('/tools/percentage-calculator')
            ->assertSessionHasErrors('first_number');
    }
}
