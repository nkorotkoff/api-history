<?php


namespace app\controllers;


use app\dto\Review\GetReviewParams;
use app\dto\Review\ReviewDto;
use app\dto\Review\ReviewExcelRequest;
use app\repositories\ReviewRepositories\ReviewRepository;
use app\Requests\ErrorRequest;
use app\Requests\ResponseCodes;
use app\Requests\SuccessResponse;
use app\services\ReviewService\ReviewExcelService;
use app\services\ReviewService\ReviewService;
use Leaf\Http\Headers;
use Psr\Container\ContainerInterface;

class ReviewController extends Controller
{

    private ReviewService $reviewService;
    private ReviewRepository $reviewRepository;

    private ReviewExcelService $reviewExcelService;

    public function __construct()
    {
        parent::__construct();
        /** @var ContainerInterface $container */
        $container = app()->config('container');
        $this->reviewService = $container->get(ReviewService::class);
        $this->reviewRepository = $container->get(ReviewRepository::class);
        $this->reviewExcelService = $container->get(ReviewExcelService::class);
    }

    public function createReviewManually()
    {
        $reviewData = new ReviewDto($this->request->body());
        if ($reviewData->hasError()) {
            $this->response->json(ErrorRequest::setErrorException($reviewData->error), 500);
        }
        $result = $this->reviewService->createReview($reviewData);
        if ($result) {
            $this->response->json(SuccessResponse::setData(ResponseCodes::OK, $result));
        } else {
            $this->response->json(ErrorRequest::setErrorWithCode(ResponseCodes::ERROR_SAVING_DATA_IN_DATABASE));
        }
    }

    public function updateReview()
    {
        $reviewData = new ReviewDto($this->request->body());
        if ($reviewData->hasError()) {
            $this->response->json(ErrorRequest::setErrorException($reviewData->error), 500);
        }
        $result = $this->reviewService->updateReview($reviewData);
        if ($result) {
            $this->response->json(SuccessResponse::setData(ResponseCodes::OK, $result));
        } else {
            $this->response->json(ErrorRequest::setErrorWithCode(ResponseCodes::ERROR_SAVING_DATA_IN_DATABASE));
        }
    }

    public function getReviews()
    {
        $getReviewParams = new GetReviewParams($this->request->body());
        $this->response->json(SuccessResponse::setData(ResponseCodes::OK ,$this->reviewRepository->getCachedReview($getReviewParams)));
    }

    public function deleteReview()
    {
        $reviewId = $this->request->body()['review_id'] ?? null;
        if (empty($reviewId)) {
            $this->response->json(ErrorRequest::setErrorException('review_id can not be empty'), 500);
        }
        $result = $this->reviewService->deleteReview($reviewId);
        if (!$result) {
            $this->response->json(ErrorRequest::setErrorException('Error deleting review'));
        }

        $this->response->json(SuccessResponse::setData(ResponseCodes::OK, $result));
    }

    public function getReviewsListAsExcel()
    {
        $reviewExcelRequest = new ReviewExcelRequest($this->request->body());

        $result = $this->reviewExcelService->run($reviewExcelRequest);
        if ($result) {
            echo base64_encode($result);
        } else {
            $this->response->json(ErrorRequest::setErrorWithCode(ResponseCodes::ERROR_TO_PARSE_EXCEL));
        }

    }
}