<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class CaseStudyVideo
{
    /**
     * Stores an uploaded video file and returns its public path.
     */
    public static function storeUploaded(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'mp4';
        $filename = 'film_' . Str::random(16) . '.' . $extension;
        $relativeDir = 'case-studies/videos';

        // Store via Laravel Storage disk 'public'
        Storage::disk('public')->putFileAs($relativeDir, $file, $filename);

        // Mirror to public/storage for direct web server serving without PHP overhead
        $publicDir = public_path('storage/' . $relativeDir);
        if (! is_dir($publicDir)) {
            @mkdir($publicDir, 0755, true);
        }
        @copy($file->getRealPath(), $publicDir . '/' . $filename);

        return '/storage/' . $relativeDir . '/' . $filename;
    }

    /**
     * Detects if the given URL is a direct HTML5 video stream (MP4, WebM, MOV, OGG, or cloud stream).
     */
    public static function isDirectVideo(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }

        $clean = strtolower(trim((string) parse_url($url, PHP_URL_PATH)));

        if (Str::endsWith($clean, ['.mp4', '.webm', '.mov', '.ogg', '.m4v'])) {
            return true;
        }

        // Internal public storage paths
        if (str_starts_with($url, '/storage/') || str_contains($url, '/storage/case-studies/videos/')) {
            return true;
        }

        // Direct CDN streams (Cloudinary, AWS S3, BunnyCDN, etc.)
        if (preg_match('#(cloudinary|s3|amazonaws|digitaloceanspaces|bunnycdn|supabase|blob\.core\.windows\.net)#i', $url)) {
            return true;
        }

        return false;
    }

    /**
     * Generates a safe embed URL for YouTube, Vimeo, etc., if applicable.
     */
    public static function resolveEmbedUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $trimmed = trim($url);

        // YouTube Shorts or standard videos
        if (preg_match('#(?:youtube\.com/(?:watch\?v=|shorts/|embed/)|youtu\.be/)([\w\-]+)#i', $trimmed, $matches)) {
            return "https://www.youtube.com/embed/{$matches[1]}?autoplay=1&mute=0&controls=1&rel=0&playsinline=1";
        }

        // Vimeo
        if (preg_match('#vimeo\.com/(?:video/)?(\d+)#i', $trimmed, $matches)) {
            return "https://player.vimeo.com/video/{$matches[1]}?autoplay=1&muted=0&controls=1";
        }

        // Instagram Reels fallback
        if (preg_match('#instagram\.com/reel/([\w\-]+)#i', $trimmed, $matches)) {
            return "https://www.instagram.com/reel/{$matches[1]}/embed";
        }

        return null;
    }
}
