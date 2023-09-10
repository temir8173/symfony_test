<?php
namespace docker {
    function adminer_object() {
        require_once('plugins/plugin.php');

        class Adminer extends \AdminerPlugin {
            function _callParent($function, $args) {
                if ($function === 'loginForm') {
                    ob_start();
                    $return = \Adminer::loginForm();
                    $form = ob_get_clean();

                    echo str_replace('name="auth[server]" value="" title="hostname[:port]"', 'name="auth[server]" value="'.($_ENV['ADMINER_DEFAULT_SERVER'] ?: 'db').'" title="hostname[:port]"', $form);

                    return $return;
                }

                return parent::_callParent($function, $args);
            }
        }

        $plugins = [];
        foreach (glob('plugins-enabled/*.php') as $plugin) {
            $plugins[] = require($plugin);
        }
        foreach (glob('plugins-enabled/drivers/*.php') as $driver) {
            require($driver);
        }

        global $adminer;
        $adminer = new Adminer($plugins);
        return $adminer;
    }
}

namespace {
    if (basename($_SERVER['DOCUMENT_URI'] ?? $_SERVER['REQUEST_URI']) === 'adminer.css' && is_readable('adminer.css')) {
        header('Content-Type: text/css');
        readfile('adminer.css');
        exit;
    }

    if (($_SERVER['DOCUMENT_URI'] ?? $_SERVER['REQUEST_URI']) === '/js/sql-formatter.min.js' && is_readable('js/sql-formatter.min.js')) {
        header('Content-Type: text/javascript');
        readfile('js/sql-formatter.min.js');
        exit;
    }

    function adminer_object() {
        return \docker\adminer_object();
    }

    require('adminer.php');
}
