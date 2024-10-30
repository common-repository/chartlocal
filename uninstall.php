<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
  exit;
}

delete_option('clt_tracking_code_id');
