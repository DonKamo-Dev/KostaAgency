<?php

namespace App\Http\Middleware;

use App\Models\SiteVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteVisit
{
    private const TRACKED_ROUTES = [
        'landing',
        'portfolio',
        'contact',
        'cv',
        'kamo',
        'perfil',
        'services.show',
        'privacy',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            if ($this->shouldTrack($request, $response)) {
                $this->recordVisit($request);
            }
        } catch (\Throwable $e) {
            // Never interrupt user experience if analytics logging fails
        }

        return $response;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET')) {
            return false;
        }

        if ($request->ajax() || $request->wantsJson() || $request->hasHeader('X-Livewire')) {
            return false;
        }

        if ($response->getStatusCode() >= 400) {
            return false;
        }

        $routeName = $request->route()?->getName();
        if (! in_array($routeName, self::TRACKED_ROUTES, true)) {
            return false;
        }

        $ua = strtolower((string) $request->userAgent());
        if ($ua === '') {
            return false;
        }

        $botSignatures = [
            'bot', 'crawl', 'spider', 'slurp', 'lighthouse', 'headless',
            'pingdom', 'uptimerobot', 'facebookexternalhit', 'facebot',
            'twitterbot', 'linkedinbot', 'embedly', 'quora link preview',
            'curl/', 'wget/', 'python-requests', 'go-http-client', 'postman',
        ];

        foreach ($botSignatures as $bot) {
            if (str_contains($ua, $bot)) {
                return false;
            }
        }

        return true;
    }

    private function recordVisit(Request $request): void
    {
        if (! SiteVisit::ensureTableExists()) {
            return;
        }

        $ip = $this->resolveClientIp($request);
        $ua = (string) $request->userAgent();
        $today = now()->toDateString();
        $visitorHash = hash('sha256', $ip.'|'.substr($ua, 0, 180).'|'.$today);

        $path = '/'.ltrim($request->path(), '/');
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        // Prevent duplicate spam on rapid refresh of the exact same page within 5 minutes
        $dedupKey = 'visit_dedup:'.md5($visitorHash.'|'.$path);
        if (! Cache::add($dedupKey, 1, now()->addMinutes(5))) {
            return;
        }

        [$channel, $sourceLabel] = $this->detectSource($request);
        $geo = $this->resolveGeo($request, $ip);
        $device = $this->detectDevice($ua);

        SiteVisit::create([
            'visitor_hash'   => $visitorHash,
            'session_id'     => $request->hasSession() ? substr((string) $request->session()->getId(), 0, 100) : null,
            'path'           => substr($path, 0, 255),
            'source_channel' => $channel,
            'source_label'   => $sourceLabel ? substr($sourceLabel, 0, 100) : null,
            'referrer_url'   => $request->headers->get('referer') ? substr((string) $request->headers->get('referer'), 0, 500) : null,
            'utm_source'     => $request->query('utm_source') ? substr((string) $request->query('utm_source'), 0, 100) : null,
            'utm_medium'     => $request->query('utm_medium') ? substr((string) $request->query('utm_medium'), 0, 100) : null,
            'utm_campaign'   => $request->query('utm_campaign') ? substr((string) $request->query('utm_campaign'), 0, 100) : null,
            'country_code'   => $geo['country_code'],
            'country_name'   => $geo['country_name'],
            'region_name'    => $geo['region_name'],
            'city_name'      => $geo['city_name'],
            'device_type'    => $device,
            'ip_address'     => substr($ip, 0, 45),
        ]);
    }

    private function resolveClientIp(Request $request): string
    {
        $headers = [
            'CF-Connecting-IP',
            'True-Client-IP',
            'X-Real-IP',
            'X-Forwarded-For',
        ];

        foreach ($headers as $header) {
            $val = $request->headers->get($header);
            if (! empty($val)) {
                $firstIp = trim(explode(',', $val)[0]);
                if (filter_var($firstIp, FILTER_VALIDATE_IP)) {
                    return $firstIp;
                }
            }
        }

        return (string) ($request->ip() ?? '0.0.0.0');
    }

    private function detectSource(Request $request): array
    {
        $utmSource = strtolower(trim((string) ($request->query('utm_source') ?? $request->query('source') ?? $request->query('ref') ?? '')));
        $ua = strtolower((string) $request->userAgent());
        $referer = (string) $request->headers->get('referer', '');
        $refHost = strtolower((string) parse_url($referer, PHP_URL_HOST));
        $refHost = preg_replace('/^www\./', '', $refHost);
        $ownHost = preg_replace('/^www\./', '', strtolower($request->getHost()));

        // 1. Explicit UTM / Click IDs
        if ($utmSource !== '') {
            if (str_contains($utmSource, 'ig') || str_contains($utmSource, 'instagram')) {
                return $this->rememberSessionSource($request, 'instagram', 'Instagram');
            }
            if (str_contains($utmSource, 'tiktok') || str_contains($utmSource, 'tt')) {
                return $this->rememberSessionSource($request, 'tiktok', 'TikTok');
            }
            if (str_contains($utmSource, 'fb') || str_contains($utmSource, 'facebook') || str_contains($utmSource, 'meta')) {
                return $this->rememberSessionSource($request, 'facebook', 'Facebook');
            }
            if (str_contains($utmSource, 'wa') || str_contains($utmSource, 'whatsapp')) {
                return $this->rememberSessionSource($request, 'whatsapp', 'WhatsApp');
            }
            if (str_contains($utmSource, 'linkedin') || str_contains($utmSource, 'li')) {
                return $this->rememberSessionSource($request, 'linkedin', 'LinkedIn');
            }
            if (str_contains($utmSource, 'youtube') || str_contains($utmSource, 'yt')) {
                return $this->rememberSessionSource($request, 'youtube', 'YouTube');
            }
            if (str_contains($utmSource, 'twitter') || $utmSource === 'x') {
                return $this->rememberSessionSource($request, 'x', 'X / Twitter');
            }
            if (str_contains($utmSource, 'google') || str_contains($utmSource, 'adwords') || str_contains($utmSource, 'gads')) {
                return $this->rememberSessionSource($request, 'google', 'Google');
            }
        }

        if ($request->has('igshid') || str_contains($ua, 'instagram')) {
            return $this->rememberSessionSource($request, 'instagram', 'Instagram');
        }
        if ($request->has('ttclid') || str_contains($ua, 'tiktok') || str_contains($ua, 'bytedancewebview') || str_contains($ua, 'musical_ly')) {
            return $this->rememberSessionSource($request, 'tiktok', 'TikTok');
        }
        if ($request->has('fbclid') || str_contains($ua, 'fban') || str_contains($ua, 'fbav') || str_contains($ua, 'fb_iab')) {
            return $this->rememberSessionSource($request, 'facebook', 'Facebook');
        }
        if (str_contains($ua, 'whatsapp')) {
            return $this->rememberSessionSource($request, 'whatsapp', 'WhatsApp');
        }
        if ($request->has('li_fat_id') || str_contains($ua, 'linkedinapp')) {
            return $this->rememberSessionSource($request, 'linkedin', 'LinkedIn');
        }
        if ($request->has('gclid') || $request->has('wbraid') || $request->has('gbraid')) {
            return $this->rememberSessionSource($request, 'google', 'Google Ads');
        }

        // 2. HTTP Referer Domain
        if ($refHost !== '' && $refHost !== $ownHost) {
            if (str_contains($refHost, 'instagram.com')) {
                return $this->rememberSessionSource($request, 'instagram', 'Instagram');
            }
            if (str_contains($refHost, 'tiktok.com')) {
                return $this->rememberSessionSource($request, 'tiktok', 'TikTok');
            }
            if (str_contains($refHost, 'facebook.com') || str_contains($refHost, 'fb.com') || str_contains($refHost, 'messenger.com')) {
                return $this->rememberSessionSource($request, 'facebook', 'Facebook');
            }
            if (str_contains($refHost, 'whatsapp.com') || $refHost === 'wa.me') {
                return $this->rememberSessionSource($request, 'whatsapp', 'WhatsApp');
            }
            if (str_contains($refHost, 'linkedin.com') || $refHost === 'lnkd.in') {
                return $this->rememberSessionSource($request, 'linkedin', 'LinkedIn');
            }
            if (str_contains($refHost, 'youtube.com') || $refHost === 'youtu.be') {
                return $this->rememberSessionSource($request, 'youtube', 'YouTube');
            }
            if (str_contains($refHost, 'twitter.com') || str_contains($refHost, 'x.com') || $refHost === 't.co') {
                return $this->rememberSessionSource($request, 'x', 'X / Twitter');
            }
            if (str_contains($refHost, 'google.') || str_contains($refHost, 'bing.com') || str_contains($refHost, 'duckduckgo.com') || str_contains($refHost, 'yahoo.com')) {
                return $this->rememberSessionSource($request, 'google', 'Google / Orgánico');
            }

            return $this->rememberSessionSource($request, 'referral', $refHost);
        }

        // 3. Internal navigation within same session preserves initial source
        if ($request->hasSession() && $request->session()->has('kosta_traffic_source')) {
            $saved = $request->session()->get('kosta_traffic_source');
            if (is_array($saved) && count($saved) === 2) {
                return $saved;
            }
        }

        return ['direct', 'Tráfico Directo'];
    }

    private function rememberSessionSource(Request $request, string $channel, string $label): array
    {
        $pair = [$channel, $label];
        if ($request->hasSession()) {
            $request->session()->put('kosta_traffic_source', $pair);
        }

        return $pair;
    }

    private function detectDevice(string $ua): string
    {
        if (preg_match('/(ipad|tablet|playbook|silk)|(android(?!.*mobile))/i', $ua)) {
            return 'tablet';
        }
        if (preg_match('/(mobi|iphone|ipod|android.*mobile|windows phone|iemobile|opera mini)/i', $ua)) {
            return 'mobile';
        }

        return 'desktop';
    }

    private function resolveGeo(Request $request, string $ip): array
    {
        // 1. Check proxy/CDN headers first
        $headerCountry = $request->headers->get('CF-IPCountry')
            ?? $request->headers->get('X-Vercel-IP-Country')
            ?? $request->headers->get('CloudFront-Viewer-Country');

        $headerCity = $request->headers->get('CF-IPCity')
            ?? $request->headers->get('X-Vercel-IP-City');

        if (! empty($headerCountry) && strtoupper($headerCountry) !== 'XX' && strtoupper($headerCountry) !== 'T1') {
            $code = strtoupper(substr(trim($headerCountry), 0, 2));

            return [
                'country_code' => $code,
                'country_name' => $this->countryNameFromCode($code),
                'region_name'  => $request->headers->get('X-Vercel-IP-Country-Region'),
                'city_name'    => $headerCity ? urldecode($headerCity) : null,
            ];
        }

        // 2. Check if private/local IP
        if (! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return [
                'country_code' => 'CO',
                'country_name' => 'Colombia',
                'region_name'  => 'Local',
                'city_name'    => 'Red Local',
            ];
        }

        // 3. Cached GeoIP lookup (cached 7 days per IP)
        return Cache::remember("site_geoip:{$ip}", 604800, function () use ($ip) {
            try {
                $res = Http::timeout(1.2)->acceptJson()->get("http://ip-api.com/json/{$ip}", [
                    'fields' => 'status,country,countryCode,regionName,city',
                    'lang'   => 'es',
                ]);

                if ($res->successful() && $res->json('status') === 'success') {
                    return [
                        'country_code' => strtoupper((string) $res->json('countryCode', 'CO')),
                        'country_name' => (string) $res->json('country', 'Colombia'),
                        'region_name'  => (string) $res->json('regionName', ''),
                        'city_name'    => (string) $res->json('city', ''),
                    ];
                }
            } catch (\Throwable $e) {
                // Fallback below
            }

            try {
                $res2 = Http::timeout(1.2)->acceptJson()->get("https://ipwho.is/{$ip}", [
                    'lang' => 'es',
                ]);

                if ($res2->successful() && $res2->json('success') === true) {
                    return [
                        'country_code' => strtoupper((string) $res2->json('country_code', 'CO')),
                        'country_name' => (string) $res2->json('country', 'Colombia'),
                        'region_name'  => (string) $res2->json('region', ''),
                        'city_name'    => (string) $res2->json('city', ''),
                    ];
                }
            } catch (\Throwable $e) {
                // Ignore
            }

            return [
                'country_code' => null,
                'country_name' => 'Desconocido',
                'region_name'  => null,
                'city_name'    => null,
            ];
        });
    }

    private function countryNameFromCode(string $code): string
    {
        $map = [
            'CO' => 'Colombia',
            'US' => 'Estados Unidos',
            'MX' => 'México',
            'ES' => 'España',
            'AR' => 'Argentina',
            'CL' => 'Chile',
            'PE' => 'Perú',
            'EC' => 'Ecuador',
            'PA' => 'Panamá',
            'CR' => 'Costa Rica',
            'DO' => 'República Dominicana',
            'VE' => 'Venezuela',
            'BR' => 'Brasil',
            'CA' => 'Canadá',
            'GB' => 'Reino Unido',
            'DE' => 'Alemania',
            'FR' => 'Francia',
            'IT' => 'Italia',
        ];

        return $map[$code] ?? $code;
    }
}
