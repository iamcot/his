<?php
require_once('./roots.php');
require_once($root_path.'include/core/inc_environment_global.php');
require_once($root_path.'include/core/inc_config_no_nameBV.php');
require_once($root_path.'include/care_api_classes/upload.class.php');
//$this->load->plugin("jupload");
//$JU = new UploadHandler($options);
         $configs['upload_dir'] = $root_path.'uploads/photos/news/';
         $configs['upload_url'] = SITE_URL.'images/';
        /* $configs['thumbnail'] = array(
                    'upload_dir' => dirname($_SERVER['SCRIPT_FILENAME']).'/././thumbnails/',
                    'upload_url' => $this->config->item('base_url').'thumbnails/',
                    'max_width' => 140,
                    'max_height' => 200
                );
                */
        $upload_handler = new UploadHandler($configs);

        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Content-Disposition: inline; filename="files.json"');
        header('X-Content-Type-Options: nosniff');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: X-File-Name, X-File-Type, X-File-Size');

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'OPTIONS':
                break;
            case 'HEAD':
            case 'GET':
                $upload_handler->get();
                break;
            case 'POST':
                if (isset($_REQUEST['_method']) && $_REQUEST['_method'] === 'DELETE') {
                    $upload_handler->delete();
                } else {
                    $upload_handler->post();
                }
                break;
            case 'DELETE':
                $upload_handler->delete();
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
        }