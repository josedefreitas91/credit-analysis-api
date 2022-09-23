<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CreditAnalysis;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\CreditAnalysisRequest;
use App\Http\Resources\CreditAnalysisResource;
use Illuminate\Support\Facades\Http;
use App\Enums\ResultType;
use Carbon\Carbon;

class CreditAnalysisController extends Controller
{
    public function store(CustomerRequest $request)
    {
        $cep = str_replace("-", "", $request->get('cep'));
        $response = Http::get('https://viacep.com.br/ws/'.$cep.'/json/');
        $responseObj = $response->object();
        if (!$response->ok() || isset($responseObj->erro)) {
            return response(['error' => 'CEP not found'], 404);
        }

        if (strtolower($request->get('city')) !== strtolower($responseObj->bairro) || strtolower($request->get('federative_unit')) !== strtolower($responseObj->uf)) {
            return response(['error' => "City and Federative Unit don't match"], 404);
        }

        $customer = Customer::firstOrCreate($request->only('cpf'), $request->except('cpf'));
        $creditAnalysisResult = $this->creditAnalysis($customer->rent_value, $customer->salary, $customer->negative, $customer->card_limit, $customer->id);
        $creditAnalysisResult['reference_code'] = $this->generateReferenceCode();
        $creditAnalysis = $customer->credit_analysis()->create($creditAnalysisResult);

        return response(new CreditAnalysisResource($creditAnalysis), 200);
    }

    private function creditAnalysis($rentValue, $salary, $negative, $cardLimit, $customerId)
    {
        $score = 100;
        $parameterOne = false;
        $parameterTwo = false;
        $result = null;

        if ($this->isRentValueGreaterThan30PercentSalary($rentValue, $salary)) {
            $score = $score - ($score * 0.18);
            $parameterOne = true;
        }
        if ($negative) {
            $score = $score - ($score * 0.31);
            $parameterTwo = true;
        }
        if ($cardLimit <= $rentValue) {
            $score = $score - ($score * 0.15);
        }
        if ($this->isDisapprovedInLast90Days($customerId)) {
            $score = $score - ($score * 0.10);
        }

        // Rules
        if ($parameterOne && $parameterTwo) {
            $result = ResultType::disapproved;
        } else {
            if ($score <= 30) {
                $result = ResultType::disapproved;
            }
            if ($score > 30 && $score < 60) {
                $result = ResultType::derivative;
            }
            if ($score >= 60) {
                $result = ResultType::approved;
            }
        }

        return [
            'score' => ceil($score),
            'result' => $result
        ];
    }

    private function isRentValueGreaterThan30PercentSalary($rentValue, $salary)
    {
        $percentage = 0.30; // 30%
        if (!$rentValue || !$salary) {
            return false;
        }

        return $rentValue > ($salary * $percentage);
    }

    private function isDisapprovedInLast90Days($customerId)
    {
        $last90Days = Carbon::now()->subDays(90)->format('Y-m-d');
        return CreditAnalysis::where('customer_id', $customerId)->where('created_at', '>=', $last90Days)->where('result', ResultType::disapproved)->exists();
    }

    private function generateReferenceCode()
    {
        $now = Carbon::now();
        $analysis = CreditAnalysis::whereDate('created_at', $now->format('Y-m-d'))->count();
        $newPosition = str_pad(($analysis + 1), 5, "0", STR_PAD_LEFT);
        $newCode = "{$now->format('Ymd')}-{$newPosition}";

        return $newCode;
    }

    public function show(CreditAnalysisRequest $request)
    {
        $customer = Customer::where('cpf', $request->get('cpf'))->first();
        if (!$customer) {
            return response(['error' => "Customer not found"], 404);
        }
        $lastCreditAnalysis = $customer->credit_analysis->sortByDesc('created_at')->first();

        return response(new CreditAnalysisResource($lastCreditAnalysis), 200);
    }
}
