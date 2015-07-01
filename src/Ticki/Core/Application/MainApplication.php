<?php

namespace Ticki\Core\Application;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;

class MainApplication extends Application
{
	/**
	 * @inheritdoc
	 */
	protected function getCommandName(InputInterface $input)
	{
		return 'tic-tac-toe';
	}

	/**
	 * @inheritdoc
	 */
	protected function getDefaultCommands()
	{
		$defaultCommands = parent::getDefaultCommands();
		$defaultCommands[] = new MainCommand();

		return $defaultCommands;
	}

	/**
	 * @inheritdoc
	 */
	public function getDefinition()
	{
		$inputDefinition = parent::getDefinition();
		$inputDefinition->setArguments();

		return $inputDefinition;
	}
}