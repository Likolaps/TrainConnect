<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:CreateAdmin',
    description: 'Add a short description for your command',
)]
class AdminUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly ValidatorInterface $validator,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly AdminUserQuestions $questions

    ) {
        parent::__construct();
    }

    protected function configure(): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Add a new admin user

        //ask for first name
        $firstName = $io->askQuestion($this->questions->createFirstnameQuestion());
        //ask for last name
        $lastName = $io->askQuestion($this->questions->createLastnameQuestion());
        //ask for email
        $email = $io->askQuestion($this->questions->createEmailQuestion());



        //ask for password
        $password = $io->askQuestion($this->questions->createPasswordQuestion());
        // set role to admin
        $role = 'ROLE_ADMIN';

        // create new user
        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $user->setPassword($this->hasher->hashPassword($user, $password));
        $user->setRoles([$role]);
        $user->setVerified(true);

        // check if user is valid before saving

        $violations = $this->validator->validate($user);
        if (0 < $violations->count()) {
            $io->error($violations);
        } else {
            $this->manager->persist($user);
            $this->manager->flush();
            $io->success(sprintf('User %s has been inserted', $user->getEmail()));
        }


        $io->success('User admin created successfully');

        return Command::SUCCESS;
    }
}
