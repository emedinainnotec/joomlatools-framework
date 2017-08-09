<?php
/**
 * Joomlatools Framework - https://www.joomlatools.com/developer/framework/
 *
 * @copyright	Copyright (C) 2011 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/joomlatools/joomlatools-framework-files for the canonical source repository
 */
defined('KOOWA') or die;
?>

<?= helper('behavior.koowa'); ?>

<ktml:style src="assets://files/css/plyr.css" />
<ktml:script src="assets://files/js/plyr/plyr.js" />
<script>
    kQuery(function($){
        plyr.setup();

        $(document).on('pause', function(event) {
            var plyr = event.detail.plyr;

            // If they've played over 3 seconds, then consider it played
            // This is the same timing convention used by Facebook, Instagram, and Twitter
            if (typeof plyr !== 'undefined' && plyr.getCurrentTime() > 3.0) {

                var media = $(plyr.getMedia());

                var category = media.data('category');
                var action = 'Play ' + plyr.getType();

                var title = media.data('title') || '';
                var id = parseInt(media.data('media-id'), 10) || 0;
                var label = "#" + id + " - " + title;
                var time = parseInt(Math.ceil(plyr.getCurrentTime() * 10) / 10, 10);

                if (typeof window.GoogleAnalyticsObject !== 'undefined' && typeof window[window.GoogleAnalyticsObject] !== 'undefined') {
                    window[window.GoogleAnalyticsObject]('send', 'event', category, action, label, {
                        'time' : time
                    });
                }
                else if (typeof _gaq !== 'undefined' && typeof _gat !== 'undefined') {
                    if (_gat._getTrackers().length) {
                        _gaq.push(function () {
                            var tracker = _gat._getTrackers()[0];
                            tracker._trackEvent(category, action, label, {
                                'time' : time
                            });
                        });
                    }
                }
            }
        });
    });
</script>



