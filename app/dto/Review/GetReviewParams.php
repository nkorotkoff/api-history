<?php


namespace app\dto\Review;


use app\dto\BaseDto;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class GetReviewParams extends BaseDto
{
    public $name;

    public $date;

    public $type;

    public $rating;

    public $page;

    public $limit;

    public function validate(): void
    {
        $validator = v::attribute('name', v::optional(
            v::stringType())
        )
            ->attribute('date', v::optional(
                v::stringType())
            )
            ->attribute('rating', v::optional(
                v::intType()->between(1, 5))
            )
            ->attribute('page', v::optional(
                v::stringType())
            )
            ->attribute('limit', v::optional(
                v::intType())
            )
            ->attribute('type', v::optional(
                v::in(['film', 'serial', 'anime', 'book']))
            );

        try {
            $validator->assert($this);
        } catch (NestedValidationException $exception) {
            $this->error = $exception->getFullMessage();
        }
    }
}