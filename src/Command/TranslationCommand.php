<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class TranslationCommand extends Command
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct();

        $this->translator = $translator;
    }

    protected function configure() : void
    {
        $this->setName('translation');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : ?int
    {
        $output->writeln('Converting sms to English');

        $output->writeln(sprintf(
            '<comment>%s</comment>',
            $this->translator->trans('sms', ['%code%' => '1234'], 'messages', 'en')
        ));

        $output->writeln('Converting sms to French');

        $output->writeln(sprintf(
            '<comment>%s</comment>',
            $this->translator->trans('sms', ['%code%' => '1234'], 'messages', 'fr')
        ));

        return 0;
    }
}

