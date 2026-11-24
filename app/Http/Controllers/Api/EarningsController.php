<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EarningsService;
use Illuminate\Http\Request;

class EarningsController extends Controller
{
    protected EarningsService $earningsService;

    public function __construct(EarningsService $earningsService)
    {
        $this->earningsService = $earningsService;
    }

    public function show(Request $request)
    {
        $summary = $this->earningsService->summary($request->user());

        return $this->success($summary, 'Earnings summary retrieved successfully.');
    }
}