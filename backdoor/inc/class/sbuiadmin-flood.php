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
    var $how_many = 3; // Combien de fois avant le blocage définitif
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
    private $timeLimitProcess = [
        "DEFAULT" => 0.1,
        "CHAT"    => 1.5,
        "LOGIN"   => 0.1 
    ];

    function __construct() {
        if (class_exists('Memcache') && extension_loaded('memcache') && function_exists('memcache_connect')) {
            $this->memcache = new Memcache ();
            $this->memcache->connect ( self::HOST, self::PORT );
        }
    }

    function addUserlimit($key, $time) {
        $this->timeLimitUser [$key] = $time;
    }

    function addProcesslimit($key, $time) {
        $this->timeLimitProcess [$key] = $time;
    }

    public function quickIP() {
        return (empty ( $_SERVER ['HTTP_CLIENT_IP'] ) ? (empty ( $_SERVER ['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER ['REMOTE_ADDR'] : $_SERVER ['HTTP_X_FORWARDED_FOR']) : $_SERVER ['HTTP_CLIENT_IP']);
    }
    
    public function getUserInfosIPSTACK($user_ip) {
        // --------------------------------
        // --- IP INFOS (Country, region, city, ...)
        // --------------------------------
        // --- Initialize CURL
        $ch = curl_init('http://api.ipstack.com/'.$user_ip.'?access_key='.$this->ip_user_infos.'&language=fr&output=json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // --- Store the data
        $history_json = curl_exec($ch);
        curl_close($ch);
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
        $ip = $this->quickIP ();
        $ipKey = "flood" . $action . sha1 ( $ip );

        $runtime = $this->memcache->get ( 'floodControl' );
        $iptime = $this->memcache->get ( $ipKey );

        $limitUser = isset ( $this->timeLimitUser [$action] ) ? $this->timeLimitUser [$action] : $this->timeLimitUser ['DEFAULT'];
        $limitProcess = isset ( $this->timeLimitProcess [$action] ) ? $this->timeLimitProcess [$action] : $this->timeLimitProcess ['DEFAULT'];

        // Initialise
        $datas = [
             'ip' => $ip
            ,'blockedtime' => time()
            ,'expirationtime' => time() + $this->expiration
            ,'reason' => sprintf( $this->reason, $this->how_many, $this->last_request )
            ,'infos' => $this->getUserInfosIPSTACK($ip)
        ];
        
        // Limit All User
        if ((microtime ( true ) - $iptime) < $limitUser) {
            $this->insertNewIP($datas);
            header("Location:403.html");
            exit();
        }

        // Limit All request
        if ((microtime ( true ) - $runtime) < $limitProcess) {
            $this->insertNewIP($datas);
            header("Location:403.html");
            exit();
        }

        $this->memcache->set ( "floodControl", microtime ( true ) );
        $this->memcache->set ( $ipKey, microtime ( true ) );
    }
    
    public function insertNewIP($datas) {
        $ip = $datas['ip'];
        $blockedtime = $datas['blockedtime'];
        $expirationtime = $datas['expirationtime'];
        $reason = $datas['reason'];
        $infos = $datas['infos'];
        $query_new_ip   = "INSERT INTO ".$this->table_blocked_ip." (ip,blockedtime,expirationtime,reason,infos)
                   VALUES ('$ip','$blockedtime','$expirationtime','$reason','$infos')";
        $result__new_ip = $this->query($query_new_ip);
        return true;
    }
}