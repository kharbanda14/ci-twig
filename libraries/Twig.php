<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class Twig extends CI_Loader
{
    protected $CI;

    private $twig;

    public function __construct()
    {
        $this->CI = &get_instance();
        $loader = new Twig_Loader_Filesystem($this->CI->config->item('twig_templates'));
        $twig_env = array();
        $cache_templates = $this->CI->config->item('cache_twig');
        if ($cache_templates) {
            $twig_env['cache'] = $this->CI->config->item('twig_cache_path');
        }
        $twig = new Twig_Environment($loader, $twig_env);
        foreach ($this->twig_globals() as $key => $value) {
            $twig->addGlobal($key, $value);
        }
        foreach ($this->twig_functions() as $key => $value) {
            $twig->addFunction(new Twig_Function($key, $value));
        }
        foreach ($this->twig_filters() as $key => $value) {
            $twig->addFilter(new Twig_Filter($key, $value));
        }
        $this->twig = $twig;
    }

    public function render($template, $data = array())
    {
        echo $this->twig->render($template, $data);
    }

    public function twig_globals()
    {
        $baseurl = $this->CI->config->item('base_url');
        $vars = array(
            'baseurl' => $baseurl,
            'sitename' => 'CI with Twig'
        );

        return $vars;
    }

    public function twig_functions()
    {
        $my_functions = array(
            'format_seconds' => function ($sec) {
                if ($sec == 0) {
                    return 0;
                }
                return gmdate('H:i:s', $sec);
            },
        );
        return $my_functions;
    }
    public function twig_filters()
    {
        $my_filters = array(
            'base64_encode' => 'base64_encode',
            'base64_decode' => 'base64_decode',
        );
        return $my_filters;
    }

}


?>