<?php
/**
 * @author Toni Kolev, <kolev@toni-kolev.com>
 */
namespace kolevCustomized\MultilingualExtension\ServiceContainer;

use Behat\Behat\Gherkin\ServiceContainer\GherkinExtension;
use Behat\EnvironmentLoader;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Behat\Testwork\Specification\ServiceContainer\SpecificationExtension;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Class MultilingualExtension.
 *
 * @package Behat\MultilingualExtension\ServiceContainer
 */
class MultilingualExtension implements Extension
{
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
    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new EnvironmentLoader($this, $container, $config);
        $loader->load();
        $this->loadDecoratedFilesystemFeatureLocator($container);
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
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

    /**
     * Loads filesystem feature locator.
     *
     * @param ContainerBuilder $container
     */
    /*private function loadFilesystemFeatureLocator(ContainerBuilder $container) {
      $definition = new Definition('kolevCustomized\MultilingualExtension\Specification\Locator\FilesystemFeatureLocator', array(
        new Reference(GherkinExtension::MANAGER_ID),
        '%paths.base%'
      ));
      $definition->addTag(SpecificationExtension::LOCATOR_TAG, array('priority' => 600));
      $container->setDefinition(SpecificationExtension::LOCATOR_TAG . '.alshaya_custom_feature_iterator', $definition);
    }*/

    private function loadDecoratedFilesystemFeatureLocator(ContainerBuilder $container) {
      $definition = new Definition('kolevCustomized\MultilingualExtension\Specification\Locator\FilesystemFeatureLocator', array(
        new Reference(GherkinExtension::MANAGER_ID),
        '%paths.base%'
      ));
      $definition->addTag(SpecificationExtension::LOCATOR_TAG, array('priority' => 600));
      $definition->setDecoratedService(SpecificationExtension::LOCATOR_TAG . '.filesystem_feature', SpecificationExtension::LOCATOR_TAG . '.filesystem_feature.inner', 9);
      $container->setDefinition(SpecificationExtension::LOCATOR_TAG . '.alshaya_custom_feature_iterator', $definition);
    }
}
