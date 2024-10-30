<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
//$theme_options = dynamicnews_theme_options();
echo \Twig\WpTwigViewHelper::getInstance()->Render();

