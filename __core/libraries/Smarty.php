<?php
/**
 * Created by Jacky.
 * User: jacky
 * Date: 6/4/2015
 * Time: 5:09 PM
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'third_party/smarty/libs/Smarty.class.php');

class CI_Smarty extends Smarty
{
    private $CI;
    private $theme;
    private $theme_dir;
    private $layout = 'default';
    private $header_for_layout = NULL;

    public function __construct()
    {
        parent::__construct();
		$this->setTemplateDir(APPPATH.'views/');
		$this->setCompileDir('__tmp/');
       /* $this->CI =& get_instance();
        $this->theme = $this->CI->config->item('layout_theme');
        $this->theme_dir = "views/" . $this->theme;

        $this->compile_dir = APPPATH . "cache/smarty/templates_c";
        $this->template_dir = APPPATH . $this->theme_dir . '/' . strtolower($this->CI->router->fetch_class());
        $this->assign('APPPATH', APPPATH);
        $this->assign('BASEPATH', BASEPATH);
        $this->assign('BASEURL', base_url());
        $this->assign('CURRENT_THEME', $this->theme);

        if (method_exists($this, 'assignByRef')) {
            $this->assignByRef("ci", $this->CI);
        }
		*/
    }

    public function set_layout($layout)
    {
        $this->layout = $layout;
    }

    public function set_header($header)
    {
        $this->header_for_layout = $header;
    }

    public function view($template, $data = array(), $return = FALSE)
    {
        $template = $template . '.tpl';
        foreach ($data as $key => $val) {
            $this->assign($key, $val);
        }

        if ($return == FALSE) {
            if (method_exists($this->CI->output, 'set_output')) {
                $this->CI->output->set_output($this->fetch($template));
            } else {
                $this->CI->output->final_output = $this->fetch($template);
            }
            return;
        } else {
            return $this->fetch($template);
        }
    }

    public function layout($template, $data = array())
    {
        $separated_title_for_layout = $this->header_for_layout['title'] . ' ' . $this->CI->config->item('layout_title_separator') . ' ' . $this->CI->config->item('layout_site_name');
        if (empty($this->header_for_layout['title'])) {
            $separated_title_for_layout = $this->CI->config->item('layout_site_name');
        }

        $view_content = $this->view($template, $data, TRUE);

        $this->view('../layouts/' . $this->layout, array(
            'content_for_layout' => $view_content,
            'title_for_layout' => $separated_title_for_layout
        ));
    }
}