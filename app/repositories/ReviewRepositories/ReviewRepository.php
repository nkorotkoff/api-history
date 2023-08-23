<?php


namespace app\repositories\ReviewRepositories;


use app\components\cache\CacheComponent;
use app\components\logger\LoggerComponent;
use app\dto\Review\GetReviewParams;
use app\entities\Review;
use app\entities\UserAuthEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Monolog\Logger;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class ReviewRepository
{
    const NEEDED_COUNT_OF_DATES = 2;

    const LIMIT = 10;

    const SECOND_TIME = ' 23:59';

    private EntityManager $entityManager;
    private EntityRepository $reviewRepository;

    private LoggerComponent $loggerComponent;

    public function __construct()
    {
        $this->entityManager = app()->config('entityManager');
        $this->reviewRepository = $this->entityManager->getRepository(Review::class);
        $this->loggerComponent = app()->config('container')->get('logger');
    }

    private function getReview(GetReviewParams $params): array
    {
        $query = $this->reviewRepository->createQueryBuilder('r');

        $query->where('r.user_id = :userId')
            ->setParameter('userId', UserAuthEntity::getInstance()->getUser()->getId());

        if (!empty($params->type) && $params->type !== Review::ALL) {
            $query->andWhere('r.type = :typeParam')
                ->setParameter('typeParam', $params->type);
        }

        if (!empty($params->name)) {
            $query->andWhere('r.name LIKE :nameParam')
                ->setParameter('nameParam', '%' . $params->name . '%');
        }

        if (!empty($params->rating)) {
            $query->andWhere('r.rating = :ratingParam')
                ->setParameter('ratingParam', $params->rating);
        }

        if (!empty($params->date)) {
            $dates = explode('-', $params->date);
            if (!empty($dates) && is_array($dates) && count($dates) === self::NEEDED_COUNT_OF_DATES) {
                $query->andWhere('r.created_at BETWEEN :startDate AND :endDate')
                    ->setParameter('startDate', strtotime(trim($dates[0])))
                    ->setParameter('endDate', strtotime(trim($dates[1] . self::SECOND_TIME)));
            }
        }

        $queryCount = clone $query;
        $totalCount = $queryCount->select('COUNT(r.id)')->getQuery()->getSingleScalarResult();

        $page = $params->page ?? 1;

        $limit = $params->limit ?? self::LIMIT;

        $offset = ($page - 1) * $limit;
        $query->setFirstResult($offset)
            ->setMaxResults($limit);

        $results = $query->getQuery()->getArrayResult();


        $data = [
            'data' => $results,
            'total' => ceil($totalCount / $limit),
            'page' => $page,
            'limit' => $limit,
        ];


        return $data;
    }

    public function getCachedReview(GetReviewParams $params)
    {

        $cache = CacheComponent::getCache();

        $this->loggerComponent->log(Logger::INFO, 'ReviewRepository->getReview run with params:' . json_encode($params));

        $cacheKey = CacheComponent::generateReviewCacheKey($params);

        $cachedData = $cache->get($cacheKey, function (ItemInterface $item) use ($params): array {
            $item->tag(CacheComponent::getReviewCacheTag());
            return $this->getReview($params);
        });

        $this->loggerComponent->log(Logger::INFO, 'ReviewRepository->getReview success, data:' . json_encode($cachedData));

        return $cachedData;
    }


    /**
     * @param int $reviewId
     * @return Review|object|null
     */
    public function getReviewById(int $reviewId)
    {
        return $this->reviewRepository->findOneBy(['id' => $reviewId]);
    }

}