<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Wpanelmenu extends Widget {

    private $menu_id = '';
    private $ul_style = '';
    private $li_style = '';
    
    private $ul_dropdown = '';

	function __construct($config = array())
	{
		if (count($config) > 0) {
            $this->initialize($config);
        }
	}

    public function initialize($config = array())
    {
        foreach ($config as $key => $val)
        {
            if (isset($this->$key)) {
                $method = 'set_' . $key;
                if (method_exists($this, $method)) {
                    $this->$method($val);
                } else {
                    $this->$key = $val;
                }
            }
        }
        return $this;
    }

    public function run()
	{
		return $this->get_menu($this->menu_id, $this->ul_style, $this->li_style);
	}

    /**
     * ---------------------------------------------------------------------------------------------
     * Este método faz a exibição de um menu usando o ID como parametro principal.
     * 
     * O menu é gerado usando "<ul></ul>" e "<li></li>", os estilos pré-definidos são
     * os usados pelo Bootstrap, mas pode-se deixar os parametros de estilo em
     * branco e criar seus próprios estilos usando "ul li {}"
     * 
     * @author Eliel de Paula <dev@elieldepaula.com.br>
     * @param int $menu_id
     * @param string $ul_style
     * @param string $li_style
     * @return mixed
     * ---------------------------------------------------------------------------------------------
     */
    private function get_menu($menu_id = null, $ul_style = '', $li_style = '')
    {
        if ($menu_id == null)
            return false;

        $this->load->model('menu_item');
        $query = $this->menu_item->get_by_field(
            'menu_id', 
            $menu_id, 
            array('field' => 'ordem', 'order' => 'asc'),
            null
        )->result();

        $html = "";
        $html .= "<ul class=\"" . $ul_style . "\">";
        foreach ($query as $row)
        {

            if ($row->type == 'submenu')
                $html .= "<li class=\"" . $li_style . "\">";
            else
                $html .= "<li>";
            
            switch ($row->type)
            {
                case 'link':
                    $html .= "<a href=\"" . $row->href . "\">" . $row->label . "</a>";
                    break;
                case 'post':
                    $html .= anchor('post/' . $row->href, $row->label);
                    break;
                case 'posts':
                    $html .= anchor('posts/' . $row->href, $row->label);
                    break;
                case 'funcional':
                    if ($row->href == 'home')
                        $html .= anchor('', $row->label);
                    else
                        $html .= anchor($row->href, $row->label);
                    break;
                case 'submenu':
                    $html .= "<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" >" . $row->label . " <span class=\"caret\"></span></a>";
                    $html .= $this->get_menu($row->href, 'dropdown-menu', 'dropdown-submenu');
                    break;
            }
            $html .= "</li>";
        }
        $html .= "</ul>";
        return $html;
    }

}