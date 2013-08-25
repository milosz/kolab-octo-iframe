<?php

/**
 * Kolab IFRAME Plugin
 *
 * @author Aleksander 'A.L.E.C' Machniak <machniak@kolabsys.com>
 * @licence GNU AGPL
 *
 * Configuration (see config.inc.php)
 * 
 * Modified OwnCloud Plugin
 *
 * For description visit:
 * http://blog.sleeplessbeastie.eu/2013/06/24/kolab-how-to-integrate-piwik/
 */

class ifrapp extends rcube_plugin
{
    // all task excluding 'login' and 'logout'
    public $task = '?(?!login|logout).*';
    // we've got no ajax handlers
    public $noajax = true;
    // skip frames
    public $noframe = true;

    function init()
    {
        $rcmail = rcube::get_instance();

        // requires kolab_auth plugin
        if (empty($_SESSION['kolab_uid'])) {
            return;
        }

        $this->add_texts('localization/', false);

        // register task
        $this->register_task('ifrapp');

        // register actions
        $this->register_action('index', array($this, 'action'));
        $this->register_action('redirect', array($this, 'redirect'));

        // add taskbar button
        $this->add_button(array(
            'command'    => 'ifrapp',
            'class'      => 'button-ifrapp',
            'classsel'   => 'button-ifrapp button-selected',
            'innerclass' => 'button-inner',
            'label'      => 'ifrapp.ifrapp',
            ), 'taskbar');

        // add style for taskbar button (must be here) and Help UI
        $this->include_stylesheet($this->local_skin_path()."/ifrapp.css");
    }

    function action()
    {
        $rcmail = rcube::get_instance();

        $rcmail->output->add_handlers(array('ifrappframe' => array($this, 'frame')));
        $rcmail->output->set_pagetitle($this->gettext('ifrapp'));
        $rcmail->output->send('ifrapp.ifrapp');
    }

    function frame()
    {
        $rcmail = rcube::get_instance();

        $this->load_config();

        $src  = $rcmail->config->get('ifrapp_url');

        return '<iframe id="ifrappframe" width="100%" height="100%" frameborder="0"'
            .' src="' . $src. '"></iframe>';
    }

}
