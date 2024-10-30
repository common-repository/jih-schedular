<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 01-Aug-15
 * Time: 16:51
 */

namespace helpers;


use controllers\AdminController;
use Twig\WpTwigViewHelper;

class AdminMenu {
    /**
     * @var string
     */
    private $title;
    /**
     * @var array|callable
     */
    private $renderAction;
    /**
     * @var string
     */
    private $icon;
    /**
     * @var string
     */
    private $menuKey;

    /**
     * @var AdminSubMenu[]
     */
    private $subMenu = array();
    /**
     * @var string
     */
    private $prefix;

    /**
     * Generates an admin menu in wordpress, automatically hooks itself.
     * @param string $title menutitle and title of page
     * @param array|callable $renderAction this action should return html for this menu item
     * @param string $icon
     * @param string $menuKey
     * @param string $prefix
     */
    public function __construct($title = 'Calendars',$renderAction= null,$icon = 'dashicons-calendar',$menuKey = 'calendars',$prefix = 'jih'){
        add_action( 'admin_menu', array($this,'register_admin_menu'));

        if($renderAction == null)
            $renderAction = array($this,'route');

        $this->title = $title;
        $this->renderAction = $renderAction;
        $this->icon = $icon;
        $this->menuKey = $menuKey;
        $this->prefix = $prefix;
    }

    /**
     * @param AdminSubMenu $item
     * @return $this
     */
    public function AddSubMenu(AdminSubMenu $item){
        $this->subMenu[] = $item;
        return $this;
    }

    public function register_admin_menu(){
        $userNeedsThisOptionToSee = 'manage_options';
        //Links to AdminController->[item]Action();
        add_menu_page($this->title, $this->title, $userNeedsThisOptionToSee,$this->getMenuKey(), $this->renderAction,$this->icon );

        foreach($this->subMenu as $item){
            add_submenu_page($this->getMenuKey(), $item->getTitle(),  $item->getTitle(), $userNeedsThisOptionToSee, $this->prefixThis($item->getMenuKey()),
                $item->getRenderAction() ?: $this->renderAction);
        }
    }


    public function route(){
        $post = ucfirst(substr(Input::Get('page'),4))  ;
        $controller = new AdminController();
        $resultHtml = $controller->route($post);
        echo $resultHtml;
    }

    /**
     * @return string
     */
    public function getMenuKey()
    {
        return $this->prefix.'-'.$this->menuKey;
    }

    /**
     * @param $string string to prefix
     * @return string prefixed string
     */
    public function prefixThis($string)
    {
        return $this->prefix.'-'.$string;
    }
}

class AdminSubMenu {
    /**
     * @var string
     */
    private $title;
    /**
     * @var callable
     */
    private $renderAction;
    /**
     * @var null|string
     */
    private $menuKey;

    /**
     * @param string $title
     * @param callable $renderAction
     * @param string $menuKey
     */
    public function __construct($title,$renderAction = null,$menuKey = null){
        $this->title = $title;
        $this->renderAction = $renderAction;
        $this->menuKey = $menuKey ?: $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return callable
     */
    public function getRenderAction()
    {
        return $this->renderAction;
    }

    /**
     * @return null|string
     */
    public function getMenuKey()
    {
        return $this->menuKey;
    }
}