<?php

namespace MyApp;

session_start();

use Phalcon\Di\Injectable;
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Translate\InterpolatorFactory;
use Phalcon\Translate\TranslateFactory;

class Locale extends Injectable
{

    public function getTranslator(): NativeArray
    {


        $di = $this->getDI();

        $language = $_SESSION['lang'];
        $messages = [];

        $translationFile = APP_PATH . '/messages/' . $language . '.php';

        if (true !== file_exists($translationFile)) {
            $translationFile = APP_PATH . '/messages/en-GB.php';
        }

        require $translationFile;



        $interpolator = new InterpolatorFactory();
        $factory      = new TranslateFactory($interpolator);

        return $factory->newInstance(
            'array',
            [
                'content' => $messages,
            ]
        );
    }
}
