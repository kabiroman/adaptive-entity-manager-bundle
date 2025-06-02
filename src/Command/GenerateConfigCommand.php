<?php

namespace Kabiroman\AdaptiveEntityManagerBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class GenerateConfigCommand extends Command
{
    protected static $defaultName = 'adaptive:generate-config';

    protected function configure(): void
    {
        $this->setDescription('Generates the adaptive_entity_manager.yaml configuration file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fs = new Filesystem();
        $source = __DIR__.'/../../config/packages/adaptive_entity_manager.yaml.dist';  // Adjust path if needed
        $target = dirname(__DIR__, 3).'/config/packages/adaptive_entity_manager.yaml';  // Path relative to the project

        if (!$fs->exists($source)) {
            $output->writeln('Template file not found.');

            return Command::FAILURE;
        }

        if (!$fs->exists($target)) {
            $fs->copy($source, $target, true);
            $output->writeln('Configuration file generated successfully.');

            return Command::SUCCESS;
        }

        $output->writeln('Configuration file already exists.');

        return Command::SUCCESS;
    }
} 