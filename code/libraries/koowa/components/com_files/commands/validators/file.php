<?php
/**
 * Koowa Framework - http://developer.joomlatools.com/koowa
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/joomlatools/koowa-files for the canonical source repository
 */

/**
 * File Validator Command
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Koowa\Component\Files
 */
class ComFilesCommandValidatorFile extends ComFilesCommandValidatorNode
{
	protected function _databaseBeforeSave(KCommandContext $context)
	{
		$row = $context->caller;

		if (is_string($row->file) && !is_uploaded_file($row->file))
		{
			// remote file
            $file = $this->getObject('com://admin/files.database.row.url');
            $file->setData(array('file' => $row->file));

            if (!$file->load()) {
                throw new KControllerExceptionActionFailed('File cannot be downloaded');
            }

            $row->contents = $file->contents;

			if (empty($row->name))
			{
				$uri = $this->getObject('koowa:http.url', array('url' => $row->file));
	        	$path = $uri->toString(KHttpUrl::PATH | KHttpUrl::FORMAT);
	        	if (strpos($path, '/') !== false) {
	        		$path = basename($path);
	        	}

	        	$row->name = $path;
			}
		}

		return parent::_databaseBeforeSave($context) && $this->getObject('com://admin/files.filter.file.uploadable')->validate($context->caller);

	}
}
