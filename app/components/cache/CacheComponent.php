<?php

namespace app\components\cache;

use app\dto\Review\GetReviewParams;
use app\entities\UserAuthEntity;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;

class CacheComponent
{

    const REVIEW_CACHE_TAG = 'review';

    public static function generateReviewCacheKey(GetReviewParams $params): string
    {
        $keyParts = [
            'reviews',
            UserAuthEntity::getInstance()->getUser()->getId(),
            $params->type,
            $params->name,
            $params->rating,
            $params->date,
            $params->page,
            $params->limit
        ];

        return md5(implode('_', $keyParts));
    }

    public static function getReviewCacheTag(): string
    {
        return self::REVIEW_CACHE_TAG . UserAuthEntity::getInstance()->getUser()->getId();
    }

    public static function invalidateCache(array $tags): void
    {
        self::getCache()->invalidateTags($tags);
    }

    public static function getCache(): FilesystemTagAwareAdapter
    {
        return app()->config('container')->get('cache');
    }
}