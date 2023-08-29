<?php

namespace app\services\ReviewService;

use app\components\logger\LoggerComponent;
use app\dto\Review\ReviewExcelRequest;
use app\repositories\ReviewRepositories\ReviewRepository;
use Leaf\Http\Headers;
use Monolog\Logger;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReviewExcelService
{

    private ReviewRepository $reviewRepository;

    private LoggerComponent $loggerComponent;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
        $this->loggerComponent = app()->config('container')->get('logger');
    }

    public function run(ReviewExcelRequest $reviewExcelRequest)
    {

        $reviewsForExcel = $this->reviewRepository->getReviewsForExcel($reviewExcelRequest);
        if (!empty($reviewsForExcel)) {
            try {
                $sheet = new Spreadsheet();

                $titleStyle = [
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEEEEE']],
                ];

                $sheet->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Название')
                    ->setCellValue('B1', 'Тип')
                    ->setCellValue('C1', 'Рейтинг');

                $sheet->setActiveSheetIndex(0)->getStyle('A1:C1')->applyFromArray($titleStyle);


                $rowIndex = 2;
                foreach ($reviewsForExcel as $review) {
                    $sheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $rowIndex, $review['name'])
                        ->setCellValue('B' . $rowIndex, $this->getTranslatedType($review['type']))
                        ->setCellValue('C' . $rowIndex, $review['rating']);
                    $rowIndex++;
                }
                $writer = new Xlsx($sheet);
                Headers::set([
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'Content-Disposition' => 'attachment; filename="reviews.xlsx"',
                ]);
                ob_start();
                $writer->save('php://output');
                return ob_get_clean();
            } catch (Exception $e) {
                $this->loggerComponent->log(Logger::INFO, 'ReviewExcelService->run error: ' . $e->getMessage());
                return false;
            }
        } else {
            $this->loggerComponent->log(Logger::INFO, 'ReviewExcelService->run empty data:');
            return false;
        }
    }

    private function getTranslatedType(string $type): string
    {
        switch ($type) {
            case 'anime': return 'Аниме';
            case 'film': return 'Фильм';
            case 'serial': return 'Сериал';
            case 'book': return 'Книга';
            default: return 'Неверный тип';
        }
    }
}