<?php
// Gemini AI Configuration
define('GEMINI_API_KEY', 'YOUR_GEMINI_API_KEY_HERE');
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent');

class GeminiAI {
    private $api_key;
    private $api_url;

    public function __construct() {
        $this->api_key = GEMINI_API_KEY;
        $this->api_url = GEMINI_API_URL;
    }

    public function customizeTour($destination, $budget, $duration, $preferences) {
        $prompt = "You are a professional tour guide and travel planner. Help design a perfect tour package with the following details:\n";
        $prompt .= "Destination: $destination\n";
        $prompt .= "Budget: ₹$budget\n";
        $prompt .= "Duration: $duration days\n";
        $prompt .= "Preferences: $preferences\n\n";
        $prompt .= "Please provide:\n";
        $prompt .= "1. Detailed Day-wise Itinerary\n";
        $prompt .= "2. Recommended Hotels (within budget)\n";
        $prompt .= "3. Activities and Attractions\n";
        $prompt .= "4. Estimated Costs Breakdown\n";
        $prompt .= "5. Travel Tips and Best Time to Visit\n";
        $prompt .= "6. Food Recommendations\n";
        $prompt .= "Format the response in clear sections with bullet points. Make it practical and budget-friendly.";

        return $this->callGeminiAPI($prompt);
    }

    public function suggestActivities($destination, $interests) {
        $prompt = "Suggest the best activities and attractions in $destination for someone interested in: $interests. ";
        $prompt .= "Provide at least 5 activities with estimated costs and duration. Format as JSON array.";
        return $this->callGeminiAPI($prompt);
    }

    public function suggestHotels($destination, $budget, $rating) {
        $prompt = "Suggest good hotels in $destination within ₹$budget budget with minimum $rating rating. ";
        $prompt .= "Provide 5 options with names, estimated prices, amenities, and why they're good. Format as JSON.";
        return $this->callGeminiAPI($prompt);
    }

    public function optimizeExpenses($expenses_array) {
        $prompt = "Analyze these tour expenses and suggest optimization tips: " . json_encode($expenses_array) . ". ";
        $prompt .= "Provide breakdown, percentage analysis, and cost-saving recommendations.";
        return $this->callGeminiAPI($prompt);
    }

    private function callGeminiAPI($prompt) {
        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url . '?key=' . $this->api_key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code === 200) {
            $result = json_decode($response, true);
            return $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Error processing response';
        } else {
            return 'Error: API request failed with status ' . $http_code;
        }
    }
}
?>