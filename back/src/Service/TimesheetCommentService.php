<?php

namespace App\Service;

use App\Entity\TimesheetComment;
use App\Enum\Entity\CommentStatusEnum;
use Doctrine\ORM\EntityManagerInterface;

readonly class TimesheetCommentService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function turnCommentRead(array $comments, bool $flush = false): void
    {
        /** @var TimesheetComment $comment */
        foreach ($comments as $comment) {
            $comment->setStatus(CommentStatusEnum::READ);
            $this->entityManager->persist($comment);
        }

        if ($flush) {
            $this->entityManager->flush();
        }
    }
}
