<?php
/*
Plugin Name: EA Weight Based Shipping
Plugin URI: https://github.com/eayllon/ea-weight-based-shipping
Description: Weight based shipping for WooCommerce
Version: 1.0.1
Author: Estudio Ayllón
Author URI: https://estudioayllon.com
Text Domain: ea-weight-based-shipping
Domain Path: /lang
License: GPL3

    EA Weight Based Shipping version 1.0.1, Copyright (C) 2016 Estudio Ayllón

    EA Weight Based Shipping is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    EA Weight Based Shipping is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with EA Weight Based Shipping.  If not, see <http://www.gnu.org/licenses/>.

*/

namespace EA_Weight_Based_Shipping;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define('EAWBS_PLUGIN', plugins_url( '', __FILE__ ));
define('EAWBS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('EAWBS_REL_PATH', plugin_basename( dirname( __FILE__ ) ));
define('EAWBS_VERSION', '1.0.1');

require_once 'classes/class-eawbs-plugin.php';
new EAWBS_Plugin();
