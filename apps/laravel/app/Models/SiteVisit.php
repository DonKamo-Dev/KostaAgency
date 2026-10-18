<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SiteVisit extends Model
{
    protected $fillable = [
        'visitor_hash',
        'session_id',
        'path',
        'source_channel',
        'source_label',
        'referrer_url',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'country_code',
        'country_name',
        'region_name',
        'city_name',
        'device_type',
        'ip_address',
    ];

    protected static bool $tableChecked = false;

    public static function ensureTableExists(): bool
    {
        if (self::$tableChecked) {
            return true;
        }

        try {
            if (! Schema::hasTable('site_visits')) {
                Schema::create('site_visits', function (Blueprint $table) {
                    $table->id();
                    $table->string('visitor_hash', 64)->index();
                    $table->string('session_id', 100)->nullable();
                    $table->string('path', 255)->index();
                    $table->string('source_channel', 40)->default('direct')->index();
                    $table->string('source_label', 100)->nullable();
                    $table->string('referrer_url', 500)->nullable();
                    $table->string('utm_source', 100)->nullable();
                    $table->string('utm_medium', 100)->nullable();
                    $table->string('utm_campaign', 100)->nullable();
                    $table->string('country_code', 8)->nullable()->index();
                    $table->string('country_name', 100)->nullable();
                    $table->string('region_name', 100)->nullable();
                    $table->string('city_name', 100)->nullable()->index();
                    $table->string('device_type', 20)->default('desktop');
                    $table->string('ip_address', 45)->nullable();
                    $table->timestamps();
                    $table->index('created_at');
                });
            }
            self::$tableChecked = true;

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function flagEmoji(?string $countryCode): string
    {
        $code = strtoupper(trim((string) $countryCode));
        if (strlen($code) !== 2 || ! ctype_alpha($code)) {
            return '🌐';
        }

        $first = mb_chr(ord($code[0]) - ord('A') + 0x1F1E6);
        $second = mb_chr(ord($code[1]) - ord('A') + 0x1F1E6);

        return $first.$second;
    }

    public static function channelMeta(string $channel): array
    {
        return match ($channel) {
            'instagram' => ['label' => 'Instagram', 'color' => '#E1306C', 'is_social' => true],
            'tiktok'    => ['label' => 'TikTok', 'color' => '#00F2FE', 'is_social' => true],
            'facebook'  => ['label' => 'Facebook', 'color' => '#1877F2', 'is_social' => true],
            'whatsapp'  => ['label' => 'WhatsApp', 'color' => '#25D366', 'is_social' => true],
            'linkedin'  => ['label' => 'LinkedIn', 'color' => '#0A66C2', 'is_social' => true],
            'youtube'   => ['label' => 'YouTube', 'color' => '#FF0000', 'is_social' => true],
            'x'         => ['label' => 'X / Twitter', 'color' => '#A1A1AA', 'is_social' => true],
            'google'    => ['label' => 'Google / Orgánico', 'color' => '#FBBF24', 'is_social' => false],
            'referral'  => ['label' => 'Web Externa', 'color' => '#A78BFA', 'is_social' => false],
            default     => ['label' => 'Tráfico Directo', 'color' => '#FFFFFF', 'is_social' => false],
        };
    }
}
