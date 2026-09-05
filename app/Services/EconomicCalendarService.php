<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EconomicCalendarService
{
    private const FEED_URL = 'https://nfs.faireconomy.media/ff_calendar_thisweek.json';
    private const CACHE_KEY = 'economic_calendar_this_week';
    private const CACHE_MINUTES = 60;
    private const FAILURE_CACHE_KEY = 'economic_calendar_failed_at';

    public function getEvents(): array
    {
        $raw = $this->getRawEvents();

        // Hydrate les dates en Carbon APRÈS la sortie du cache
        return array_map(function ($event) {
            $event['date'] = Carbon::parse($event['date']);
            return $event;
        }, $raw);
    }

    private function getRawEvents(): array
    {
        if (Cache::has(self::FAILURE_CACHE_KEY)) {
            return Cache::get(self::CACHE_KEY, []);
        }

        return Cache::remember(self::CACHE_KEY, now()->addMinutes(self::CACHE_MINUTES), function () {
            try {
                $response = Http::timeout(5)->connectTimeout(3)->get(self::FEED_URL);

                if (! $response->successful()) {
                    return [];
                }

                return collect($response->json())
                    ->map(fn($event) => [
                        'title' => $event['title'] ?? 'N/A',
                        'country' => $event['country'] ?? '',
                        'impact' => $event['impact'] ?? 'Low',
                        'date' => Carbon::parse($event['date'])->toIso8601String(), // string, pas Carbon
                        'forecast' => $event['forecast'] ?? null,
                        'previous' => $event['previous'] ?? null,
                    ])
                    ->sortBy('date')
                    ->values()
                    ->toArray();
            } catch (\Throwable $e) {
                Log::warning('Economic calendar fetch failed: ' . $e->getMessage());
                Cache::put(self::FAILURE_CACHE_KEY, true, now()->addMinutes(5));
                return [];
            }
        });
    }

    public function getHighImpactEvents(): array
    {
        return array_values(array_filter($this->getEvents(), fn($e) => $e['impact'] === 'High'));
    }

    public function getTodayEvents(): array
    {
        return array_values(array_filter($this->getEvents(), fn($e) => $e['date']->isToday()));
    }
}