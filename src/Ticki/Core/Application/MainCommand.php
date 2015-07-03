<?php

namespace Ticki\Core\Application;

use Symfony\Component\Console\Command\Command;
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
 * Class MainCommand
 */
class MainCommand extends Command
{
	protected function configure()
	{
		$strategyManager = new StrategyManager();
		$strategies = $strategyManager->getStrategiesNames();

		$this
			->setName('tic-tac-toe')
			->setDefinition(array(
					new InputArgument('sideCount', InputArgument::OPTIONAL, sprintf("Count side of game. You can use one for those: %s", implode(", ", Board::$availableSideCount)), Board::DEFAULT_SIDE_SIZE),
					new InputArgument('type', InputArgument::OPTIONAL, sprintf("Select type of your cell. You can use '%s' or '%s'", Cell::TIC, Cell::TAC), Cell::TAC),
					new InputArgument('strategy', InputArgument::OPTIONAL, sprintf("Select strategy. You can use %s", implode(', ', $strategies)), 'intelligent'),
                    new InputArgument('winCount', InputArgument::OPTIONAL, sprintf("How point need to win. Can be less or equal countSide: %s", implode(", ", Board::$availableSideCount)), Board::DEFAULT_SIDE_SIZE),
				))
			->setDescription('Tic tac toe game')
		;
	}

	/**
	 * Main command
	 *
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 *
	 * @return int|null|void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$sideCount = $input->getArgument('sideCount');
		$type = $input->getArgument('type');
		$strategy = $input->getArgument('strategy');
		$winCount = $input->getArgument('winCount');

		/** @var \Symfony\Component\Console\Helper\DialogHelper $dialog */
		$dialog = $this->getHelper('dialog');

		try {

			$game = new TicTac($strategy, $sideCount, $type, $winCount);
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
						$output->writeln("<question>You Win!</question>");

						return;
						break;

					case TicTac::STAGE_YOU_LOST :
						$output->writeln("<question>You Lost!</question>");

						return;
						break;

					case TicTac::STAGE_DEAD_HEAT :
						$output->writeln("<question>Game finish, but not have winner!</question>");

						return;
						break;
				}
			}

		} catch (Exception $e) {
			$output->writeln(sprintf("Error happend <error>%s</error>", $e->getMessage()));
		}
	}
}
