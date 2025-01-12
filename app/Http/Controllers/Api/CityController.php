<?php

namespace App\Http\Controllers\Api;

use Aminkhoshzahmat\CountryCode\Enums\CountryType;
use App\Http\Controllers\Controller;
use Error;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    public function getCities(string $country): JsonResponse
    {
        $country = strtoupper($country);

        // Find the matching enum case
        $matchingCountry = null;
        foreach (CountryType::cases() as $case) {
            if ($case->getCode() === $country) {
                $matchingCountry = $case;
                break;
            }
        }

        if ($matchingCountry === null) {
            return response()->json(['message' => 'Invalid country code'], 400);
        }

        try {
            $cities = $matchingCountry->getCitites(); // Note the typo in the method name

            if (empty($cities)) {
                return response()->json(['message' => 'No cities found for this country'], 404);
            }

            sort($cities); // Sort cities alphabetically

            return response()->json($cities);
        } catch (Error $e) {
            return response()->json(['message' => 'An error occurred while fetching cities: ' . $e->getMessage()], 500);
        }
    }
}
