<?php
namespace Dashboard\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * This view helper class displays a menu bar.
 */
class Menu extends AbstractHelper 
{
    /**
     * Menu items array.
     * @var array 
     */
    protected $items = [];
    
    /**
     * Active item's ID.
     * @var string  
     */
    protected $activeItemId = '';

    /**
     * Active item's ID.
     * @var string
     */
    protected $isLogged = '';

    
    /**
     * Constructor.
     * @param array $items Menu items.
     * @param \Zend\View\Helper $escapeHtml.
     */
    public function __construct($items=[])
    {
        $this->items = $items;
    }
    
    /**
     * Sets menu items.
     * @param array $items Menu items.
     */
    public function setItems($items) 
    {
        $this->items = $items;
    }

    /**
     * Adds menu item.
     * @param array $item Menu item.
     */
    public function addItem($item)
    {
        $this->items[] = $item;
    }
    
    /**
     * Sets ID of the active items.
     * @param string $activeItemId
     */
    public function setActiveItemId($activeItemId) 
    {
        $this->activeItemId = $activeItemId;
    }

    /**
     * Sets logged in flag
     */
    public function setLogged($logged)
    {
        $this->isLogged = $logged;
    }
    
    /**
     * Renders the menu.
     * @return string HTML code of the menu.
     */
    public function render() 
    {
        if (count($this->items)==0)
            return ''; // Do nothing if there are no items.
        
        $result = '<nav><div class="navbar navbar-default" role="navigation">';
        $result .= '<div class="navbar-header">';
        $result .= '<button type="button" class="navbar-toggle" data-toggle="collapse"';
        $result .= 'data-target=".navbar-ex1-collapse">';
        $result .= '<span class="sr-only">Toggle navigation</span>';
        $result .= '<span class="icon-bar"></span>';
        $result .= '<span class="icon-bar"></span>';
        $result .= '<span class="icon-bar"></span>';
        $result .= '</button>';
        $result .= '</div>';
        
        $result .= '<div class="collapse navbar-collapse navbar-ex1-collapse">';        
        $result .= '<ul class="nav navbar-nav">';

        $escapeHtml = $this->getView()->plugin('escapeHtml');
        
        // Render items
        foreach ($this->items as $item) {
            $result .= $this->renderItem($item, $escapeHtml);
        }

        $result .= '</ul>';
        $result .= '</div>';
        $result .= '</div></nav>';
        
        return $result;
        
    }
    
    /**
     * Renders an item.
     * @param array $item The menu item info.
     * @return string HTML code of the item.
     */
    protected function renderItem($item, $escapeHtml)
    {

        $id = isset($item['id']) ? $item['id'] : '';
        $isActive = ($id==$this->activeItemId);
        $label = isset($item['label']) ? $item['label'] : '';
             
        $result = ''; 

        if (isset($item['dropdown'])) {
            
            $dropdownItems = $item['dropdown'];
            
            $result .= '<li class="dropdown ' . ($isActive?'active':'') . '">';
            $result .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
            $result .= $escapeHtml($label) . ' <b class="caret"></b>';
            $result .= '</a>';
           
            $result .= '<ul class="dropdown-menu">';
            foreach ($dropdownItems as $item) {
                $link = isset($item['link']) ? $item['link'] : '#';
                $label = isset($item['label']) ? $item['label'] : '';
                
                $result .= '<li>';
                $result .= '<a href="'.$escapeHtml($link).'">'.$escapeHtml($label).'</a>';
                $result .= '</li>';
            }
            $result .= '</ul>';
            $result .= '</li>';
            
        } else {        
            $link = isset($item['link']) ? $item['link'] : '#';
            
            $result .= $isActive?'<li class="active">':'<li>';
            $result .= '<a href="'.$escapeHtml($link).'">'.$escapeHtml($label).'</a>';
            $result .= '</li>';
        }
    
        return $result;
    }
}
