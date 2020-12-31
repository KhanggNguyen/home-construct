<?php

namespace HomeConstruct\BuildBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\ConfirmationQuestion;


class InitCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    protected $em;

    protected function configure()
    {
        // Name and description for app/console command
        $this
            ->setName('projet:init')
            ->addArgument('reset', InputArgument::OPTIONAL, 'Reset route')
            ->setDescription('Reset all route');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $application = $this->getApplication();
        $application->setAutoExit(false);

        $output->writeln([
            '===================================================',
            '*********        Dropping DataBase        *********',
            '===================================================',
            '',
        ]);

        $options = array('command' => 'doctrine:database:drop',"--force" => true);
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

        $output->writeln([
            '===================================================',
            '*********        Creating DataBase        *********',
            '===================================================',
            '',
        ]);

        $options = array('command' => 'doctrine:database:create', "--if-not-exists" => true);
        $application->run(new ArrayInput($options));

        $em = $this->getContainer()->get('doctrine')->getManager();
        $connection = $em->getConnection();
        $connection->getConfiguration()->setSQLLogger(null);

        $checkSchema = $connection->prepare("SELECT schema_name FROM information_schema.schemata WHERE schema_name = :name");
        $checkSchema->bindValue('name','HomeConstruct');
        $checkSchema->execute();
        $checkSchemaResults = $checkSchema->fetchAll();

        if (isset($checkSchemaResults) && !empty($checkSchemaResults)) {

            $output->writeln([
                '===================================================',
                '*********        Drop Tables              *********',
                '===================================================',
                '',
            ]);

//        $options = array('command' => 'doctrine:schema:drop',"--full-database" => true);
            $options = array('command' => 'doctrine:schema:drop', "--force" => true);
            $application->run(new ArrayInput($options));

            $statement = $connection->prepare("DROP SCHEMA HomeConstruct");
            $statement->execute();

            $statement = $connection->prepare("CREATE SCHEMA HomeConstruct");
            $statement->execute();
        }

        if (empty($checkSchemaResults)) {
            $statement = $connection->prepare("CREATE SCHEMA HomeConstruct");
            $statement->execute();
        }

        $output->writeln([
            '===================================================',
            '*********         Updating Schema         *********',
            '===================================================',
            '',
        ]);

        //Create de Schema
        $options = array('command' => 'doctrine:schema:update', "--force" => true);
        $application->run(new ArrayInput($options));

        $output->writeln([
            '===================================================',
            '*********          Load Fixtures          *********',
            '===================================================',
            '',
        ]);

        //Loading Fixtures
        $options = array('command' => 'doctrine:fixtures:load', "--no-interaction" => true);
        $application->run(new ArrayInput($options));

    }

}