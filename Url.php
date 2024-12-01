<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;

class Url extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','original_url', 'short_url', 'copy_count'];

    /**
     * Generate a short URL for the given original URL.
     *
     * @param string $originalUrl
     * @return Url
     */
    public static function shortenUrl($originalUrl)
    {
        $url = self::create([
            'original_url' => $originalUrl,
            'copy_count' => 0, // Initialize copy count to 0
        ]);

        // Generate a short URL using Hashids and save it
        $url->short_url = Hashids::encode($url->id);
        $url->save();

        return $url;
    }

    /**
     * Increment the copy count for the URL.
     *
     * @return void
     */
    public function incrementCopyCount()
    {
        $this->increment('copy_count');
    }

    /**
     * Retrieve the original URL using the short URL.
     *
     * @param string $shortUrl
     * @return Url|null
     */
    public static function getOriginalUrl($shortUrl)
    {
        $decoded = Hashids::decode($shortUrl);

        if (empty($decoded)) {
            return null;
        }

        $urlId = $decoded[0];
        return self::find($urlId);
    }
}
