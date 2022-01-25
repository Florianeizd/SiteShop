<?php

namespace App\Command;

use App\Entity\User;
use App\Service\AlertServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:user-admin',
    description: 'Création d\'un utilisateur admin',
)]
class UserAdminCommand extends Command
{
    private $entityManagerInterface;
    private $userPasswordHasherInterface;

    public function __construct(string $name = null, EntityManagerInterface $entityManagerInterface, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        parent::__construct($name);
        $this->entityManagerInterface=$entityManagerInterface;
        $this->userPasswordHasherInterface=$userPasswordHasherInterface;    
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'email de l\'utilisateur')
            ->addArgument('password', InputArgument::REQUIRED, 'password de l\'utilisateur')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        if (!$email && !$password) {
            $io->error('The command requires the email and password to create the user. Please check the information.');
            return Command::FAILURE;
        }

        $user=new User();
        $user->setPassword($this->userPasswordHasherInterface->hashPassword($user, $password));
        $user->setEmail($email);
        $user->setRoles([User::ROLE_ADMIN]);
        $user->setIsVerified(true);
        
        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->flush();

        $io->success('The user has been successfully created.');


        return Command::SUCCESS;
    }
}
