<?php
/**
 * @author Toni Kolev, <kolev@toni-kolev.com>
 */
namespace kolevCustomized\MultilingualExtension\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;

/**
 * Class MultilingualContextInitializer.
 *
 * @package Behat\MultilingualExtension\Context
 */
class MultilingualContextInitializer implements ContextInitializer
{
    /**
     * Parameters of MultilingualExtension.
     *
     * @var array
     */
    private $multilingual_parameters = [];

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->multilingual_parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function initializeContext(Context $context)
    {
        if ($context instanceof MultilingualContextInterface) {
            $context->setMultilingualParameters($this->multilingual_parameters);
        }
    }
}
