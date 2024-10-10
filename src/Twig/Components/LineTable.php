<?php

namespace App\Twig\Components;

use App\Entity\Line;
use App\Repository\LineRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;


#[AsLiveComponent()]
class LineTable
{
    use DefaultActionTrait;
    public array $lines = [];

    public function __construct(private readonly LineRepository $lineRepository) {}
    public function getLines(): int
    {
        return rand(1, 100);
    }
}
