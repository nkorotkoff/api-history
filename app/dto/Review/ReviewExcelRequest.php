<?php

namespace app\dto\Review;

use app\dto\BaseDto;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class ReviewExcelRequest extends BaseDto
{

    /**
     * @var string $type
     */
    public $type;

    /**
     * @var string $type
     */
    public $date;


    public function validate(): void
    {
        $validator = v::attribute('type', v::optional(v::stringType()))
            ->attribute('date', v::optional(v::stringType()));

        try {
            $validator->assert($this);
        } catch (NestedValidationException $exception) {
            $this->error = $exception->getFullMessage();
        }
    }
}