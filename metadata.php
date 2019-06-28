<?php
/**
 * @abstract
 * @author 	Gregor Wendland <gregor@gewend.de>
 * @copyright Copyright (c) 2019, Gregor Wendland
 * @package gw
 * @version 2019-06-28
 */

/**
 * Metadata version
 */
$sMetadataVersion = '2'; // see https://docs.oxid-esales.com/developer/en/6.0/modules/skeleton/metadataphp/version20.html

/**
 * Module information
 */
$aModule = array(
    'id'           => 'gw_oxid_email_attachment',
    'title'        => 'Bestell-E-Mail-Anhänge',
//     'thumbnail'    => 'out/admin/img/logo.jpg',
    'version'      => '1.0.0',
    'author'       => 'Gregor Wendland',
    'email'		   => 'kontakt@gewend.de',
    'url'		   => 'https://www.gewend.de',
    'description'  => array(
    	'de'		=> 'Macht es möglich an die Bestellbestätigung für den Kunden Dateien anzuhängen (z.B. AGB.pdf u.ä.) 
							<ul>
								<li>Im Ordner out/attachments/[sprachabkürzung]/ können beliebige Dateien hinterlegt werden, die der Bestellbestätigung angehangen werden</li>
							</ul>
						',
    ),
    'extend'       => array(
		OxidEsales\Eshop\Core\Email::class => gw\gw_oxid_email_attachment\Core\Email::class,

    ),
    'settings'		=> array(
    ),
    'files'			=> array(
    ),
	'blocks' => array(
	),
	'events'       => array(
		'onActivate'   => '\gw\gw_oxid_email_attachment\Core\Events::onActivate',
		'onDeactivate' => '\gw\gw_oxid_email_attachment\Core\Events::onDeactivate'
	),
	'controllers'  => [
	],
	'templates' => [
	]
);
?>
