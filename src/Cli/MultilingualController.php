<?php

namespace kolevCustomized\MultilingualExtension\Cli;

use Behat\Behat\Context\Suite\Setup\SuiteWithContextsSetup;
use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Behat\Behat\Hook\Call\BeforeStep;
use Behat\Behat\Hook\Scope\BeforeStepScope;
use Behat\Behat\Hook\Scope\StepScope;
use Behat\Testwork\Cli\Controller;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Behat\Testwork\Hook\Scope\SuiteScope;
use kolevCustomized\MultilingualExtension\ServiceContainer\MultilingualFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MultilingualController implements Controller {

  private $multiLingualFactory;

  private $currentLang;

  /**
   * Initializes controller.
   *
   * @param Translator $translator
   */
  public function __construct(MultilingualFactory $multiLingualFactory) {
//    $this->eventDispatcher = $eventDispatcher;
    $this->multiLingualFactory = $multiLingualFactory;
  }

  /**
   * {@inheritdoc}
   */
  public function configure(Command $command)
  {
    $command->addOption('--site-lang', null, InputOption::VALUE_REQUIRED,
      'Print output in particular language.'
    );
  }

  /**
   * {@inheritdoc}
   */
  public function execute(InputInterface $input, OutputInterface $output)
  {
    if (!$input->getOption('site-lang')) {
      return;
    }

    //$this->suiteWithContextsSetup->
    // print_r($this->eventDispatcher->getListeners(SuiteScope::BEFORE));
    $this->multiLingualFactory->setCurrentLang($input->getOption('site-lang'));
//    $this->eventDispatcher->addListener(ScenarioTested::BEFORE, array($this, 'setCurrentLanguageBeforeStep'), -999);
    // $this->eventDispatcher->addListener(SuiteScope::BEFORE, array($this, 'setCurrentLanguageBeforeSuite'), -999);
    // $this->translator->setLocale($input->getOption('lang'));
  }


  public function setCurrentLanguageBeforeStep(ScenarioTested $event) {
    // $step = $event->getSuite()
    // print_r($step->getArguments());
    print_r($event->getEnvironment()->getSuite());
    $suite = $event->getSuite();
    $settings = &$suite->getSettings();
    $settings['lang'] = $this->currentLang;
    $this->suiteWithContextsSetup->setupSuite($suite);
    // $event->getStep()->getKeyword();
    // $event->
  }

  public function setCurrentLanguageBeforeSuite(BeforeSuiteScope $event) {
    $settings = $event->getSuite()->getSettings();
    print "==========";
    print "Helllllo";
    print_r($settings);
  }

}