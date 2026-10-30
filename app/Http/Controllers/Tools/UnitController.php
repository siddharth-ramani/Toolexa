<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        return view('tools.unit', ['conversionOptions' => $this->conversionOptions()]);
    }

    public function convert(Request $request)
    {
        $options = $this->conversionOptions();
        $validated = $request->validate([
            'value' => 'required|numeric',
            'type' => 'required|in:'.implode(',', array_keys($options)),
        ]);

        $value = (float) $validated['value'];
        $type = $validated['type'];
        $option = $options[$type];

        if ($value < 0 && $option['group'] !== 'Temperature') {
            return back()->withErrors(['value' => 'Negative values are supported only for temperature conversions.'])->withInput();
        }

        $result = match ($type) {
            'c_to_f' => ($value * 9 / 5) + 32,
            'f_to_c' => ($value - 32) * 5 / 9,
            'c_to_k' => $value + 273.15,
            'k_to_c' => $value - 273.15,
            'f_to_k' => ($value - 32) * 5 / 9 + 273.15,
            'k_to_f' => ($value - 273.15) * 9 / 5 + 32,
            default => $value * $option['factor'],
        };

        if ((in_array($type, ['c_to_k', 'f_to_k'], true) && $result < 0)
            || (in_array($type, ['k_to_c', 'k_to_f'], true) && $value < 0)) {
            return back()->withErrors(['value' => 'Temperature cannot be below absolute zero.'])->withInput();
        }

        return view('tools.unit', [
            'conversionOptions' => $options,
            'value' => $value,
            'type' => $type,
            'result' => $result,
            'resultUnit' => $option['to'],
            'conversionLabel' => $option['label'],
        ]);
    }

    private function conversionOptions(): array
    {
        return [
            'km_to_m' => $this->option('Length', 'Kilometres to metres', 'km', 'm', 1000),
            'm_to_km' => $this->option('Length', 'Metres to kilometres', 'm', 'km', 0.001),
            'm_to_cm' => $this->option('Length', 'Metres to centimetres', 'm', 'cm', 100),
            'cm_to_m' => $this->option('Length', 'Centimetres to metres', 'cm', 'm', 0.01),
            'in_to_cm' => $this->option('Length', 'Inches to centimetres', 'in', 'cm', 2.54),
            'cm_to_in' => $this->option('Length', 'Centimetres to inches', 'cm', 'in', 1 / 2.54),
            'ft_to_m' => $this->option('Length', 'Feet to metres', 'ft', 'm', 0.3048),
            'm_to_ft' => $this->option('Length', 'Metres to feet', 'm', 'ft', 1 / 0.3048),
            'mi_to_km' => $this->option('Length', 'Miles to kilometres', 'mi', 'km', 1.609344),
            'km_to_mi' => $this->option('Length', 'Kilometres to miles', 'km', 'mi', 1 / 1.609344),
            'kg_to_g' => $this->option('Weight', 'Kilograms to grams', 'kg', 'g', 1000),
            'g_to_kg' => $this->option('Weight', 'Grams to kilograms', 'g', 'kg', 0.001),
            'kg_to_lb' => $this->option('Weight', 'Kilograms to pounds', 'kg', 'lb', 1 / 0.45359237),
            'lb_to_kg' => $this->option('Weight', 'Pounds to kilograms', 'lb', 'kg', 0.45359237),
            'oz_to_g' => $this->option('Weight', 'Ounces to grams', 'oz', 'g', 28.349523125),
            'g_to_oz' => $this->option('Weight', 'Grams to ounces', 'g', 'oz', 1 / 28.349523125),
            'c_to_f' => $this->option('Temperature', 'Celsius to Fahrenheit', '°C', '°F'),
            'f_to_c' => $this->option('Temperature', 'Fahrenheit to Celsius', '°F', '°C'),
            'c_to_k' => $this->option('Temperature', 'Celsius to kelvin', '°C', 'K'),
            'k_to_c' => $this->option('Temperature', 'Kelvin to Celsius', 'K', '°C'),
            'f_to_k' => $this->option('Temperature', 'Fahrenheit to kelvin', '°F', 'K'),
            'k_to_f' => $this->option('Temperature', 'Kelvin to Fahrenheit', 'K', '°F'),
            'l_to_ml' => $this->option('Volume', 'Litres to millilitres', 'L', 'mL', 1000),
            'ml_to_l' => $this->option('Volume', 'Millilitres to litres', 'mL', 'L', 0.001),
            'l_to_us_gal' => $this->option('Volume', 'Litres to US gallons', 'L', 'US gal', 1 / 3.785411784),
            'us_gal_to_l' => $this->option('Volume', 'US gallons to litres', 'US gal', 'L', 3.785411784),
            'l_to_us_floz' => $this->option('Volume', 'Litres to US fluid ounces', 'L', 'US fl oz', 1000 / 29.5735295625),
            'us_floz_to_l' => $this->option('Volume', 'US fluid ounces to litres', 'US fl oz', 'L', 29.5735295625 / 1000),
        ];
    }

    private function option(string $group, string $label, string $from, string $to, float $factor = 1): array
    {
        return compact('group', 'label', 'from', 'to', 'factor');
    }
}
