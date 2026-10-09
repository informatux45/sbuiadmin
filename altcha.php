<?php
/**
 * SBUIADMIN - Défi ALTCHA (JSON)
 *
 * Appelé par le widget anti-robot de l'administration comme du site. Public
 * par nature : un défi ne donne accès à rien, il doit être résolu (preuve de
 * travail) puis vérifié par sbAltchaVerify(), une seule fois. Voir
 * SBADMIN/inc/sbuiadmin-altcha.php.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'sbconfig.php');
sbAltchaServe();
