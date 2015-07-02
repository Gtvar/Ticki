<?php

use Symfony\Component\Console\Tester\CommandTester;
use Behat\Behat\Context\Context;
use PHPUnit_Framework_Assert as Assertions;
use PHPUnit_Framework_TestCase as TestCase;
use Ticki\Core\Application\MainApplication;
use Ticki\Core\Application\MainCommand;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Input\InputArgument;

class CommandContext extends TestCase implements Context
{
    /**
     * @var Ticki\Core\Application\MainApplication
     */
    private $application;
    /**
     * @var CommandTester
     */
    private $tester;

    /**
     * @var StreamOutput
     */
    protected $output;

    protected $strategy;

    protected $exitCode;

    public function __construct()
    {
        $this->application = new MainApplication();
        $this->application->add(new MainCommand());
    }

    /**
     * @When /^I run "([^"]*)" command with interactive set: "([^"]*)"$/
     */
    public function iRunCommandWithInteractive($name, $set)
    {
        $command = $this->application->find($name);

        $set = explode(',', $set);
        $count = 0;

        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('ask'));
        $dialog->expects($this->any())
            ->method('ask')
            ->will($this->returnCallback(function () use ($set, &$count) {
                return $set[$count++];
            }));

        $command->getHelperSet()->set($dialog, 'dialog');

        $this->tester = new CommandTester($command);
        $this->exitCode = $this->tester->execute(array('strategy' => $this->strategy), array('interactive' => false));
    }

    /**
     * @When /^strategy is "([^"]*)"$/
     */
    public function strategy($strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @Then /^I should see$/
     */
    public function iShouldSee(PyStringNode $string)
    {
        Assertions::assertEquals($string->getRaw(), $this->tester->getDisplay());
    }

    /**
     * @Then /^The command exit code should be (\d+)$/
     */
    public function theCommandExitCodeShouldBe($exitCode)
    {
        Assertions::assertEquals($exitCode, $this->exitCode);
    }

    /**
     * @Then /^After finish game i see text "([^"]*)"$/
     */
    public function finishText($text)
    {
        Assertions::assertTrue(strpos($this->tester->getDisplay(), $text) !== false);
    }
}
