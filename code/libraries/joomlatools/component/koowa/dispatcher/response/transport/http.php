<?php
/**
 * Joomlatools Framework - https://www.joomlatools.com/developer/framework/
 *
 * @copyright   Copyright (C) 2007 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/joomlatools/joomlatools-framework for the canonical source repository
 */

/**
 * Http Dispatcher Response Transport
 *
 * Pass all 'html' GET requests rendered outside of 'koowa' context on to Joomla.
 *
 * @author  Johan Janssens <https://github.com/johanjanssens>
 * @package Koowa\Component\Koowa\Dispatcher\Response\Transport
 */
class ComKoowaDispatcherResponseTransportHttp extends KDispatcherResponseTransportHttp
{
    /**
     * Send HTTP response
     *
     * @param KDispatcherResponseInterface $response
     * @return boolean
     */
    public function send(KDispatcherResponseInterface $response)
    {
        $request = $response->getRequest();

        if(!$response->isDownloadable() && $request->getFormat() == 'html')
        {
            if ($request->getHeaders()->has('X-Flush-Response'))
            {
                $layout = 'koowa';
            }
            else $layout = $request->query->get('tmpl', 'cmd') == 'koowa' ? 'koowa' : 'joomla';

            //Render the page
            $this->getObject('com:koowa.controller.page',  array('response' => $response))
                ->layout($layout)
                ->render();

            //Pass back to Joomla
            if ($request->isGet() && $layout != 'koowa')
            {
                //Mimetype
                JFactory::getDocument()->setMimeEncoding($response->getContentType());

                //Remove Content-Type header to prevent duplicate header conflict (see #172)
                $response->headers->remove('Content-Type');

                //Headers
                $headers = explode("\r\n", trim((string) $response->headers));
                foreach ($headers as $header)
                {
                    $parts = explode(':', $header, 2);

                    if (count($parts) !== 2) { // Empty values are not allowed per RFC2616 Sec 4.2
                        continue;
                    }

                    // JResponse doesn't play well with cookie headers for some reason
                    if ($parts[0] === 'Set-Cookie') {
                        continue;
                    }

                    if(version_compare(JVERSION, '3.6.5', '>=')) {
                        JFactory::getApplication()->setHeader($parts[0], $parts[1]);
                    } else {
                        JResponse::setHeader($parts[0], $parts[1]);
                    }
                }

                //Cookies
                foreach ($response->headers->getCookies() as $cookie)
                {
                    setcookie(
                        $cookie->name,
                        $cookie->value,
                        $cookie->expire,
                        $cookie->path,
                        $cookie->domain,
                        $cookie->isSecure(),
                        $cookie->isHttpOnly()
                    );
                }

                //Set messages for any request method
                $messages = $response->getMessages();
                foreach($messages as $type => $group)
                {
                    if ($type === 'success') {
                        $type = 'message';
                    }

                    foreach($group as $message) {
                        JFactory::getApplication()->enqueueMessage($message, $type);
                    }
                }

                //Content
                echo $response->getContent();
                return true;
            }
        }

        return parent::send($response);
    }
}