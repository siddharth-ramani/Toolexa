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
            'marketplace_profit' => $this->marketplaceProfit($input),
            'volumetric_weight' => $this->volumetricWeight($input),
            'cod_fee' => $this->codFee($input),
            'marketplace_commission' => $this->marketplaceCommission($input),
            'break_even_price' => $this->breakEvenPrice($input),
            'return_rto_cost' => $this->returnRtoCost($input),
            'shipping_cost_order' => $this->shippingCostOrder($input),
            'reorder_point' => $this->reorderPoint($input),
            'seller_roas' => $this->sellerRoas($input),
            'discount_profit' => $this->discountProfit($input),
            default => [],
        };
    }

    private function marketplaceProfit(array $input): array
    {
        $sale = (float) $input['sale_price'];
        $commission = $sale * (float) $input['commission_rate'] / 100;
        $feeBase = $commission + (float) $input['shipping_fee'] + (float) $input['other_fees'];
        $feeTax = $feeBase * (float) $input['fee_tax_rate'] / 100;
        $payout = $sale - $feeBase - $feeTax;
        $profit = $payout - (float) $input['product_cost'];

        return ['Estimated Payout' => $payout, 'Total Marketplace Fees' => $feeBase + $feeTax, 'Estimated Profit' => $profit, 'Profit Margin' => $sale > 0 ? $profit / $sale * 100 : 0];
    }

    private function volumetricWeight(array $input): array
    {
        $volumetric = ((float) $input['length'] * (float) $input['width'] * (float) $input['height']) / max(1, (float) $input['divisor']);
        $actual = (float) $input['actual_weight'];

        return ['Volumetric Weight' => $volumetric, 'Actual Weight' => $actual, 'Chargeable Weight' => max($volumetric, $actual)];
    }

    private function codFee(array $input): array
    {
        $percentageFee = (float) $input['order_value'] * (float) $input['cod_rate'] / 100;
        $baseFee = $percentageFee + (float) $input['fixed_fee'];
        $tax = $baseFee * (float) $input['tax_rate'] / 100;
        $total = $baseFee + $tax;

        return ['Percentage COD Fee' => $percentageFee, 'Fixed COD Fee' => (float) $input['fixed_fee'], 'Tax on COD Fees' => $tax, 'Total COD Cost' => $total, 'Estimated Payout' => (float) $input['order_value'] - $total];
    }

    private function marketplaceCommission(array $input): array
    {
        $sale = (float) $input['sale_price'];
        $commission = $sale * (float) $input['commission_rate'] / 100;
        $tax = $commission * (float) $input['tax_rate'] / 100;

        return ['Marketplace Commission' => $commission, 'Tax on Commission' => $tax, 'Total Commission Cost' => $commission + $tax, 'Net After Commission' => $sale - $commission - $tax];
    }

    private function breakEvenPrice(array $input): array
    {
        $target = (float) $input['product_cost'] + (float) $input['fixed_fees'] + (float) $input['target_profit'];
        $retainedRate = max(0.01, 1 - ((float) $input['fee_rate'] / 100));
        $price = $target / $retainedRate;

        return ['Break-even Price' => ((float) $input['product_cost'] + (float) $input['fixed_fees']) / $retainedRate, 'Required Selling Price' => $price, 'Fees at Required Price' => $price * (float) $input['fee_rate'] / 100, 'Target Profit' => (float) $input['target_profit']];
    }

    private function returnRtoCost(array $input): array
    {
        $returns = (float) $input['orders'] * (float) $input['return_rate'] / 100;
        $lossPerReturn = (float) $input['forward_cost'] + (float) $input['reverse_cost'] + (float) $input['handling_loss'];

        return ['Expected Returns (orders)' => $returns, 'Loss per Return' => $lossPerReturn, 'Expected Return Loss' => $returns * $lossPerReturn, 'Return Cost per Order' => (float) $input['orders'] > 0 ? $returns * $lossPerReturn / (float) $input['orders'] : 0];
    }

    private function shippingCostOrder(array $input): array
    {
        $total = (float) $input['freight_total'] + (float) $input['packaging_total'] + (float) $input['surcharge_total'];
        $orders = max(1, (float) $input['orders']);

        return ['Total Shipping Cost' => $total, 'Average Shipping Cost per Order' => $total / $orders, 'Freight per Order' => (float) $input['freight_total'] / $orders, 'Packaging per Order' => (float) $input['packaging_total'] / $orders];
    }

    private function reorderPoint(array $input): array
    {
        $daily = (float) $input['daily_sales'];
        $leadDemand = $daily * (float) $input['lead_days'];
        $safety = $daily * (float) $input['safety_days'];

        return ['Lead-time Demand (units)' => $leadDemand, 'Safety Stock (units)' => $safety, 'Reorder Point (units)' => $leadDemand + $safety, 'Coverage (days)' => (float) $input['lead_days'] + (float) $input['safety_days']];
    }

    private function sellerRoas(array $input): array
    {
        $revenue = (float) $input['revenue'];
        $spend = (float) $input['ad_spend'];
        $grossProfit = $revenue * (float) $input['gross_margin'] / 100;

        return ['ROAS (x)' => $spend > 0 ? $revenue / $spend : 0, 'Advertising Cost Ratio (%)' => $revenue > 0 ? $spend / $revenue * 100 : 0, 'Gross Profit Before Ads' => $grossProfit, 'Contribution After Ads' => $grossProfit - $spend];
    }

    private function discountProfit(array $input): array
    {
        $sale = (float) $input['list_price'] * (1 - ((float) $input['discount_rate'] / 100));
        $fees = $sale * (float) $input['fee_rate'] / 100;
        $profit = $sale - $fees - (float) $input['product_cost'] - (float) $input['fixed_cost'];

        return ['Discounted Selling Price' => $sale, 'Marketplace Fees' => $fees, 'Profit after Discount' => $profit, 'Effective Margin (%)' => $sale > 0 ? $profit / $sale * 100 : 0];
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
