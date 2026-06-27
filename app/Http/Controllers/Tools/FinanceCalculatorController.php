<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinanceCalculatorController extends Controller
{
    public function index(string $slug)
    {
        $tool = HomeController::toolBySlug($slug);

        abort_unless($tool && ($tool['view'] ?? null) === 'finance-calculator', 404);

        return view('tools.finance-calculator', compact('slug'));
    }

    public function calculate(Request $request, string $slug)
    {
        $tool = HomeController::toolBySlug($slug);

        abort_unless($tool && ($tool['view'] ?? null) === 'finance-calculator', 404);

        $rules = [];
        foreach ($tool['fields'] as $field) {
            $rules[$field['name']] = $field['type'] === 'select'
                ? 'required|numeric|min:1'
                : 'required|numeric|min:0';
        }

        $validated = $request->validate($rules);
        $result = $this->calculateResult($tool['calculator'], $validated);

        return view('tools.finance-calculator', compact('slug', 'validated', 'result'));
    }

    private function calculateResult(string $calculator, array $input): array
    {
        return match ($calculator) {
            'compound_interest', 'fd' => $this->compound($input),
            'sip' => $this->sip($input['monthly_investment'], $input['rate'], $input['years'], true),
            'rd' => $this->sip($input['monthly_deposit'], $input['rate'], $input['years'], false),
            'loan' => $this->loan($input['amount'], $input['rate'], $input['tenure_years']),
            'ppf' => $this->ppf($input['yearly_investment'], $input['rate'], $input['years']),
            'epf' => $this->epf($input),
            'nps' => $this->nps($input),
            'cagr' => $this->cagr($input['start_value'], $input['end_value'], $input['years']),
            'inflation' => $this->inflation($input['current_cost'], $input['rate'], $input['years']),
            default => [],
        };
    }

    private function compound(array $input): array
    {
        $principal = (float) $input['principal'];
        $rate = (float) $input['rate'] / 100;
        $years = (float) $input['years'];
        $frequency = (float) $input['frequency'];
        $maturity = $principal * pow(1 + ($rate / $frequency), $frequency * $years);

        return [
            'Maturity Amount' => $maturity,
            'Total Interest' => $maturity - $principal,
            'Principal Amount' => $principal,
        ];
    }

    private function sip(float $monthlyInvestment, float $rate, float $years, bool $annuityDue): array
    {
        $months = max(1, (int) round($years * 12));
        $monthlyRate = $rate / 12 / 100;
        $invested = $monthlyInvestment * $months;
        $maturity = $monthlyRate > 0
            ? $monthlyInvestment * ((pow(1 + $monthlyRate, $months) - 1) / $monthlyRate)
            : $invested;

        if ($annuityDue) {
            $maturity *= (1 + $monthlyRate);
        }

        return [
            'Maturity Amount' => $maturity,
            'Total Investment' => $invested,
            'Estimated Gain' => $maturity - $invested,
        ];
    }

    private function loan(float $amount, float $rate, float $years): array
    {
        $months = max(1, (int) round($years * 12));
        $monthlyRate = $rate / 12 / 100;
        $emi = $monthlyRate > 0
            ? ($amount * $monthlyRate * pow(1 + $monthlyRate, $months)) / (pow(1 + $monthlyRate, $months) - 1)
            : $amount / $months;
        $total = $emi * $months;

        return [
            'Monthly EMI' => $emi,
            'Total Payment' => $total,
            'Total Interest' => $total - $amount,
        ];
    }

    private function ppf(float $yearlyInvestment, float $rate, float $years): array
    {
        $balance = 0;
        $roundedYears = max(1, (int) round($years));

        for ($year = 1; $year <= $roundedYears; $year++) {
            $balance = ($balance + $yearlyInvestment) * (1 + ($rate / 100));
        }

        return [
            'Maturity Amount' => $balance,
            'Total Investment' => $yearlyInvestment * $roundedYears,
            'Estimated Interest' => $balance - ($yearlyInvestment * $roundedYears),
        ];
    }

    private function epf(array $input): array
    {
        $monthlyContribution = ((float) $input['monthly_salary']) * (((float) $input['employee_rate'] + (float) $input['employer_rate']) / 100);
        $result = $this->sip($monthlyContribution, (float) $input['rate'], (float) $input['years'], false);

        return [
            'EPF Corpus' => $result['Maturity Amount'],
            'Total Contribution' => $result['Total Investment'],
            'Estimated Interest' => $result['Estimated Gain'],
        ];
    }

    private function nps(array $input): array
    {
        $result = $this->sip((float) $input['monthly_investment'], (float) $input['rate'], (float) $input['years'], false);
        $annuityCorpus = $result['Maturity Amount'] * ((float) $input['annuity_percent'] / 100);

        return [
            'Estimated Corpus' => $result['Maturity Amount'],
            'Lump Sum Amount' => $result['Maturity Amount'] - $annuityCorpus,
            'Annuity Corpus' => $annuityCorpus,
            'Total Investment' => $result['Total Investment'],
        ];
    }

    private function cagr(float $startValue, float $endValue, float $years): array
    {
        $cagr = $startValue > 0 && $years > 0
            ? (pow($endValue / $startValue, 1 / $years) - 1) * 100
            : 0;

        return [
            'CAGR' => $cagr,
            'Starting Value' => $startValue,
            'Ending Value' => $endValue,
            'Absolute Gain' => $endValue - $startValue,
        ];
    }

    private function inflation(float $currentCost, float $rate, float $years): array
    {
        $futureCost = $currentCost * pow(1 + ($rate / 100), $years);

        return [
            'Future Cost' => $futureCost,
            'Current Cost' => $currentCost,
            'Cost Increase' => $futureCost - $currentCost,
        ];
    }
}
