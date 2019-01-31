<?php
/**
 * @author Toni Kolev, <kolev@toni-kolev.com>
 */
namespace kolevCustomized\MultilingualExtension\ServiceContainer;

use Behat\Behat\EventDispatcher\ServiceContainer\EventDispatcherExtension;
use Behat\EnvironmentLoader;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Behat\Testwork\Cli\ServiceContainer\CliExtension;
use Behat\Testwork\Suite\ServiceContainer\SuiteExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * Class MultilingualExtension.
 *
 * @package Behat\MultilingualExtension\ServiceContainer
 */
class MultilingualExtension implements Extension
{

    const MANAGER_ID = 'miteshmap_multilingual';

    /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return 'multilingual';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config) {
      $this->loadStepListner($container);
      $this->loadMultilingualFactory($container);
      $this->loadMultilingualController($container);
      $loader = new EnvironmentLoader($this, $container, $config);
      $loader->load();
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
    }

    private function loadMultilingualFactory(ContainerBuilder $container) {
      //$definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG, array('priority' => 0));
      $container->setDefinition(
        'miteshmap_multilingual.factory',
        new Definition('kolevCustomized\MultilingualExtension\ServiceContainer\MultiLingualFactory')
      );
    }

  /**
     * Loads multilingual controller.
     *
     * @param ContainerBuilder $container
     */
    private function loadMultilingualController(ContainerBuilder $container) {
      $definition = new Definition('kolevCustomized\MultilingualExtension\Cli\MultilingualController', array(
        //new Reference(EventDispatcherExtension::DISPATCHER_ID),
        new Reference('miteshmap_multilingual.factory'),
      ));
      $definition->addTag(CliExtension::CONTROLLER_TAG, array('priority' => 800));
      $container->setDefinition(CliExtension::CONTROLLER_TAG . '.miteshmap_multilingual', $definition);
    }

    private function loadStepListner(ContainerBuilder $container) {
      $definition = new Definition('kolevCustomized\MultilingualExtension\Listener\Step', [
        new Reference('miteshmap_multilingual.factory')
      ]);
      $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG, array('priority' => 0));
      $container->setDefinition('miteshmap_multilingual.listener.step', $definition);
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $config = $builder->children();
        $config->scalarNode('default_language')->end();
        $config->arrayNode('translations')->prototype('scalar')->end();
        $config->end();
    }
}
