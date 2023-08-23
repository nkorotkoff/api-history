<?php


namespace app\services\ReviewService;


use app\components\cache\CacheComponent;
use app\components\logger\LoggerComponent;
use app\dto\Review\ReviewDto;
use app\entities\Review;
use app\Exceptions\ErrorSavingDataInDataBase;
use app\repositories\ReviewRepositories\ReviewRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use Monolog\Logger;

class ReviewService
{
    private EntityManager $entityManager;
    private LoggerComponent $loggerComponent;

    private ReviewRepository $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->entityManager = app()->config('entityManager');
        $this->loggerComponent = app()->config('container')->get('logger');
        $this->reviewRepository = $reviewRepository;
    }

    public function createReview(ReviewDto $review): bool
    {
        $this->loggerComponent->log(Logger::INFO, 'ReviewService->createReview run with params: ' . json_encode($review));
        $reviewEntity = new Review();
        try {
            $this->loggerComponent->log(Logger::INFO, 'ReviewService->createReview saving data in db');
            $reviewEntity->saveReview($review);
            $this->entityManager->persist($reviewEntity);
            $this->entityManager->flush();
            CacheComponent::invalidateCache([CacheComponent::getReviewCacheTag()]);
            return true;
        } catch (Exception $exception) {
            $this->loggerComponent->log(Logger::ERROR, 'ReviewService->createReview error saving data in db error:' . $exception->getMessage());
            return false;
        }
    }

    public function deleteReview(int $reviewId): bool
    {
        $this->loggerComponent->log(Logger::ERROR, 'ReviewService->deleteReview run with reviewId:' . $reviewId);
        $review = $this->reviewRepository->getReviewById($reviewId);
        if (empty($review)) {
            $this->loggerComponent->log(Logger::ERROR, 'ReviewService->deleteReview error: review with id ' . $reviewId  . ' not exist');
            return false;
        }
        try {
            $this->entityManager->remove($review);
            $this->entityManager->flush();
            $this->loggerComponent->log(Logger::ERROR, 'ReviewService->deleteReview success');
            CacheComponent::invalidateCache([CacheComponent::getReviewCacheTag()]);
            return true;
        } catch (ORMException $e) {
            $this->loggerComponent->log(Logger::ERROR, 'ReviewService->deleteReview error: ' . $e->getMessage());
            return false;
        }

    }

    public function updateReview(ReviewDto $review): bool
    {
        $this->loggerComponent->log(Logger::ERROR, 'ReviewService->updateReview run with params: ' . json_encode($review));
        $reviewEntity = $this->reviewRepository->getReviewById($review->id);
        if (empty($reviewEntity)) {
            $this->loggerComponent->log(Logger::ERROR, 'ReviewService->updateReview error: review with id ' . $review->id  . ' not exist');
            return false;
        }

        try {
            $reviewEntity->saveReview($review);
            $this->entityManager->flush();
            CacheComponent::invalidateCache([CacheComponent::getReviewCacheTag()]);
            $this->loggerComponent->log(Logger::ERROR, 'ReviewService->updateReview success');
            return true;
        } catch (ORMException $e) {
            $this->loggerComponent->log(Logger::ERROR, 'ReviewService->updateReview error: ' . json_encode($e->getMessage()) );
            return false;
        }
    }
}