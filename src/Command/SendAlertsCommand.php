<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\AlertRepository;
use App\Repository\DealRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendAlertsCommand extends Command
{
    protected static $defaultName = 'app:send-alerts';
    private $ar;
    private $dr;
    private $mailer;

    public function __construct(AlertRepository $ar, DealRepository $dr, MailerInterface $mailer){
        $this->ar = $ar;
        $this->dr = $dr;
        $this->mailer = $mailer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Send deals to the user by emails if they should be notified.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $alerts = $this->ar->findBy(['isNotified' => true]);
        foreach ($alerts as $alert) {
            $message = '';
            $user = $alert->getUser();

            $email = (new Email())
            ->from('hello@example.com')
            ->to($user->getEmail())
            ->subject('Pactum Notifications !');

            
            $deals = $this->dr->getDealContains($alert->getkeyWord(), $alert->getminTemperature());
            
            if(empty($deals)){
                $message .= '<p>No alert today (T_T)...</p>';
            }
            else{
                $message .= '<ul>';
                foreach ($deals as $deal) {
                    $message .= '<li>' .$deal->getTitle(). '</li>';
                }
                $message .= '</ul>';
            }


            $email->html($message);
            $this->mailer->send($email);
        }
        $io = new SymfonyStyle($input, $output);
        $io->success('Done');

        return 0;
    }
}
