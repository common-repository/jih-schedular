<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 01-Aug-15
 * Time: 17:50
 */

namespace helpers;

class Page {

    protected $prefix = 'jih-option-';

    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    private $title;
    /**
     * @var callable
     */
    private $renderAction;
    /**
     * @var string
     */
    private $content;

    /**
     * @param string $title
     * @param callable $renderAction
     * @param string $content
     */
    public function __construct($title,$renderAction = null,$content = "This is a page generated for jih-schedlule plugin. This page will show the calendar where hours can be booked"){
        $this->title = $title;
        $this->renderAction = $renderAction;
        $this->content = $content;

        $this->id = get_option($this->getOptionName());

        add_action('wp_trash_post', array($this,'protectRegisteredPage'));
        add_action('before_delete_post',array($this,'protectRegisteredPage'));
        add_filter( 'the_content', array($this,'executeRenderIfMyPage'));
    }


    /**
     * saves page into WP database, saves option of the page id
     */
    public function register(){
        $id = wp_insert_post(array(
            'post_title'=>$this->title,
            'post_status'=>'publish',
            'post_type'=>'page',
            'post_content'=>$this->content
        ));
        $this->id = $id;
        \update_option($this->getOptionName(),$id);
        return $id;
    }


    /**
     * Deletes page from WP database, deletes option
     */
    public function unregister(){
        if($this->id){
            remove_action('before_delete_post',array($this,'protectRegisteredPage'));
            wp_delete_post($this->id,true);
            $this->id = null;
        }
        delete_option($this->getOptionName());
    }

    /**
     * Protects page from being deleted: wp_trash_post and before_delete_post actions should be registered on this
     * @param int $postId
     */
    public function protectRegisteredPage($postId){
        if($postId != 0 && $postId == $this->id)
            exit('You can not delete this page, this page is created for jih schedule plugin.');
    }

    public function executeRenderIfMyPage($originalContent){
        global $post;
        if($post->ID == $this->id && $this->renderAction != null){
            $newContent = call_user_func($this->renderAction,$originalContent);
            if($newContent !== null)
                return $newContent;
        }
        return $originalContent;
    }

    public function getOptionName(){
        return $this->prefix.$this->title;
    }
}