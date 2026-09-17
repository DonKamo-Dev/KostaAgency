<?php

namespace App\Jobs;

use App\Models\AiMetaQuote;
use App\Services\ClaudeMetaAdsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateMetaAdsQuote implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 120;

    public function __construct(public int $quoteId) {}

    public function handle(ClaudeMetaAdsService $service): void
    {
        $quote = AiMetaQuote::find($this->quoteId);

        if (! $quote || $quote->generation_status === AiMetaQuote::STATUS_COMPLETED) {
            return;
        }

        $quote->generation_status = AiMetaQuote::STATUS_PROCESSING;
        $quote->error = null;
        $quote->save();

        try {
            $result = $service->generate($this->payload($quote));

            $quote->ai_result = $result;
            $quote->generation_status = AiMetaQuote::STATUS_COMPLETED;
            $quote->error = null;
            $quote->save();
        } catch (\Throwable $e) {
            $quote->generation_status = AiMetaQuote::STATUS_FAILED;
            $quote->error = $e->getMessage();
            $quote->save();

            throw $e;
        }
    }

    private function payload(AiMetaQuote $quote): array
    {
        return [
            'client_name' => $quote->client_name,
            'industry' => $quote->industry,
            'budget_cop' => $quote->budget_cop,
            'duration_days' => $quote->duration_days,
            'age_range' => $quote->age_range,
            'location' => $quote->location,
            'interests' => $quote->interests ?? [],
            'platform' => $quote->platform ?? 'META',
            'campaign_type' => $quote->campaign_type,
            'destination' => $quote->destination,
            'whatsapp_number' => $quote->whatsapp_number,
        ];
    }
}
