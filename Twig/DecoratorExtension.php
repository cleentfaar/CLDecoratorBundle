<?php

namespace CL\Bundle\DecoratorBundle\Twig;

use CL\Decorator\DecoratorInterface;
use CL\Decorator\DelegatingDecorator;

class DecoratorExtension extends \Twig_Extension
{
    /**
     * @var DelegatingDecorator
     */
    protected $delegatingDecorator;

    /**
     * @param DelegatingDecorator $delegatingDecorator
     */
    public function __construct(DelegatingDecorator $delegatingDecorator)
    {
        $this->delegatingDecorator = $delegatingDecorator;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'twig_extension_decorator';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            'decorate' => new \Twig_Function_Method($this, 'decorate'),
        ];
    }

    /**
     * @param object|null $object
     *
     * @throws \InvalidArgumentException
     *
     * @return object
     */
    public function decorate($object)
    {
        if (null === $object) {
            return null;
        }

        if (!is_object($object)) {
            throw new \InvalidArgumentException(sprintf('You must pass an object to decorate, "%s" given', gettype($object)));
        }

        if ($object instanceof DecoratorInterface) {
            return $object;
        }

        return $this->delegatingDecorator->inject($object, $this->delegatingDecorator);
    }
}
