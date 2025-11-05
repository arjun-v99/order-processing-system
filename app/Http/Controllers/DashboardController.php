<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class DashboardController extends Controller
{
    public function getOrdersSummmary()
    {
        try {
        } catch (Exception $e) {
            Log::error('Unable to login user. Error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
