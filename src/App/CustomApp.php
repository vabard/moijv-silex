<?php

namespace App;

/**
 * Description of CustomApp
 *
 * @author vassilina
 */
class CustomApp extends \Silex\Application
{
    // on utilise les traits de silex 
    use \Silex\Application\SecurityTrait;
    use \Silex\Application\FormTrait;
    use \Silex\Application\MonologTrait;
    use \Silex\Application\TranslationTrait;
    use \Silex\Application\TwigTrait;
    use \Silex\Application\UrlGeneratorTrait;
}
