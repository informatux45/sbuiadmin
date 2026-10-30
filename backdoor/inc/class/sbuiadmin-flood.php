<?php
/** *****************************************************************************
*                      INFORMATUX Flood class (UTF8)                            *
/** *****************************************************************************
* @author     Patrice BOUTHIER <contact[at]informatux.com>                      *
* @copyright  1996-2023 INFORMATUX                                              *
* @link       http://www.informatux.com/                                        *
* @since      1.0                                                               *
* @version    CVS: 1.8                                                          *
* ----------------------------------------------------------------------------- *
* Copyright (c) 2023, INFORMATUX Solutions and web development                  *
* All rights reserved.                                                          *
***************************************************************************** **/

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');

class flood extends sql {
    const HOST = "localhost";
    const PORT = 11211;
    var $expiration = 1 * 24 * 60 * 60; // 1 jour; 24 heures; 60 minutes; 60 secondes == 1 journée
    var $how_many = 3; // Combien de rafales (dans la minute) avant le blocage
    var $last_request = 2; // Derniere requete en secondes
    var $message = 'Blocked'; // Message à l'IP bloquée
    var $reason = 'Too many connexion in %s request(s) that last %s second(s) each one';
    var $table_blocked_ip = _AM_DB_PREFIX . 'sb_blocked_ip';
    var $ip_user_infos = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; // API ipstack
    private $memcache;
    private $ipAddress;

    private $timeLimitUser = [
        "DEFAULT" => 1,
        "CHAT"    => 3,
        "LOGIN"   => 4
    ];

    public function __construct() {
        // --- Pull the configurable bits from Paramètres > Utilisateurs > IP(s)
        // bloquée(s) > Paramètres IP bloquées, when defined, without breaking
        // callers that don't rely on sbuiadmin-config.php having run.
        if (defined('_AM_FLOOD_EXPIRATION') && _AM_FLOOD_EXPIRATION > 0) {
            $this->expiration = _AM_FLOOD_EXPIRATION;
        }
        if (defined('_AM_FLOOD_LOGIN_DELAY') && _AM_FLOOD_LOGIN_DELAY > 0) {
            $this->timeLimitUser['LOGIN'] = _AM_FLOOD_LOGIN_DELAY;
        }
        // --- Memcache is optional: floodCheck() no-ops instead of fataling
        // when it isn't reachable, so a Memcache hiccup never locks out login.
        if (class_exists('Memcache') && extension_loaded('memcache') && function_exists('memcache_connect')) {
            try {
                $this->memcache = new Memcache();
                if (!@$this->memcache->connect(self::HOST, self::PORT)) {
                    $this->memcache = null;
                }
            } catch (Exception $e) {
                $this->memcache = null;
            }
        }
    }

    private function addUserlimit($key, $time) {
        $this->timeLimitUser [$key] = $time;
    }

    public function quickIP() {
        // REMOTE_ADDR ou adresse d'un proxy déclaré (inc/sbuiadmin-clientip.php)
        $ip = function_exists('sbClientIp') ? sbClientIp() : (string) ($_SERVER['REMOTE_ADDR'] ?? '');
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
    }
    
    public function getUserInfosIPSTACK($user_ip) {
        // --- No real API key configured (still the placeholder) - skip the
        // external call entirely rather than waiting on a request doomed to fail.
        if (empty($this->ip_user_infos) || $this->ip_user_infos === 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') {
            return "Infos indisponibles (clé API ipstack non configurée).";
        }
        // --------------------------------
        // --- IP INFOS (Country, region, city, ...)
        // --------------------------------
        // --- Initialize CURL
        $ch = curl_init('http://api.ipstack.com/'.$user_ip.'?access_key='.$this->ip_user_infos.'&language=fr&output=json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // --- Store the data
        $history_json = curl_exec($ch);
		// --------------------------------
        $client_info = "";
		// --------------------------------
		$client_info .= "FLOOD détecté sur l'IP : $user_ip<br><br>";
		$client_info .= "<u>User infos :</u><br><br>";
		$client_info .= "<br>Date : ".date('d-m-Y à H:i:s')."<br>";
        if (is_array($history_json) || is_object($history_json)) {
			$json = json_decode($history_json, true);
			foreach($json as $key => $row) {
				if ($key == 'location') {
					foreach($row as $k => $v) {
						if ($k == 'languages') {
							foreach($v[0] as $ke => $va) {
								$client_info .= $ke . ' : ' . $va . '<br>';
							}
						} else {
							$client_info .= $k . ' : ' . $v . '<br>';
						}
					}
				} else {
					$client_info .= $key . ' : ' . $row . '<br>';
				}
			}
		} else {
            $history_json = "No info available!";
        }
        return $history_json;
    }

    public function floodCheck($action = "DEFAULT") {
        // --- Memcache unreachable (not installed, down, misconfigured...):
        // silently skip rather than fatal - anti-flood is a bonus safety net,
        // it must never be the reason legitimate logins stop working.
        if (!$this->memcache) {
            return;
        }

        $ip = $this->quickIP ();
        $ipKey = "flood" . $action . sha1 ( $ip );
        $strikesKey = $ipKey . "strikes";

        $iptime = (float) $this->memcache->get ( $ipKey );
        $limitUser = isset ( $this->timeLimitUser [$action] ) ? $this->timeLimitUser [$action] : $this->timeLimitUser ['DEFAULT'];

        // --- Requête trop rapprochée de la précédente de CETTE IP : une
        // --- rafale. Bannissement seulement à la $how_many-ième rafale dans
        // --- la minute : un lien chargé deux fois (messagerie, proxy d'images
        // --- Gmail) ne bannit plus. L'ancien seuil global (« floodControl »,
        // --- 0,1 s entre deux requêtes de N'IMPORTE QUELS visiteurs) bannissait
        // --- le second de deux visiteurs différents : supprimé.
        if ((microtime ( true ) - $iptime) < $limitUser) {
            $strikes = (int) $this->memcache->get ( $strikesKey ) + 1;
            if ($strikes >= $this->how_many) {
                $this->memcache->delete ( $strikesKey );
                $this->insertNewIP([
                     'ip' => $ip
                    ,'blockedtime' => time()
                    ,'expirationtime' => time() + $this->expiration
                    ,'reason' => sprintf( $this->reason, $this->how_many, $limitUser )
                    ,'infos' => $this->getUserInfosIPSTACK($ip)
                ]);
                sbForbiddenRedirect();
            }
            $this->memcache->set ( $strikesKey, $strikes, 0, 60 );
        }

        $this->memcache->set ( $ipKey, microtime ( true ) );
    }
    
    public function insertNewIP($datas) {
        // infos vient d'une API externe : valeurs liées, jamais insérées dans la requête
        $this->db()->insert('sb_blocked_ip', array(
            'ip'             => (string) $datas['ip'],
            'blockedtime'    => intval($datas['blockedtime']),
            'expirationtime' => intval($datas['expirationtime']),
            'reason'         => (string) $datas['reason'],
            'infos'          => (string) $datas['infos'],
        ));
        if (function_exists('sbSecurityEvent')) sbSecurityEvent('flood', 'warning', (string) $datas['reason'], 'banned');
        return true;
    }
}