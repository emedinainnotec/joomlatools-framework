<?php
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

class ComFilesTemplateHelperPaginator extends ComKoowaTemplateHelperPaginator
{
    /**
     * Render item pagination
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     * @see     http://developer.yahoo.com/ypatterns/navigation/pagination/
     */
    public function pagination($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'limit'   => 0,
        ));

        $html  = '<div class="container" id="files-paginator-container">';
        $html .= '<div class="pagination" id="files-paginator">';
        $html .= '<div class="limit">'.$this->limit($config->toArray()).'</div>';
        $html .=  $this->_pages();
        $html .= '<div class="limit"> '.$this->translate('Page').' <span class="page-current">1</span>';
        $html .= ' '.$this->translate('of').' <span class="page-total">1</span></div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Render a list of pages links
     *
     * This function is overriddes the default behavior to render the links in the khepri template
     * backend style.
     *
     * @param   araay   An array of page data
     * @return  string  Html
     */
    protected function _pages($pages = null)
    {
    	$tpl = '<div class="button2-%s"><div class="%s"><a href="#">%s</a></div></div>';

    	$html = sprintf($tpl, 'right', 'start', $this->translate('Start'));
    	$html .= sprintf($tpl, 'right', 'prev', $this->translate('Prev'));
    	$html .= '<div class="button2-left"><div class="page"></div></div>';
    	$html .= sprintf($tpl, 'left', 'next', $this->translate('Next'));
    	$html .= sprintf($tpl, 'left', 'end', $this->translate('End'));

        return $html;
    }

	public function limit($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'limit'	  	=> 0,
			'attribs'	=> array(),
		));

		$html = '';

		$selected = '';
		foreach(array(10, 20, 50, 100) as $value)
		{
			if($value == $config->limit) {
				$selected = $value;
			}

			$options[] = $this->option(array('text' => $value, 'value' => $value));
		}

		$html .= $this->optionlist(array('options' => $options, 'name' => 'limit', 'attribs' => $config->attribs, 'selected' => $selected));
		return $html;
	}
}