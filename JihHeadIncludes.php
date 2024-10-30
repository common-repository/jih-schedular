<?php
use helpers\Css;
use helpers\Js;

/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 01-Aug-15
 * Time: 22:40
 */

class JihHeadIncludes {
    protected $resourceDirectory = 'resources/';

    public function __construct(){
        //admin|
        $this->AddCss('css/admin',"jih-plugin",array(),false,true);
        $this->AddCss('lib/datetime-picker/jquery.datetimepicker',"jquery-datetime-picker",array(),false,true);
        //wp
        $this->AddCss('css/plugin',"plugin",array(),true,false);
        $this->AddCss('lib/bootstrap-3.2.0/css/bootstrap',"bootstrap",array(),true,false);


        $this->AddJs('lib/jquery-query','jquery-query',array('jquery'),true,true);
        $this->AddJs('lib/datetime-picker/jquery.datetimepicker','jquery-datetime-picker',array(),false,true);

        $this->AddJs('lib/bootstrap-3.2.0/js/bootstrap','bootstrap',array('jquery'),true,true);
        $this->AddJs('lib/moment-2.8.3/moment-with-locales','moment',array('jquery'),true,false);
        $this->AddJs('lib/jquery.cookie','cookie',array('jquery'),true,false);
        $this->AddJsUrl('https://www.google.com/recaptcha/api.js','google-api',array('jquery'),true,false);


        $this->AddJs('js/util','util',array('jquery'),true,true);
        $this->AddJs('js/api','api',array('util'),true,true);

        $this->AddJs('js/admin','admin',array('api'),false,true);

//        $this->AddJs('js/schedular','schedular',array('api'),true,false);
        $this->AddJs('lib/jquery-ui.min','jquery-ui',array('jquery'),true,false);
        $this->AddJs('js/schedule','jih.schedule',array('api','jquery-ui'),true,false);

    }


    /**
     * @param string $fileName filename to be found (exclude .js!)
     * @param string $overwriteFileName
     * @param array $dependencies to other js files
     * @param bool $wp
     * @param bool $admin
     * @return Js
     */
    public function AddJs($fileName,$overwriteFileName=null,array $dependencies = array('jquery'),$wp=true,$admin=true){
        $path = JIH_URL.'/'.$this->resourceDirectory.$fileName.'.js';
        return new Js($overwriteFileName ?: $fileName,$path,$dependencies,$wp,$admin);
    }


    /**
     * @param string $url the url of the script
     * @param string $filename filename is used to define dependencies
     * @param array $dependencies
     * @param bool $wp
     * @param bool $admin
     * @return Js
     */
    public function AddJsUrl($url,$filename,array $dependencies = array('jquery'),$wp=true,$admin=true){
        return new Js($filename, $url,$dependencies,$wp,$admin);
    }

    /**
     * @param string $fileName filename to be found (exclude .js!)
     * @param string $overwriteFileName
     * @param array $dependencies to other js files
     * @param bool $wp
     * @param bool $admin
     * @return Css
     */
    public function AddCss($fileName,$overwriteFileName=null,array $dependencies = array(),$wp=true,$admin=true){
        $path = JIH_URL.'/'.$this->resourceDirectory.$fileName.'.css';
        return new Css($overwriteFileName ?: $fileName, $path, $dependencies,$wp,$admin);
    }
}