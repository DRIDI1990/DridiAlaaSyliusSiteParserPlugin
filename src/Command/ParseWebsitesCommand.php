<?php

namespace Dridialaa\SyliusSiteParserPlugin\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dridialaa\SyliusSiteParserPlugin\Entity\Website;
use Dridialaa\SyliusSiteParserPlugin\Service\WebsiteParserService;
use Doctrine\ORM\EntityManagerInterface;
use Cron\CronExpression;

class ParseWebsitesCommand extends Command
{
    protected static $defaultName = 'app:parse-websites';

    private $websiteParserService;
    private $entityManager;

    public function __construct(WebsiteParserService $websiteParserService, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->websiteParserService = $websiteParserService;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Parse websites based on their cron configuration.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $websites = $this->entityManager->getRepository(Website::class)->findAll();

        foreach ($websites as $website) {
            $cronExpression = $website->getCronExpression();

            if ($cronExpression && $this->shouldRun($cronExpression)) {
                $output->writeln(sprintf('Parsing website: %s', $website->getName()));
                $this->websiteParserService->parseWebsite($website);
            }
        }

        return Command::SUCCESS;
    }

    private function shouldRun(string $cronExpression): bool
    {
        $cron = CronExpression::factory($cronExpression);
        return $cron->isDue();
    }
}