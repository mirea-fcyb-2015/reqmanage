<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Template {

    private $_ci;

    protected $brand_name = 'Система управления требованиями';
    protected $title_separator = ' | ';

    protected $base_view = 'base_view';
    protected $layout = 'default';

    protected $title = FALSE;
    protected $menu = FALSE;
    protected $breadcrumb = array();

    protected $metadata = array();

    protected $js = array();
    protected $css = array();

    function __construct()
    {
        $this->_ci =& get_instance();
        $this->_ci->load->library('ion_auth', NULL, 'user');
    }

    
    public function set_layout($layout)
    {
        $this->layout = $layout;
    }

    public function set_base_view($base_view)
    {
        $this->base_view = $base_view;
    }
    
    public function set_title($title)
    {
        $this->title = $title;
    }

    public function set_menu($menu)
    {
        $this->menu = $menu;
    }

    public function set_breadcrumb($breadcrumb)
    {
        $this->breadcrumb = $breadcrumb;
    }

    
    public function add_metadata($name, $content)
    {
        $name = htmlspecialchars(strip_tags($name));
        $content = htmlspecialchars(strip_tags($content));

        $this->metadata[$name] = $content;
    }

    
    public function add_js($js)
    {
        $this->js[$js] = $js;
    }

    
    public function add_css($css)
    {
        $this->css[$css] = $css;
    }

    
    public function load_view($view, $data = array(), $return = FALSE)
    {
        if ($this->_ci->input->is_ajax_request())
        {
            $this->_ci->load->view($view, $data);
            return;
        }
        if (empty($this->title))
        {
            $title = $this->brand_name;
        }
        else {
            $title = $this->title . $this->title_separator . $this->brand_name;
        }
        $metadata = array();
        foreach ($this->metadata as $name => $content)
        {
            if (strpos($name, 'og:') === 0)
            {
                $metadata[] = '<meta property="' . $name . '" content="' . $content . '">';
            }
            else
            {
                $metadata[] = '<meta name="' . $name . '" content="' . $content . '">';
            }
        }
        $metadata = implode('', $metadata);
        $js = array();
        foreach ($this->js as $js_file)
        {
            $js[] = '<script src="' . assets_url('js/'. $js_file .'.js') . '"></script>';
        }
        $js = implode('', $js);
        $css = array();
        foreach ($this->css as $css_file)
        {
            $css[] = '<link rel="stylesheet" href="' . assets_url('css/' . $css_file .'.css') . '">';
        }
        $css = implode('', $css);

        $header = $this->_ci->load->view('header', array('user' => $this->_ci->user->user()->row()), TRUE);
        $footer = $this->_ci->load->view('footer', array(), TRUE);
        $menu = $this->_ci->load->view('sidebar', array('menu' => $this->menu), TRUE);
        $main_content = $this->_ci->load->view('modules/'. $view, $data, TRUE);

        $body = $this->_ci->load->view('layout/' . $this->layout, array(
            'header' => $header,
            'footer' => $footer,
            'sidebar' => $menu,
            'title' => $this->title,
            'breadcrumb' => $this->breadcrumb,
            'main_content' => $main_content,
        ), TRUE);

        return $this->_ci->load->view($this->base_view, array(
            'title' => $title,
            'metadata' => $metadata,
            'js' => $js,
            'css' => $css,
            'body' => $body
        ), $return);
    }
}


