<?php

namespace App\Command;

use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class AdminUserQuestions
{
    public function __construct(
        private ValidatorInterface $validator
    ) {}

    public function createEmailQuestion(): Question
    {
        return (new Question('User email'))
            ->setValidator(
                Validation::createCallable(
                    $this->validator,
                    new Assert\NotBlank(),
                    new Assert\Email(),
                )
            )
            ->setMaxAttempts(10);
    }

    public function createPasswordQuestion(): Question
    {
        return (new Question('User password'))
            ->setHidden(true)
            ->setValidator(
                Validation::createCallable(
                    $this->validator,
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 6]),
                    new Assert\PasswordStrength(
                        minScore: Assert\PasswordStrength::STRENGTH_WEAK
                    ),
                )
            )
            ->setMaxAttempts(10);
    }

    public function createFirstnameQuestion(): Question
    {
        return (new Question('User first name'))
            ->setValidator(
                Validation::createCallable(
                    $this->validator,
                    new Assert\NotBlank(),
                )
            )
            ->setMaxAttempts(10);
    }

    public function createLastnameQuestion(): Question
    {
        return (new Question('User last name'))
            ->setValidator(
                Validation::createCallable(
                    $this->validator,
                    new Assert\NotBlank(),
                )
            )
            ->setMaxAttempts(10);
    }
}
