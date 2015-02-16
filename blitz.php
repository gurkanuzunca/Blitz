#!/usr/bin/env php
<?php

// Composer Autoload.
require_once 'vendor/autoload.php';

use Library\Support\Blitz;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


$app = new Blitz();


class Co extends Command
{
    protected function configure()
    {
        $this
            ->setName('install')
            ->setDescription('Module yukle.')
            ->addArgument('module', InputArgument::REQUIRED, 'Module ismini girin.')
            ->addOption('backup', null, InputOption::VALUE_NONE, 'Dosyalari yedekle.')
            ->addOption('db', null, InputOption::VALUE_NONE, 'Database olustur.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $module = $input->getArgument('module');
        $backup = false;
        $db = false;


        if ($input->getOption('backup')) {
            $backup = true;
        }
        if ($input->getOption('db')) {
            $db = true;
        }


        $installPath = "App\\Modules\\$module\\Installer\\Install";
        $installer = new $installPath($module);

        $installer->checkRequirements();
        if ($db) {
            $installer->checkTables();
        }





        if ($installer->hasMessage()) {
            foreach ($installer->getMessages() as $massage) {
                $output->writeln($massage);
            }

            $output->writeln('Yukleme basarisiz.');
        } else {
            if ($db) {
                $installer->installTables();
                $output->writeln('Veritabani olusturuldu.');
            }
            if ($backup) {
                $installer->copySources();
                $output->writeln('Yedekleme yapildi.');
            }
            $output->writeln('Yukleme basarili.');
        }

    }

}

use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new Co);
$application->run();