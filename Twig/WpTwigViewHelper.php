<?php
/**
 * Created by PhpStorm.
 * User: Joel Harkes
 * Date: 14-Oct-14
 * Time: 12:04
 */

namespace Twig;

use helpers\Setting;
use models\User;
use Singleton;
use Twig_Autoloader;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_SimpleFunction;


class WpTwigViewHelper extends Singleton {

    public $twig;

    // @var $template Twig_TemplateInterface
    public $template;
    public $templateData = array();

    /**
     * @param string $viewLocation
     * @param bool $debug
     */
    protected function __construct($viewLocation = null, $debug = true){
        require_once JIH_PATH.'/Twig/Twig/Autoloader.php';
        Twig_Autoloader::register();

        $loader = new Twig_Loader_Filesystem($viewLocation ?: JIH_PATH.'/views');
        $this->twig = new Twig_Environment($loader, array(
//            'cache' => JIH_PATH.'viewscache',
            'debug' => $debug
        ));
        if($debug)
            $this->twig->addExtension(new \Twig_Extension_Debug());
        $this->addFunction('get_sidebar');
        $this->addFunction('get_header');
        $this->addFunction('get_footer');
        $this->addFunction('get_template_part');
        $this->addFunction('get_search_form');
        $this->addFunction('wp_editor');


        $this->twig->addFunction(new Twig_SimpleFunction('tr', array($this, 'tr')));
        $this->twig->addFunction(new Twig_SimpleFunction('setting', array($this, 'setting')));
        $this->twig->addFunction(new Twig_SimpleFunction('language', array($this, 'extractLanguageCode')));

        $this->templateData['user'] = User::Current();
//        wp_get_current_commenter();
    }

    public function extractLanguageCode(){
        $split =  explode("_",get_locale());
        return $split[0];
    }

    public function tr($defaultText){
        return __($defaultText,'jih-schedular');
    }

    public function setting($key){
        return Setting::get($key);
    }

    /**
     * @param callable $callable
     */
    private function addFunction($callable){
        $func = new FunctionWrapper($callable);
        $func->add_to_twig($this->twig);
    }

    public function addTemplateData($key,$data){
        $this->templateData[$key] = $data;
    }

    /**
     * @param string $template
     * @return string html of template
     */
    public function Render($template = null,array $data = array()){
        $data = $this->mergeTemplateData($data);
        return $this->twig->loadTemplate($template ?: $this->templateData)->render($data);
    }

    public function mergeTemplateData(array $data,$overwrite = false){
        return $overwrite ? $data : array_merge($this->templateData,$data);
    }

}




