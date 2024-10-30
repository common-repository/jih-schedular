<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 01-Aug-15
 * Time: 18:35
 */

namespace helpers;


class PageContainer {

    /**
     * @var Page[]
     */
    protected $pages = array();

    /**
     * @param Page $page
     * @return $this
     */
    public function add(Page $page){
        $this->pages[] = $page;
        return $this;
    }

    /**
     * Inserts all pages in container in wp database
     */
    public function registerPages(){
        foreach($this->pages as $page){
            $page->register();
        }
    }

    /**
     * Deletes all pages of container in wp database
     */
    public function unregisterPages(){
        foreach($this->pages as $page){
            $page->unregister();
        }
    }


}