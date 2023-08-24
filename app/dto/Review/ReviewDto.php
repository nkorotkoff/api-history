<?php


namespace app\dto\Review;


use app\dto\BaseDto;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class ReviewDto extends BaseDto
{
    /**
     * @var int $id
     */
    public $id;

    /**
     * @var string $name
     */
    public $name;

    /**
     * @var string $description
     */
    public $review;

    /**
     * @var int $rating
     */
    public $rating;

    /**
     * @var string $type
     */
    public $type;

    /**
     * @var string $author
     */
    public $author;

    /**
     * @var string $author
     */
    public $whereStay;



    public function validate(): void
    {
        $validator = v::attribute('name', v::stringType()->notEmpty())
            ->attribute('review', v::stringType()->length(null, 255))
            ->attribute('rating', v::stringType())
            ->attribute('type', v::in(['film', 'serial', 'anime', 'book']))->notEmpty()
            ->attribute('id', v::optional(v::intType()))
            ->attribute('author', v::optional(v::stringType()))
            ->attribute('whereStay', v::optional(v::stringType()));

        try {
            $validator->assert($this);
        } catch (NestedValidationException $exception) {
            $this->error = $exception->getFullMessage();
        }
    }
}