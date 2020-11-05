<?php


namespace DctT\TracabilityBundle\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Tracable {
    public static $name = "Tracable";

    /**
     * @var string
     */
    public $ressourceName;
}