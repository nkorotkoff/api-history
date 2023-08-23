<?php


namespace app\entities;

use app\dto\Review\ReviewDto;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'reviews')]
class Review
{

    const ALL = 'all';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;


    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $rating;

    #[ORM\Column(type: 'string', length: 50, nullable: false)]
    private string $type;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private $user;


    #[ORM\Column(type: 'integer', nullable: false)]
    private int $user_id;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $created_at;

    #[ORM\Column(type: 'string', length: 255,)]
    private ?string $review;

    #[ORM\Column(type: 'string', length: 255,)]
    private ?string $author;

    public function saveReview(ReviewDto $reviewDto)
    {
        $userEntity = (UserAuthEntity::getInstance());
        $this->name = $reviewDto->name;
        $this->rating = $reviewDto->rating;
        $this->review = $reviewDto->review;
        $this->author = $reviewDto->author;
        if (empty($this->id)) {
            $this->created_at = time();
        }
        $this->type = $reviewDto->type;
        $this->user = $userEntity->getUser();
    }
}