<?php

namespace kolevCustomized\MultilingualExtension\Listener;

use Behat\Behat\EventDispatcher\Event\BeforeStepTested;
use Behat\Behat\EventDispatcher\Event\StepTested;
use kolevCustomized\MultilingualExtension\ServiceContainer\MultilingualFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Behat\Gherkin\Node\StepNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;


class Step implements EventSubscriberInterface {

  /**
   * @var MultilingualFactory
   */
  private $multilingualFactory;

  /**
   * Step constructor.
   * @param MultilingualFactory $multilingualFactory
   */
  public function __construct(MultilingualFactory $multilingualFactory) {
    $this->multilingualFactory = $multilingualFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents()
  {
    return array(
      StepTested::BEFORE => ['beforeStep', 10]
    );
  }

  /**
   * @param BeforeStepTested $event
   */
  public function beforeStep(BeforeStepTested $event) {
    $step = $event->getStep();
    // print_r($this->multilingualFactory->getCurrentLag());
    if ($step->getText() =='I go to "/ar/user/login"') {
      $this->processStepArguments($step);
//      var_dump($step->getNodeType());
//      var_dump($step->getType());
//      var_dump($step->getText());
      //var_dump($step->getArguments());
      //die();
    }

    // die();
    // $this->processStepText($step);

  }

  /**
   * @param StepNode $step
   */
  private function processStepText(StepNode $step)
  {
    $reflectedStep = new \ReflectionObject($step);
    $textProperty = $reflectedStep->getProperty('text');
    $textProperty->setAccessible(true);

    $textProperty->setValue($step, $this->processor->doShortcode($textProperty->getValue($step)));
  }

  /**
   * @param StepNode $step
   */
  private function processStepArguments(StepNode $step) {
//    if (!$step->hasArguments()) {
//      return;
//    }

    $reflectedStep = new \ReflectionObject($step);
    var_dump($reflectedStep->getConstructor());
    var_dump($reflectedStep->getName());
    var_dump($reflectedStep->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED));
    var_dump($reflectedStep->getDefaultProperties());
    var_dump($reflectedStep->getProperties());

    $argumentProperty = $reflectedStep->getProperty('arguments');
    $argumentProperty->setAccessible(true);

    $argumentProperty->setValue($step, ['/en/user/login']);


//    $newArguments = [];
//    foreach ($step->getArguments() as $argument) {
//      if ($argument instanceof TableNode) {
//        $argument = $this->processTableNode($argument);
//      } else if ($argument instanceof PyStringNode) {
//        $argument = $this->processPyStringNode($argument);
//      }
//
//      $newArguments[] = $argument;
//    }
//
//
//
//    $reflectedStep = new \ReflectionObject($step);
//    $argumentProperty = $reflectedStep->getProperty('arguments');
//    $argumentProperty->setAccessible(true);
//
//    $argumentProperty->setValue($step, $newArguments);
  }

  /**
   * @param TableNode $table
   * @return TableNode
   */
  private function processTableNode(TableNode $table)
  {
    $processor = $this->processor;

    $newTable = array_map(
      function($row) use ($processor) {
        return array_map(
          function($value) use ($processor) {
            return $processor->doShortcode($value);
          },
          $row
        );
      },
      $table->getRows()
    );

    return new TableNode($newTable);
  }

  /**
   * @param PyStringNode $string
   * @return PyStringNode
   */
  private function processPyStringNode(PyStringNode $string)
  {
    $processor = $this->processor;

    return new PyStringNode(
      array_map(
        function($line) use ($processor) {
          return $processor->doShortcode($line);
        },
        $string->getStrings()
      ),
      $string->getLine()
    );
  }
}