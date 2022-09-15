<?php
/**
* 2007-2022 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2022 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'iframecart` (
    `id_iframecart` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(250) NOT NULL,
    `active` boolean not null default 1,
    PRIMARY KEY  (`id_iframecart`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;';
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'iframecart_detail` (
    `id_iframecart_detail` int (11) NOT NULL UNIQUE AUTO_INCREMENT,
    `id_iframecart` int (11) NOT NULL,
    `id_product` int (10) UNSIGNED NOT NULL,
    `id_product_attribute` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `quantity` int(4) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id_iframecart`,`id_product`,`id_product_attribute`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';
$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'iframecart_detail` ADD CONSTRAINT FK_IFRAMECART FOREIGN KEY(id_iframecart) REFERENCES `' . _DB_PREFIX_ . 'iframecart` (id_iframecart) ON DELETE CASCADE'; 
$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'iframecart_detail` ADD CONSTRAINT FK_IFRAMECART_PRODUCT FOREIGN KEY(id_product) REFERENCES `' . _DB_PREFIX_ . 'product` (id_product) ON DELETE CASCADE'; 
$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'iframecart_detail` ADD CONSTRAINT FK_IFRAMECART_PRODUCT_ATTRIBUTE FOREIGN KEY(id_product_attribute) REFERENCES `' . _DB_PREFIX_ . 'product_attribute` (id_product_attribute) ON DELETE CASCADE'; 
foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
/*`id_cart` int(10) UNSIGNED NOT NULL,
`id_product` int(10) UNSIGNED NOT NULL,
`id_address_delivery` int(10) UNSIGNED NOT NULL DEFAULT '0',
`id_shop` int(10) UNSIGNED NOT NULL DEFAULT '1',
`id_product_attribute` int(10) UNSIGNED NOT NULL DEFAULT '0',
`id_customization` int(10) UNSIGNED NOT NULL DEFAULT '0',
`quantity` int(10) UNSIGNED NOT NULL DEFAULT '0',
`date_add` datetime NOT NULL,
KEY `id_product_attribute` (`id_product_attribute`),
KEY `id_cart_order` (`id_cart`,`date_add`,`id_product`,`id_product_attribute`)*/
