<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;

class ToolController extends Controller
{
    private array $controllers = [
        'gst-calculator' => GstController::class,
        'emi-calculator' => EmiController::class,
        'age-calculator' => AgeController::class,
        'percentage-calculator' => PercentageController::class,
        'discount-calculator' => DiscountController::class,
        'simple-interest-calculator' => InterestController::class,
        'password-generator' => PasswordController::class,
        'unit-converter' => UnitController::class,
        'qr-generator' => QrController::class,
        'text-case-converter' => TextController::class,
    ];

    public function show(string $slug)
    {
        $tool = HomeController::toolBySlug($slug);

        if ($tool && ($tool['view'] ?? null) === 'finance-calculator') {
            return app(FinanceCalculatorController::class)->index($slug);
        }

        abort_unless(isset($this->controllers[$slug]), 404);

        return app($this->controllers[$slug])->index();
    }
}
