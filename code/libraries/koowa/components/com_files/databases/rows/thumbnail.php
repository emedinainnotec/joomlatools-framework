<?php
/**
 * Koowa Framework - http://developer.joomlatools.com/koowa
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/joomlatools/koowa-files for the canonical source repository
 */

/**
 * Thumbnail Database Row
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Koowa\Component\Files
 */
class ComFilesDatabaseRowThumbnail extends KDatabaseRowDefault
{
    protected $_thumbnail_size;

	public function __construct(KObjectConfig $config)
	{
		parent::__construct($config);

		$this->setThumbnailSize(KObjectConfig::unbox($config->thumbnail_size));
	}

    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'thumbnail_size' => array('x' => 200, 'y' => 150)
        ));

        parent::_initialize($config);
    }

    public function generateThumbnail()
    {
		@ini_set('memory_limit', '256M');

    	$source = $this->source;
    	if ($source && !$source->isNew())
		{
			try {
				//Load the library
				$this->getObject('koowa:class.loader')->loadIdentifier('com://admin/files.helper.phpthumb.phpthumb');
				
				//Create the thumb
				$image = PhpThumbFactory::create($source->fullpath)
					->setOptions(array('jpegQuality' => 50));
				
				if ($this->_thumbnail_size['x'] && $this->_thumbnail_size['y']) {
					// Resize then crop to the provided resolution.
					$image->adaptiveResize($this->_thumbnail_size['x'], $this->_thumbnail_size['y']);
				} else {
					$width = isset($this->_thumbnail_size['x'])?$this->_thumbnail_size['x']:0;
					$height = isset($this->_thumbnail_size['y'])?$this->_thumbnail_size['y']:0;
					// PhpThumb will calculate the missing side while preserving the aspect ratio.
					$image->resize($width, $height);
				}
				
				ob_start();
				echo $image->getImageAsString();
				$str = ob_get_clean();
				$str = sprintf('data:%s;base64,%s', $source->mimetype, base64_encode($str));
				
				return $str;
			}
			catch (Exception $e) {
				return false;
			}
		}

		return false;
    }

	public function save()
	{
		if ($source = $this->source)
		{
			if (!$source->isNew())
			{
				$str = $source->thumbnail_string ? $source->thumbnail_string : $this->generateThumbnail();

		    	$this->setData(array(
			    	'files_container_id' => $source->container->id,
					'folder'			 => $source->folder,
					'filename'           => $source->name,
					'thumbnail'          => $str
			    ));

			}
			else return false;
		}

		return parent::save();
	}

    public function toArray()
    {
        $data = parent::toArray();

		unset($data['_thumbnail_size']);
		unset($data['source']);

        return $data;
    }

    public function getThumbnailSize()
    {
        return $this->_thumbnail_size;
    }

    /**
     * @param array $size An array with x and y properties
     */
    public function setThumbnailSize(array $size)
    {
        $this->_thumbnail_size = $size;
    }
}
