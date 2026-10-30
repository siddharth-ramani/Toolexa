<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PercentageController extends Controller
{
    public function index()
    {
        return view('tools.percentage');
    }

    public function calculate(Request $request)
    {
        $mode = $request->input('mode', 'find_percent');
        $first = $request->input('first_number', $request->input('value'));
        $second = $request->input('second_number', $request->input('total'));

        $request->merge([
            'mode' => $mode,
            'first_number' => $first,
            'second_number' => $second,
        ]);

        $validated = $request->validate([
            'mode' => 'required|in:percent_of,find_percent,percent_change',
            'first_number' => 'required|numeric',
            'second_number' => 'required|numeric',
        ], [
            'first_number.required' => 'Enter the first number.',
            'second_number.required' => 'Enter the second number.',
        ]);

        $first = (float) $validated['first_number'];
        $second = (float) $validated['second_number'];

        if (($mode === 'find_percent' && $second == 0.0) || ($mode === 'percent_change' && $first == 0.0)) {
            $field = $mode === 'find_percent' ? 'second_number' : 'first_number';

            return back()
                ->withErrors([$field => 'This value cannot be zero for the selected calculation.'])
                ->withInput();
        }

        $result = match ($mode) {
            'percent_of' => [
                'value' => ($first / 100) * $second,
                'suffix' => '',
                'summary' => $this->format($first).'% of '.$this->format($second).' is',
                'calculation' => '('.$this->format($first).' / 100) × '.$this->format($second),
            ],
            'percent_change' => $this->percentageChange($first, $second),
            default => [
                'value' => ($first / $second) * 100,
                'suffix' => '%',
                'summary' => $this->format($first).' is this percentage of '.$this->format($second),
                'calculation' => '('.$this->format($first).' / '.$this->format($second).') × 100',
            ],
        };

        return view('tools.percentage', compact('mode', 'first', 'second', 'result'));
    }

    private function percentageChange(float $old, float $new): array
    {
        $change = (($new - $old) / abs($old)) * 100;
        $direction = $change > 0 ? 'increase' : ($change < 0 ? 'decrease' : 'change');

        return [
            'value' => abs($change),
            'suffix' => '% '.$direction,
            'summary' => 'From '.$this->format($old).' to '.$this->format($new),
            'calculation' => '(('.$this->format($new).' − '.$this->format($old).') / |'.$this->format($old).'|) × 100',
        ];
    }

    private function format(float $number): string
    {
        return rtrim(rtrim(number_format($number, 4, '.', ''), '0'), '.');
    }
}
