<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 01-Aug-15
 * Time: 22:27
 */

namespace helpers;


class Css {
    /**
     * @var string
     */
    protected $file;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var array
     */
    protected $dependencies;


    /**
     * @param string $file
     * @param string $name
     * @param array $dependencies
     * @param bool $wp if script is for frontend
     * @param bool $admin if script is for backend
     */
    public function __construct($name,$file,$dependencies = array(),$wp=true,$admin=true){

        $this->file = $file;
        $this->name = $name;
        $this->dependencies = $dependencies;

        if($wp)
            add_action( 'wp_enqueue_scripts',  array( $this, 'enqueue' ) );
        if($admin)
            add_action( 'admin_enqueue_scripts',  array( $this, 'enqueue' ) );
    }

    public function enqueue(){
        wp_enqueue_style($this->name, $this->file, $this->dependencies);
    }
}