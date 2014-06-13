<?php

/**
  Author: Kyle & Irving
  Author URI: http://kyle-irving.co.uk/
  Plugin Name: Simply Scroll Up
  Plugin URI: http://pagelines.kyle-irving.co.uk/back-to-top/
  Version: 1.0.0
  Description: Allows users to get back to the top of your web page effortlessly. Adds a new options panel to DMS Settings for Simply Scroll Up settings.
  Class Name: Back_To_Top
  PageLines: true
  Section: false
 * 
 */
class Back_To_Top {

    function __construct() {
        add_action('init', array(&$this, 'init'));
        add_filter('pl_settings_array', array(&$this, 'add_settings'));
        add_action('wp_enqueue_scripts', array(&$this, 'add_scripts'));
        add_action('wp_print_styles', array(&$this, 'print_styles'));
    }

    function init() {
        load_plugin_textdomain('simply-scroll-up', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    function print_styles() { 
        $options = array(
            'position' => array(
                'bottom' => (int) pl_setting('back-to-top-position-bottom', array('default' => 20)),
                'right' => (int) pl_setting('back-to-top-position-right', array('default' => 20)),
                'position' => pl_setting('back-to-top-position-lr', array('default' => 'right')),
				'radius' => (int) pl_setting('back-to-top-radius', array('default' => 5))
            ),
            'padding' => array(
                'vertical' => (int) pl_setting('back-to-top-padding-vertical', array('default' => 10)),
                'horizontal' => (int) pl_setting('back-to-top-padding-horizontal', array('default' => 20))
            ),
            'styling' => array(
                'style' => pl_setting('back-to-top-style', array('default' => 'link')),
                'z-index' => (int) pl_setting('back-to-top-zindex', array('default' => 100)),
                'background-color' => pl_hashify(pl_setting('back-to-top-background-color', array('default' => '#555555'))),
                'background-hover-color' => pl_hashify(pl_setting('back-to-top-background-hover-color', array('default' => '#cccccc'))),
                'text-color' => pl_hashify(pl_setting('back-to-top-text-color', array('default' => '#FFFFFF'))),                
            ),
			'images-top' => array(
                'imge-url' => pl_setting('page_background_image_url_back_top', array('default' => 'none'))
            ),
        );
		
        ?>
        <style type="text/css">
            #button-back-to-top{
                bottom: <?php echo $options['position']['bottom']; ?>px;
                <?php echo $options['position']['position']; ?>: <?php echo $options['position']['right']; ?>px;
                padding: <?php echo $options['padding']['vertical']; ?>px <?php echo $options['padding']['horizontal']; ?>px;                
                color: <?php echo $options['styling']['text-color']; ?>;
                z-index: <?php echo $options['styling']['z-index']; ?>;
                font-size: 12px !important;     
				<?php if($options['styling']['style'] == 'image' ){ ?>
					<?php if($options['images-top']['imge-url'] != 'none'){  ?>
						background-image:url('<?php echo $options['images-top']['imge-url']; ?>');
					<?php }else{ ?>
						background-image:url('<?php echo plugins_url().'/simply-scroll-up/images/top.png'; ?>');
					<?php } ?>
					background-position: center;
					background-repeat: no-repeat;
					height:38px;
					width:38px;
					background-size:auto;
					text-indent:-9999px;
					background-color: rgba(0, 0, 0, 0)!important;
				<?php } ?>
				
				<?php if(pl_setting('icon_back_top',array('default' => 'none')) != 'none' && $options['styling']['style'] == 'icon'){  ?>
					background-color: rgba(0, 0, 0, 0)!important;
				<?php } ?>
				
				<?php if($options['styling']['style'] == 'pill'){  ?>
					text-decoration: none;
					opacity: .9;    
					-webkit-border-radius: <?php echo $options['position']['radius']; ?>px;
					-moz-border-radius: <?php echo $options['position']['radius']; ?>px;
					border-radius: <?php echo $options['position']['radius']; ?>px;
					-webkit-transition: background 200ms linear;
					-moz-transition: background 200ms linear;
					transition: background 200ms linear;
					-webkit-backface-visibility: hidden;
				<?php } ?>
            }
            <?php  if ('image' != $options['styling']['style'] && $options['styling']['style'] != 'link'){  ?>
                #button-back-to-top{
                    background-color: <?php echo $options['styling']['background-color']; ?>;
                }
                #button-back-to-top:hover{
                    background-color: <?php echo $options['styling']['background-hover-color']; ?>;
                }     
            <?php } ?>

        </style>
		
        <?php
    }

    function add_scripts() {
		$text = pl_setting('back-to-top-text');
		if(pl_setting('back-to-top-style', array('default' => 'link')) == 'icon'){ 
			if(pl_setting('icon_back_top',array('default' => 'none')) != 'none'){ 
				$icon = pl_setting("icon_back_top",array("default" => "none"));
				$text = '<i class="iii icon icon-3x icon-'. $icon .' "></i>';
			}else{
				$text = 'please select the icon';
			}
		}
		//var_dump($icon);exit;
		
        $style = pl_setting('back-to-top-style', array('default' => 'link'));
        $zindex = (int) pl_setting('back-to-top-zindex', array('default' => 50));
        wp_enqueue_script('jquery-scrollup', plugin_dir_url(__FILE__) . 'js/jquery.scrollup.js', array('jquery'), NULL, true);
        wp_enqueue_script('back-to-top', plugin_dir_url(__FILE__) . 'js/back-to-top.js', array('jquery', 'jquery-scrollup'), NULL, true);
		
		 wp_enqueue_script('pl-back-to-top-custom1', plugin_dir_url(__FILE__) . 'js/simply-scroll-up.js', array( 'jquery' ), pl_get_cache_key() , true);
		
        wp_localize_script('back-to-top', 'pagelines_scroll_up', array(
            'text' => ($text) ? $text : __('Simply Scroll Up', 'back-to-top'),
            'style' => $style,
            'zIndex' => $zindex
        ));
		

        //wp_enqueue_style('back-to-top', plugin_dir_url(__FILE__) . "css/{$style}.css", array(), NULL);
    }

    function add_settings($settings) {

        $settings['back-to-top'] = array(
            'name' => 'Simply Scroll Up',
            'icon' => 'icon-circle-arrow-up',
            'pos' => 3,
            'opts' => $this->options()
        );

        return $settings;
    }

    function options() {

        $settings = array(
            array(
                'type' => 'multi',
                'col'	=> 1,
                'title' => __('Styling', 'back-to-top'),
                'help' => '',
                'opts' => array(
                    array(
                        'key' => 'back-to-top-style',
                        'type' => 'select_same',
                        'label' => __('Select style of button', 'back-to-top'),
                        'default' => 'link',
                        'opts' => array(
                            'link',
                            //'tab',
                            'pill',
                            'image',
							'icon'
                        )
                    ),
                    array(
                        'key' => 'back-to-top-text',
                        'type' => 'text',
                        'default' => __('Simply Scroll Up', 'back-to-top'),
                        'label' => __('Button Text', 'back-to-top'),
                    ),
					array(
						'key'			=> 'page_background_image_url_back_top',
						'imgsize' 		=> 	'150',
						'sizemode'		=> 'height',
						'sizelimit'		=> 1224000,
						'type'			=> 'image_upload',
						'label' 		=> __( 'Image back top 38px x 38px ', 'pagelines' ),
						'default'		=> '',
						'compile'		=> true,

					),
					array(
						'key'		=> 'icon_back_top',
						'label'		=> __( 'Icon (Icon Mode)', 'pagelines' ),
						'type'		=> 'select_icon'
					),
                    array(
                        'key' => 'back-to-top-zindex',
                        'help' => 'Only use zindex if required, i.e your Simply Scroll Up link is behind another object.',
                        'type' => 'text',
                        'default' => 50,
                        'label' => __('Button z-index', 'back-to-top'),
                    )
					
                )
            ),
            array(
                'type' => 'multi',
                'col'	=> 2,
                'title' => __('Position', 'back-to-top'),
                'help' => '',
                'opts' => array(
					array(
                        'key' => 'back-to-top-position-lr',
                        'type' => 'select_same',
                        'label' => __('left or right', 'back-to-top'),
                        'default' => 'right',
                        'opts' => array(
                            'left',
                            'right',
                        )
                    ),
                    array(
                        'key' => 'back-to-top-position-bottom',
                        'type' => 'select_same',
                        'label' => __('Bottom (px)', 'back-to-top'),
                        'default' => 20,
                        'opts' => range(0, 100, 5)
                    ),
                    array(
                        'key' => 'back-to-top-position-right',
                        'type' => 'select_same',
                        'label' => __('Right or left (px)', 'back-to-top'),
                        'default' => 20,
                        'opts' => range(0, 100, 5)
                    )
                )
            ),
            array(
                'type' => 'multi',
                'col'	=> 3,
                'title' => __('Padding', 'back-to-top'),
                'help' => '',
                'opts' => array(
                    array(
                        'key' => 'back-to-top-padding-vertical',
                        'type' => 'select_same',
                        'label' => __('Top & Bottom (px)', 'back-to-top'),
                        'default' => 10,
                        'opts' => range(0, 100, 5)
                    ),
                    array(
                        'key' => 'back-to-top-padding-horizontal',
                        'type' => 'select_same',
                        'label' => __('Left & Right (px)', 'back-to-top'),
                        'default' => 20,
                        'opts' => range(0, 100, 5)
                    )
                )
            ),
			array(
				'key'		=> 'background_image_upload',
				'type' 		=> 'multi',
				'col'		=> 4,
				'title' 	=> __( 'Styling', 'pagelines' ),
				'help' 		=> '',
				'opts'		=> array(
					array(
                        'key' => 'back-to-top-background-color',
                        'type' => 'color',
                        'label' => __('Background color', 'back-to-top'),
                        'default' => '#555555'
                    ),
                    array(
                        'key' => 'back-to-top-background-hover-color',
                        'type' => 'color',
                        'label' => __('Background hover color', 'back-to-top'),
                        'default' => '#cccccc'
                    ),
                    
					array(
                        'key' => 'back-to-top-radius',
                        'type' => 'text',
                        'label' => __('Border radius', 'back-to-top'),
                        'default' => '5'
                    ),
					array(
                        'key' => 'back-to-top-text-color',
                        'type' => 'color',
                        'label' => __('Text color', 'back-to-top'),
                        'default' => '#FFFFFF'
                    )
					
				)
			)
        );


        return $settings;
    }

}

new Back_To_Top();
