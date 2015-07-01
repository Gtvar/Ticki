<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Ticki\Core\Model\Board;
use Ticki\Core\Model\Cell;
use Ticki\Core\Strategy\StrategyManager;
use Ticki\Core\TicTac;
use Ticki\Core\Exception\Exception;
use Ticki\Core\Template\BoardTemplate;
use Symfony\Component\Console\Helper\DialogHelper;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

date_default_timezone_set('UTC');

$strategyManager = new StrategyManager();
$strategies = $strategyManager->getStrategiesNames();

$console = new Application();

/** @var \Symfony\Component\Console\Helper\DialogHelper $dialog */
$dialog = $console->getHelperSet()->get('dialog');

$console
    ->register('tip-tap-toe')
    ->setDefinition(array(
        new InputArgument('sideCount', InputArgument::REQUIRED, 'Count side of game'),
        new InputArgument('type', InputArgument::REQUIRED, sprintf("Select type of your cell. You can use '%s' or '%s'", Cell::TIC, Cell::TAC)),
        new InputArgument('strategy', InputArgument::REQUIRED, sprintf("Select strategy. You can use %s", implode(', ', $strategies))),
    ))
    ->setDescription('Displays the files in the given directory')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($dialog) {
            $sideCount = $input->getArgument('sideCount');
            $type = $input->getArgument('type');
            $strategy = $input->getArgument('strategy');

            try {

                $game = new TicTac($strategy, $sideCount, $type);
                $output->writeln(BoardTemplate::draw($game->getBoard()));

                for (;;) {

                    $position = $dialog->ask(
                        $output,
                        'Please select your number of you next step: ',
                        implode(', ', $game->getBoard()->getFreeCell())
                    );

                    $stage = $game->game($position);
                    $output->writeln(BoardTemplate::draw($game->getBoard()));

                    switch ($stage) {
                        case TicTac::STAGE_YOU_WIN :
                            $output->writeln("You Win!");

                            return;

                            break;

                    case TicTac::STAGE_YOU_LOST :
                            $output->writeln("You Win!");

                            return;

                            break;

                    case TicTac::STAGE_DEAD_HEAT :
                            $output->writeln("Game finish, but not have winner!");

                            return;

                            break;
                    }
                }

            } catch (Exception $e) {
                $output->writeln(sprintf("Error happend <error>%s</error>", $e->getMessage()));
            }
    })
;

$console->run();
