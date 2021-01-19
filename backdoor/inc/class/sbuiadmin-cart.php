<?php
/** *****************************************************************************
*                      INFORMATUX page class (UTF8)                             *
/** *****************************************************************************
* @author     Patrice BOUTHIER <contact[at]informatux.com>                      *
* @copyright  1997-2021 INFORMATUX                                              *
* @link       http://www.informatux.com/                                        *
* @since      1.0                                                               *
* @version    CVS: 1.8                                                          *
* ----------------------------------------------------------------------------- *
* Copyright (c) 2020, INFORMATUX Solutions and web development                  *
* All rights reserved.                                                          *
***************************************************************************** **/

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');


class panier extends sql {
	
    /**
     * Holds various infos
     *
     * @var string
     */
    public $tblaccount     = _AM_DB_PREFIX . "sb_account";
	public $tblshopconfig  = _AM_DB_PREFIX . 'sb_shop_config';
    public $tblgroup       = _AM_DB_PREFIX . "sb_shop_group";
    public $tblproduct     = _AM_DB_PREFIX . "sb_shop_product";
    public $tbldiscount    = _AM_DB_PREFIX . "sb_shop_discount";
    public $tbltransport   = _AM_DB_PREFIX . "sb_shop_transport";
    public $tblpayment     = _AM_DB_PREFIX . "sb_shop_payment";
	public $tblemail       = _AM_DB_PREFIX . "sb_shop_email";
	public $tblcurrency    = _AM_DB_PREFIX . "sb_shop_currency_country";
	public $tblorder       = _AM_DB_PREFIX . 'sb_shop_order';
	public $tblorderdetail = _AM_DB_PREFIX . 'sb_shop_order_detail';
	public $tblaccess      = _AM_DB_PREFIX . "sb_logaccess";
	public $tblconfig      = _AM_DB_PREFIX . "sb_config";
	
	//var $article;       // Tableau des article du Panier
	//var $nbarticle;     // Nombre d'article dans le Panier
	//var $totalHT;       // Montant total HT du Panier
	//var $totalTTC;      // Montant total TTC du Panier
	var $total_quantity;  // Quantite totale d'article dans le panier
	var $total_price_ht;  // Prix total du panier HT (hors TVA, hors TRANSPORT)
	var $total_price_ttc; // Prix total du panier TTC (hors TRANSPORT)
	var $TVA;             // Taux de TVA
	var $n_decimals;      // Nombre de decimales
	var $shipping;        // Tarif livraison (mode de livraison)
	var $typeport;        // Type de livraison
	var $config;          // Shop configuration
	
	// Types de codes promo
	public $code_types = [
		'1' => _CMS_SHOP_DISCOUNT_TYPE_1 // Remise sur un produit
	   ,'2' => _CMS_SHOP_DISCOUNT_TYPE_2 // Remise sur le panier
	   ,'3' => _CMS_SHOP_DISCOUNT_TYPE_3 // Remise en pourcentage
	   ,'4' => _CMS_SHOP_DISCOUNT_TYPE_4 // Livraison gratuite
	];

	function __construct() {
		// --- Initialize
		$this->total_quantity  = 0;
		$this->total_price_ht  = 0;
		$this->total_price_ttc = 0;
		$this->shipping        = 0;
		// --- SHOP Configuration
		$this->config     = $this->getConfShop();
		$this->TVA        = $this->config->tva;
		$this->n_decimals = $this->config->n_decimals;
	}
	
	/**
	 * Add product to cart
	 *
	 * @param 	string	$ref 	Product Reference
	 * @param	integer	$qty	Product Quantity
	 *
	 * @return void
	 */
	function add($ref, $qty, $ekado = '0') {
		global $sbsanitize;
		// Check if quantity
		if ( !empty($qty) ) {
			// --- Initialize
			$reference = $sbsanitize->stopXSS( $ref );
			$quantity  = intval( $qty );
            // --- Free SQL
            $this->free();
			// --- Get product infos by REFERENCE
			$resultByReference  = $this->query( "SELECT * FROM " . $this->tblproduct . " WHERE reference = '$reference'" );
			$productByReference = $this->assoc($resultByReference);
            $itemArray = [];
			$itemArray = [
				$productByReference["reference"] => [
					'id'          => $productByReference["id"]
				   ,'ekado'       => $ekado
				   ,'title'       => $productByReference["title"]
				   ,'reference'   => $reference
				   ,'quantity'    => $quantity
				   ,'catid'       => $productByReference["catid"]
				   ,'tag'         => $productByReference["tag"]
				   ,'description' => $productByReference["description_short"]
				   ,'type'        => $productByReference["type"]
				   ,'price'       => $this->getProductPrice( $productByReference["id"] )
				   ,'photo'       => $productByReference["photo"]]
			];
			
			if ( !empty($_SESSION["cart"]) ) {
				if ( in_array( $reference, array_keys($_SESSION["cart"]) ) ) {
					foreach($_SESSION["cart"] as $k => $v) {
						if ($reference == $k) {
							if (empty($_SESSION["cart"][$k]["quantity"])) {
								$_SESSION["cart"][$k]["quantity"] = 0;
							}
							$_SESSION["cart"][$k]["quantity"] += $quantity;
						}
					}
				} else {
					$_SESSION["cart"] = array_merge($_SESSION["cart"], $itemArray);
				}
			} else {
				$_SESSION["cart"] = $itemArray;
			}
            // ----------------------
            // CLOSE SQL
            // ----------------------
            //$this->close();
		}
	}
	
	/**
	 * Update product to cart
	 *
	 * @return void
	 */
	function update() {
		global $_POST;
		// Check if products
		if ($_POST) {
			// initialize SESSION
			$this->cartEmpty();
			// Loop all products
			foreach($_POST['reference'] as $key => $value) {
				// --- Add product by REFERENCE / QUANTITY in SESSION (cart)
				$this->add( $_POST['reference'][$key], $_POST['quantity'][$key], $_POST['ekado'][$key] );
			}	
		}
	}
	
	/**
	 * Remove product from cart
	 *
	 * @param 	string	$ref 	Product Reference
	 *
	 * @return void
	 */
	function remove($reference) {
		if (!empty($_SESSION["cart"])) {
			foreach($_SESSION["cart"] as $k => $v) {
					if ($reference == $k) unset($_SESSION["cart"][$k]);				
					if (empty($_SESSION["cart"])) unset($_SESSION["cart"]);
			}
		}
	}
	
	/**
	 * Check if product reference exists in cart
	 *
	 * @param 	string	$ref 	Product Reference
	 *
	 * @return void
	 */
	function check($reference) {
		global $sbsanitize;
		// Check if quantity
		if (!empty($_SESSION["cart"])) {
			foreach($_SESSION["cart"] as $k => $v) {
					if ($reference == $k) return true;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * Update cart with CODE PROMO
	 *
	 * @param 	string	$code 	CODE PROMO
	 *
	 * @return void
	 */
	function promo($code) {
		global $sbsanitize, $sbsmarty;
		// -----------------------------
		// Check if SESSION cart_promo is already active
		// -----------------------------
		if ($_SESSION['cart_promo']) return false;
		// -----------------------------
		// Initialize
		// -----------------------------
		$is_promo = false;
		$code     = $sbsanitize->stopXSS($code);
        // --- Free SQL
        $this->free();
		// -----------------------------
		// Check if code exists AND is always available
		// -----------------------------
		$query    = "SELECT * FROM " . $this->tbldiscount . " WHERE code = '$code' AND active = '1' AND expiration >= CURDATE()";
		$request  = $this->query($query);
		$result   = $this->assoc($request);
		// -----------------------------
		// Check if code_limit is reached
		// -----------------------------
		if ($result['code_limit'] > 0) {
			if ($result['code_usage'] < $result['code_limit']) $is_promo = true;
		} elseif ($result) {
			$is_promo = true;
		}
		// -----------------------------
		// Initialize SESSION cart_promo
		// -----------------------------
		unset($_SESSION['cart_promo']);
		// -----------------------------
		// Is promo
		// -----------------------------
		if (!$is_promo) {
			// -----------------------------
			// Code promo non valide
			// -----------------------------
			return false;
		} else {
			// -----------------------------
			// Switch DISCOUNT TYPE
			// -----------------------------
			switch($result['type']) {
				case "1": // Remise sur un produit
					// -----------------------------
					// Check if product is in CART
					// -----------------------------
					$is_valid = $this->check( $result['product'] );
				break;
				case "2": // Remise sur le panier
					$is_valid = true;
				break;
				case "3": // Remise en pourcentage
					$is_valid = true;
				break;
				case "4": // Livraison gratuite
					$is_valid = true;
				break;
			}
			if ($is_valid) {
				// -----------------------------
				// --- Initialize
				// -----------------------------
				unset($_SESSION['cart_promo']);
				// -----------------------------
				// --- Instantiate SESSION
				// -----------------------------
				$_SESSION['cart_promo']['code']         = $result['type'];
				$_SESSION['cart_promo']['code_name']    = $result['code'];
				$_SESSION['cart_promo']['type']         = $this->code_types[ $result['type'] ];
				$_SESSION['cart_promo']['valeur_float'] = $result['valeur'];
				$_SESSION['cart_promo']['valeur']       = $this->addCurrency( $result['valeur'] );
				$_SESSION['cart_promo']['description']  = $result['description'];
				$_SESSION['cart_promo']['limit']        = $result['limit'];
				$_SESSION['cart_promo']['usage']        = $result['usage'];
                // --- Free SQL
                $this->free();
				// -----------------------------
				// --- Update usage
				// -----------------------------
				$query  = "UPDATE " . $this->tbldiscount . " SET code_usage = code_usage + 1 WHERE code = '$code'";
				$result = $this->query($query);
			}
			return true;
		}
        // ----------------------
        // CLOSE SQL
        // ----------------------
        //$this->close();
	}
	
	/**
	 * Update cart with TRANSPORT
	 *
	 * @param 	string	$id 	TRANSPORT ID
	 *
	 * @return void
	 */
	function transport($id) {
		global $sbsanitize, $sbsmarty;
		// -----------------------------
		if (!$id) return false;
		// -----------------------------
		// Initialize
		// -----------------------------
		$transport_id = intval($id);
        // --- Free SQL
        $this->free();
		// -----------------------------
		// Check if code exists AND is always available
		// -----------------------------
		$query   = "SELECT * FROM " . $this->tbltransport . " WHERE id = '$id' AND active = '1'";
		$request = $this->query($query);
		$result  = $this->assoc($request);
		// -----------------------------
		// Check if result
		// -----------------------------
		if (!$result) return false;
		// -----------------------------
		// Initialize SESSION cart_transport
		// -----------------------------
		unset($_SESSION['cart_transport']);
		// -----------------------------
		// --- Instantiate SESSION
		// -----------------------------
		$_SESSION['cart_transport']['id']          = $result['id'];
		$_SESSION['cart_transport']['title']       = $result['title'];
		$_SESSION['cart_transport']['description'] = $result['description'];
		$_SESSION['cart_transport']['type']        = $result['type'];
		$_SESSION['cart_transport']['photo']       = $result['photo'];
		// -----------------------------
		$custom = json_decode($result['custom']);
		// -----------------------------
		// code = 4 : Transport gratuit
		$_SESSION['cart_transport']['forfait_price']      = ($this->getPromo()->code == 4) ? 0 : $custom->forfait_price;
		$_SESSION['cart_transport']['poids_price']        = ($this->getPromo()->code == 4) ? 0 : $custom->poids_price;
		$_SESSION['cart_transport']['relais_price']       = ($this->getPromo()->code == 4) ? 0 : $custom->relais_price;
		$_SESSION['cart_transport']['relais_description'] = $custom->relais_description;
        // ----------------------
        // CLOSE SQL
        // ----------------------
        //$this->close();
	}
	
	/**
	 * Check if product has EKADO
	 *
	 * @return bool
	 */
	function checkEkadoCart() {
		$ekado = false;
		if (!empty($_SESSION["cart"])) {
			foreach($_SESSION["cart"] as $k => $v) {
				if ($v['ekado'] == '1') return true;
			}
		}
		return $ekado;
	}

	/**
	 * Get value EKADO from product in SESSION
	 *
	 * @return string | false
	 */
	function getProductEkado($reference) {
		global $sbsanitize;
		// Check if Cart SESSION is not empty
		if (!empty($_SESSION["cart"])) {
			foreach($_SESSION["cart"] as $k => $v) {
				if ($reference == $k) {
					return $v['ekado'];
				}
			}
		} else {
			return false;
		}
	}

	/**
	 * Get value EKADO from product in SESSION
	 *
	 * @return string | false
	 */
	function getCartNbProducts() {
		global $sbsanitize;
		$nb_products = 0;
		// Check if Cart SESSION is not empty
		if (!empty($_SESSION["cart"])) {
			foreach($_SESSION["cart"] as $k => $v) {
				$nb_products++;
			}
			return $nb_products;
		} else {
			return 0;
		}
	}
	
	/**
	 * Get value from discount SESSION
	 *
	 * @return array (object)
	 */
	function getPromo() {
		return (object) [
			 'code'         => $_SESSION['cart_promo']['code']
			,'code_name'    => $_SESSION['cart_promo']['code_name']
			,'valeur_float' => $_SESSION['cart_promo']['valeur_float']
			,'valeur'       => $_SESSION['cart_promo']['valeur']
			,'type'         => $_SESSION['cart_promo']['type']
			,'limit'        => $_SESSION['cart_promo']['limit']
			,'usage'        => $_SESSION['cart_promo']['usage']
			,'description'  => $_SESSION['cart_promo']['description']
		];
	}
	
	/**
	 * Get value from transport SESSION
	 *
	 * @return array (object)
	 */
	function getTransport() {
		return (object) [
			 'title'               => $_SESSION['cart_transport']['title']
			,'description'         => $_SESSION['cart_transport']['description']
			,'type'                => $_SESSION['cart_transport']['type']
			,'photo'               => $_SESSION['cart_transport']['photo']
			,'forfait_price_float' => $_SESSION['cart_transport']['forfait_price']
			,'forfait_price'       => ($_SESSION['cart_transport']['forfait_price'] > 0) ? $this->addCurrency( $_SESSION['cart_transport']['forfait_price'] ) : $this->addCurrency(0)
			,'poids_price_float'   => $_SESSION['cart_transport']['poids_price']
			,'poids_price'         => ($_SESSION['cart_transport']['poids_price'] > 0) ? $this->addCurrency( $_SESSION['cart_transport']['poids_price'] ) : $this->addCurrency(0)
			,'relais_price_float'  => $_SESSION['cart_transport']['relais_price']
			,'relais_price'        => ($_SESSION['cart_transport']['relais_price'] > 0) ? $this->addCurrency( $_SESSION['cart_transport']['relais_price'] ) : $this->addCurrency(0)
			,'relais_description'  => $_SESSION['cart_transport']['relais_description']
		];
	}
	
	/**
	 * Get Payment informations
	 *
	 * @param	integer		$id		Payment ID
	 *
	 * @return array
	 */
	function getPayment($id, $field = '') {
		global $sbsanitize;
		// --- Initialization
		$field = ($field != '') ? $sbsanitize->stopXSS($field) : "*";
		$id      = intval($id);
		
        // --- Free SQL
        $this->free();
        $sql     = "SELECT $field FROM " . $this->tblpayment . " WHERE id = '$id'";
        $result  = $this->query($sql);
        $payment = $this->assoc($result);
		
		// --- Check if exists
		if ($payment) {
			if ($field == "*") {
				// All infos about PAYMENT
				return $payment;
			} elseif ($payment[$field]) {
				// Field info about PAYMENT
				return $payment[$field];
			} else {
				// No info
				return false;
			}
		} else {
			// Payment don't exists
			return false;
		}
        // ----------------------
        // CLOSE SQL
        // ----------------------
        //$this->close();
	}
	
	/**
	 * Get TOTAL TTC less DISCOUNT
	 *
	 * @param	float	$total_price_ttc	Total price TTC (Cart)
	 * @param	string	$float				return float number OR not
	 * @param	bool	$total 				return total: true | only discount: false
	 *
	 * @return float / float with currency
	 */
	function calculatePromo($total_price_ttc, $float = false, $total = false) {
		// Switch Calculation with TYPE of Discount (code)
		switch( $this->getPromo()->code ) {
			case "1": // Remise sur un produit
			case "2": // Remise sur le panier
				$discount        = $this->getPromo()->valeur_float;
				$total_discount  = $total_price_ttc - $discount;
			break;
			case "3": // Remise en pourcentage
				$discount       = $total_price_ttc * ( $this->getPromo()->valeur_float / 100 );
				$total_discount = $total_price_ttc - $discount;
			break;
			case "4": // Livraison gratuite
				$discount       = 0;
				$total_discount = $total_price_ttc - $discount;
			break;
		}
		// Return discount infos
		$return_discount = ($total) ? $total_discount : $discount;
		return ($float) ? $return_discount : $this->addCurrency($return_discount);
	}
	
	/**
	 * Empty cart
	 *
	 * @return void
	 */
	function cartEmpty() {
		unset($_SESSION["cart"]);               // Panier
		unset($_SESSION["cart_promo"]);         // Codes promo
		unset($_SESSION["cart_transport"]);     // Transport ID
		unset($_SESSION['cart_oid']);           // Order ID
		unset($_SESSION['sbaccount_redirect']); // Redirect if not connected
	}
	
	/**
	 * Get products in cart (From SESSION cart)
	 *
	 * @return array
	 */
	function getProducts() {
		// --- Initialize
		$cart_item = [];
		// --- Check if products in cart
		if (isset($_SESSION["cart"])) {
			foreach ($_SESSION["cart"] as $key => $item) {
				$cart_item[$key]['id']                = $item["id"];
				$cart_item[$key]['ekado']             = $item["ekado"];
				$cart_item[$key]['title']             = $item["title"];
				$cart_item[$key]['reference']         = $item["reference"];
				$cart_item[$key]['quantity']          = $item["quantity"];
				$cart_item[$key]['catid']             = $item["catid"];
				$cart_item[$key]['tag']               = $item["tag"];
				$cart_item[$key]['description']       = $item["description"];
				$cart_item[$key]['type']              = $item["type"];
				$cart_item[$key]['price_float']       = $item["price"];
				$cart_item[$key]['price']             = $this->addCurrency($item["price"]);
				//$cart_item[$key]['total_price_float'] = $item["quantity"] * $item["price"];
				$cart_item[$key]['total_price_float'] = number_format( $item["quantity"] * $item["price"], 2, ".", "" );  // Bug des decimales sur serveur PROD
				$cart_item[$key]['total_price']       = $this->addCurrency($item["quantity"] * $item["price"]);
				$cart_item[$key]['photo']             = $item["photo"];
			}
		}
		return ( array_keys( $cart_item, true ) ) ? $cart_item : false;
	}
	
	/**
	 * Get total cart with or without PORT / TVA
	 *
	 * @param	bool	$tva		+ TVA
	 * @param	bool	$shipping 	+ PORT (livraison)
	 *
	 * @return object
	 */
	function getCartTotal($tva = false, $shipping = false) {
		// --- Check if products in cart
		if (isset($_SESSION["cart"])) {
			$this->total_quantity = 0;
			$this->total_price_ht = 0;
			foreach ($_SESSION["cart"] as $item) {
				$this->total_quantity += $item["quantity"];
				$this->total_price_ht += ( $item["price"] * $item["quantity"] );
			}
		}
		
		switch($this->config->is_tva) {
			case "1": // 1: TVA activée (déjà incluse dans le prix du produit - prix en TTC sur la fiche produit)
				$this->total_price_ttc = $this->total_price_ht;
				$this->total_price_ht  = $this->total_price_ht * 100 / (100 + $this->TVA);
			break;
			case "2": // 2: TVA activée (non incluse dans le prix du produit - prix en HT sur la fiche produit)
				$this->total_price_ttc = $this->total_price_ht;
				$this->total_price_ht  = $this->total_price_ht;
			break;
			case "3": // 3: TVA activée (à inclure dans le prix du produit - prix en TTC sur la fiche produit)
				$this->total_price_ttc = $this->total_price_ht;
				$this->total_price_ht  = $this->total_price_ht * 100 / (100 + $this->TVA);
			break;
		}
		
		return (object) [
			 'total_quantity'                 => $this->total_quantity
			,'cart_total_price_ht_float'      => $this->total_price_ht
			,'cart_total_price_ht'            => $this->addCurrency($this->total_price_ht)
			,'cart_total_price_ttc_float'     => $this->total_price_ttc
			,'cart_total_price_ttc'           => $this->addCurrency($this->total_price_ttc)
			,'cart_total_discount_float'      => $this->getPromo()->valeur_float
			,'cart_total_discount'            => $this->getPromo()->valeur
			,'cart_promo_type'                => $this->getPromo()->type
			,'cart_promo_description'         => $this->getPromo()->description
		];
	}
	
	/**
	 * Get Total Cart with TRANSPORT less DISCOUNT
	 *
	 * @return object
	 */
	function getTotalCartWithTransportLessDiscount() {
		// --------------------
		// Transport
		// --------------------
		switch($this->getTransport()->type) {
			case "forfait":
				$transport_price_T = $this->getTransport()->forfait_price_float;
			break;
			case "poids":
				$transport_price_T = $this->getTransport()->poids_price_float;
			break;
			case "relais":
				$transport_price_T = $this->getTransport()->relais_price_float;
			break;
		}
		// --------------------
		// Cart
		// --------------------
		$get_cart_T = $this->getCartTotal();
		$get_tva_T  = $this->getCartTVA( $get_cart_T->cart_total_price_ht_float );
		// --------------------
		// TVA
		// --------------------
		$cart_total_tva_T = $get_cart_T->cart_total_price_ht_float + $get_tva_T->cart_tva_total_price_float;
		// --------------------
		// Promos
		// --------------------
		$cart_total_discount_T = ($this->getPromo()->code == 3) ? $this->calculatePromo($cart_total_tva_T, 'float', false) : $get_cart_T->cart_total_discount_float;
		// --------------------
		// ---    TOTAL     ---
		// --------------------
		$total = ($cart_total_tva_T - $cart_total_discount_T) + $transport_price_T;
		$total = number_format( $total, 2, ".", "" ); // Bug des decimales sur serveur PROD
		$total_currency = $this->addCurrency( $total );
		// --------------------
		// --- Return price (FLOAT + FLOAT with CURRENCY)
		// --------------------
		return (object) [
			 'total'          => $total
			,'total_currency' => $total_currency
		];
	}
	
	/**
	 * Get total cart TVA (Only)
	 * 
	 * @return object
	 */
	function getCartTVA($total_price) {
		// TVA Calcul
		switch($this->config->is_tva) {
			default:
			case "0": // 0: Pas de TVA
				$_total_tva = 0;
			break;
			case "1": // 1: TVA activée (déjà incluse dans le prix du produit - prix en TTC sur la fiche produit)
				// Ex: TVA 20%, total 50EUR => 50*100/(100+20)
				$_total_tva = $total_price * ($this->TVA / 100);
			break;
			case "2": // 2: TVA activée (non incluse dans le prix du produit - prix en HT sur la fiche produit)
			case "3": // 3: TVA activée (à inclure dans le prix du produit - prix en TTC sur la fiche produit)
				$_total_tva = $total_price * ($this->TVA / 100);
			break;
		}

		return (object) [
			 'cart_is_tva'                => $this->config->is_tva
			,'cart_tva_taux'              => $this->TVA
			,'cart_tva_total_price_float' => $this->decimalsFloat( $_total_tva, $this->n_decimals )
			,'cart_tva_total_price'       => $this->addCurrency( $_total_tva )
		];
	}
	
	/**
	 * Get total cart TVA (Only)
	 * 
	 * @return object
	 */
	function getProductCartTVA($price) {
		// 3: TVA activée (à inclure dans le prix du produit - prix en TTC sur la fiche produit)
		$_product_tva       = $price * ($this->TVA / 100);
		$_product_width_tva = $price + $_product_tva;

		return $_product_width_tva;
	}
	
	/**
	 * Get order by ID
	 *
	 * @param	integer		$oid 	Order ID
	 * 
	 * @return object
	 */
	function getOrder($oid) {
		$oid = intval($oid);
        // --- Free SQL
        $this->free();
		// Get order
		$query_order   = "SELECT * FROM " . $this->tblorder . " WHERE id = '$oid'";
		$request_order = $this->query($query_order);
		$order         = $this->assoc($request_order);
        // ----------------------
        // CLOSE SQL
        // ----------------------
        //$this->close();

		return (object) $order;
	}

	/**
	 * Get order detail by ID
	 *
	 * @param	integer		$oid 	Order ID
	 * 
	 * @return object
	 */
	function getOrderDetail($oid) {
		$oid           = intval($oid);
        // --- Free SQL
        $this->free();
		// Get order
		$query_order   = "SELECT * FROM " . $this->tblorderdetail . " WHERE oid = '$oid'";
		$request_order = $this->query($query_order);
		$order_detail  = $this->toarray($request_order);
        // ----------------------
        // CLOSE SQL
        // ----------------------
        //$this->close();

		return $order_detail;
	}
	
	/**
	 * Get Client Shipping Address
	 *
	 * @param
	 *
	 * @return string (Shipping Adress)
	 */
	function getClientShipping($user_info) {
		$client_shipping = "";
		$transport       = $this->getTransport();
		
		switch($transport->type) {
			case "forfait":
				if ($user_info['shipping_same'] == 1) {
					// --- Adresse de facturation = livraison
    				$client_shipping .= $user_info['username'] . '<br>';
					$client_shipping .= $user_info['address'] . '<br>';
					if ($user_info['address_2'] != '') $client_shipping .= $user_info['address_2'] . '<br>';
					$client_shipping .= $user_info['zip'] . ' ' . $user_info['city'] . '<br>';
					$client_shipping .= $this->getCountryName( $user_info['country'] );
				} else {
					// --- Adresse de livraison
    				$client_shipping .= $user_info['shipping_firstname'] . ' ' . $user_info['shipping_lastname'] . '<br>';
					$client_shipping .= $user_info['shipping_address'] . '<br>';
					if ($user_info['shipping_address_2'] != '') $client_shipping .= $user_info['shipping_address_2'] . '<br>';
					$client_shipping .= $user_info['shipping_zip'] . ' ' . $user_info['shipping_city'] . '<br>';
					$client_shipping .= $this->getCountryName( $user_info['shipping_country'] );
				}
			break;
			case "poids":
				if ($user_info['shipping_same'] == 1) {
					// --- Adresse de facturation = livraison
    				$client_shipping .= $user_info['username'] . '<br>';
					$client_shipping .= $user_info['address'] . '<br>';
					if ($user_info['address_2'] != '') $client_shipping .= $user_info['address_2'] . '<br>';
					$client_shipping .= $user_info['zip'] . ' ' . $user_info['city'] . '<br>';
					$client_shipping .= $this->getCountryName( $user_info['country'] );
				} else {
					// --- Adresse de livraison
                    $client_shipping .= $user_info['shipping_firstname'] . ' ' . $user_info['shipping_lastname'] . '<br>';
					$client_shipping .= $user_info['shipping_address'] . '<br>';
					if ($user_info['shipping_address_2'] != '') $client_shipping .= $user_info['shipping_address_2'] . '<br>';
					$client_shipping .= $user_info['shipping_zip'] . ' ' . $user_info['shipping_city'] . '<br>';
					$client_shipping .= $this->getCountryName( $user_info['shipping_country'] );
				}
			break;
			case "relais":
				$client_shipping .= 'Point Relais : ' . $transport->title . '<br>';
				$client_shipping .= $transport->description . '<br>';
				$client_shipping .= $transport->relais_description;
			break;
		}
		return $client_shipping;
	}
	
	/**
	 * Get Order Detail (Email)
	 *
	 * @param	integer		$oid	Order ID
	 *
	 * @return string	Order detail TABLE
	 */
	function getOrderEmail($oid) {
		global $sbsql, $sbsanitize, $sbsmarty;
		// -------------------------------
		// --- Initialize
		// -------------------------------
		$cart_total_ht             = 0;
		$total_quantity_products   = 0;
		$transport_title           = "";
		$transport_price           = 0;
		$session_cart_promo        = false;
		$session_promo_code        = "";
		$cart_promo_type           = "";
		$cart_promo_description    = "";
		$cart_total_discount       = 0;
		$percent_value             = 0;
		$cart_total_less_discount  = 0;
		$cart_total_discount       = 0;
		$cart_total_with_transport = 0;
		// -------------------------------
		// --- Order infos
		// -------------------------------
		$order        = $this->getOrder($oid);
		$order_detail = $this->getOrderDetail($oid);
		// -------------------------------
		
		// -------------------------------
		$order_email .= '<style>
							table.table, table.table th, table.table td {
								border: 1px solid lightgrey;
								border-collapse: collapse;
							}
							table.table th, table.table td {
								padding: 5px;
								text-align: left;
							}
						 </style>';
		// -------------------------------
		// --- Table HEAD
		// -------------------------------
		$order_email .= '<table class="table" style="width:100%">';
			$order_email .= '<thead>';
				$order_email .= '<tr>';
					$order_email .= '<th>Ref.</th>';
					$order_email .= '<th>Produit</th>';
					$order_email .= '<th>Quantit&eacute;</th>';
					$order_email .= '<th>P.U.</th>';
					$order_email .= '<th style="text-align: center;">Total</th>';
				$order_email .= '</tr>';
			$order_email .= '</thead>';
			$order_email .= '<tbody>';
		// -------------------------------
		// --- Table BODY
		// -------------------------------
		// ---------- PRODUITS -----------
		// -------------------------------
		if (!$order_detail) {
			$order_email .= '<tr>';
				$order_email .= '<td colspan="4">Aucun produit dans cette commande</td>';
			$order_email .= '</tr>';
		}
		foreach($order_detail as $product) {
			$order_email .= '<tr>';
				$order_email .= '<td>' . $product['reference'] . '</td>';
				$order_email .= '<td>' . $product['title'] . '</td>';
				$order_email .= '<td style="text-align: center;">';
					$order_email .= 'x ' . $product['quantity'];
					$total_quantity_products = $total_quantity_products + $product['quantity'];
				$order_email .= '</td>';
				$order_email .= '<td style="text-align: center;">' . number_format( $product['price'], 2, ",", " " ) . '</td>';
				$order_email .= '<td style="text-align: right;">';
					$order_email .= number_format( $product['total_price'], 2, ",", " " );
					$cart_total_ht = $cart_total_ht + $product['total_price'];
				$order_email .= '</td>';
			$order_email .= '</tr>';
			// Check if transport
			if ($product['transport_title'] != '') {
				$transport_title = $product['transport_title'];
				$transport_price = $product['transport_price'];
			}
			// Check if promo
			if ($product['promo_code'] != '') {
				$session_cart_promo     = true;
				$session_promo_code     = $product['promo_code'];
				$cart_promo_type        = $product['promo_type'];
				$cart_promo_description = $product['promo_description'];
				$cart_total_discount    = $product['promo_price'];
			}
		}

			$order_email .= '</tbody>';
		$order_email .= '</table>';
		// -------------------------------
		// ----------- TOTAUX ------------
		// -------------------------------
		$order_email .= '<table class="table table-totals" style="width:100%">';
			$order_email .= '<tbody>';
				$order_email .= '<tr>';
					$order_email .= '<td>Sous-total produits HT</td>';
					$order_email .= '<td style="text-align: right;">' . number_format( $cart_total_ht, 2, ",", " " ) . '</td>';
				$order_email .= '</tr>';

				$order_email .= '<tr>';
					$order_email .= '<td>Pas de TVA</td>';
					$order_email .= '<td style="text-align: right;">0,00</td>';
				$order_email .= '</tr>';
			$order_email .= '</tbody>';
			$order_email .= '<tfoot>';
				$order_email .= '<tr>';
					$order_email .= '<td>';
						$order_email .= 'Sous Total Commande TTC';
					$order_email .= '</td>';
					$order_email .= '<td style="text-align: right;">' . number_format( $cart_total_ht, 2, ",", " " ) . '</td>';
				$order_email .= '</tr>';
		// -------------------------------
		// ----------- TOTAUX ------------
		// -------------------------------
		if ($session_cart_promo == true && $total_quantity_products > 0) {
				$order_email .= '<tr>';
				if ($cart_promo_type == 3) {
					$percent_value            = (100-$cart_total_discount)/100;
					$cart_total_less_discount = $cart_total_ht*$percent_value;
					$cart_total_discount      = $cart_total_ht-$cart_total_less_discount;
				} else {
					$cart_total_less_discount = $cart_total_ht-$cart_total_discount;
				}

					$order_email .= '<td class="discount-td logo-color-green">';
						$order_email .= '<em style="color: red;">' . $cart_promo_description . '</em>';
						$order_email .= '<br>';
						$order_email .= 'Code : ' . $session_promo_code;
					$order_email .= '</td>';
					$order_email .= '<td style="text-align: right;">';
						$order_email .= '- ' . number_format( $cart_total_discount, 2, ",", " " );
					$order_email .= '</td>';
				$order_email .= '</tr>';
				$order_email .= '<tr>';
					$order_email .= '<td>';
						$order_email .= 'Total avec remise TTC';
					$order_email .= '</td>';
					$order_email .= '<td style="text-align: right;">';
						$order_email .= number_format( $cart_total_less_discount, 2, ",", " " );
					$order_email .= '</td>';
				$order_email .= '</tr>';
		} else {
			$cart_total_less_discount = $cart_total_ht;
		}
		
		if ($total_quantity_products > 0) {
				$order_email .= '<tr>';
					$order_email .= '<td class="discount-td logo-color-green">';
					if ($session_cart_promo == "true" && $session_promo_code == 4) {
						$order_email .= '<strong class="logo-color-red">Livraison gratuite</strong>';
					} else {
						$order_email .= 'Frais de livraison';
					}
						$order_email .= '<br>';
						$order_email .= '<em style="color: grey;">' . $transport_title . '</em>';
					$order_email .= '</td>';
					$order_email .= '<td style="text-align: right;">';
						$order_email .= '+ ' . number_format( $transport_price, 2, ",", " " );
					$order_email .= '</td>';
				$order_email .= '</tr>';
				$order_email .= '<tr>';
					$order_email .= '<td style="font-size: 1.2em; font-weight: bold;">Total commande TTC</td>';
					$order_email .= '<td style="font-size: 1.2em; text-align: right; font-weight: bold;">';
						$cart_total_with_transport = $cart_total_less_discount+$transport_price;
						$order_email .= number_format( $cart_total_with_transport, 2, ",", " " );
					$order_email .= '</td>';
				$order_email .= '</tr>';
		}
		
			$order_email .= '</tfoot>';
		$order_email .= '</table>';
		// -------------------------------
		// ---------- LIVRAISON ----------
		// -------------------------------
		$order_email .= '<table class="table" style="width:100%">';
			$order_email .= '<tbody>';
				$order_email .= '<tr>';
					$order_email .= '<td>Adresse de livraison</td>';
					$order_email .= '<td style="text-align: right;">' . $order->client_shipping . '</td>';
				$order_email .= '</tr>';
			$order_email .= '</tbody>';
		$order_email .= '</table>';
		
		return $order_email;
	}
	
	/**
	 * Get country name by CODE
	 *
	 * @param	string	$code 	COUNTRY CODE (2 positions)
	 * 
	 * @return string (Country Name)
	 */
	function getCountryName($code) {
		global $sbsql, $sbsanitize, $sbsmarty;
		$table = _AM_DB_PREFIX . 'sb_shop_currency_country';
		$code  = $sbsanitize->stopXSS($code);
        // --- Free SQL
        $sbsql->free();
		// --------------------------------
		$query   = "SELECT country FROM $table WHERE country_code = '$code'";
		$request = $sbsql->query($query);
		$result  = $sbsql->assoc($request);
        // ----------------------
        // CLOSE SQL
        // ----------------------
        //$sbsql->close();
		// --------------------------------
		return $result['country'];
		// --------------------------------
	}
	
	/**
	 * Get number format with X decimals (SHOP configuration)
	 *
	 * @param	float		$n 				Float number
	 * @param 	integer		$n_decimals		Decimals number
	 *
	 * @return float
	 */
	function decimalsFloat($n, $n_decimals) {
		return ( (floor($n) == round($n, $n_decimals)) ? number_format($n) : number_format($n, $n_decimals) );
	}
	
	/**
	 * Return price with currency symbol
	 *
	 * @param	float	$price 		Product price
	 *
	 * @return string
	 */
	function addCurrency($price) {
		// --------------------------------
		// --- Show currency
		// --------------------------------
		if ($this->config->currency_position) {
			// Behind (Apres)
			return number_format($price, $this->config->n_decimals, ",", " ") . $this->config->currency;
		} else {
			// Before (Avant)
			return $this->config->currency . number_format($price, $this->config->n_decimals, ",", " ");
		}
	}
	
	/**
	 * Get Shop configuration
	 *
	 * @return object
	 */
	function getConfShop() {
        // --- Free SQL
        $this->free();
		$query_conf   = "SELECT * FROM " . $this->tblshopconfig . " WHERE id = '1'";
		$request_conf = $this->query($query_conf);
		$config       = $this->assoc($request_conf);
        // ----------------------
        // CLOSE SQL
        // ----------------------
        //$this->close();
		
		$configuration = [
			 'tva'               => $config['tva']
			,'is_tva'            => $config['is_tva']
			,'n_decimals'        => $config['n_decimals']
			,'currency'          => $config['currency']
			,'currency_text'     => $config['currency_text']
			,'currency_position' => $config['currency_position']
			,'banner'            => $config['banner']
			,'pub'               => $config['pub']
		];
		
		return (object) $configuration;
	}
	
	/**
	 * Get product informations
	 *
	 * @param	integer		$pid 	Product ID
	 * @param	string		$field	Field name (SQL TBL)
	 *
	 * @return string
	 */
	function getProductInfo($pid, $field = '') {
		global $sbsanitize;
		// --- Initialization
		$field = ($field != '') ? $sbsanitize->stopXSS($field) : "*";
		$pid   = intval($pid);
        // --- Free SQL
        $this->free();
        $sql     = "SELECT $field FROM " . $this->tblproduct . " WHERE id = '$pid'";
        $result  = $this->query($sql);
        $product = $this->assoc($result);
        // ----------------------
        // CLOSE SQL
        // ----------------------
        //$this->close();
		
		// --- Check if exists
		if ($product) {
			if ($field == "*") {
				// All infos about PRODUCT
				return $product;
			} elseif ($product[$field]) {
				// Field info about PRODUCT
				return $product[$field];
			} else {
				// No info
				return false;
			}
		} else {
			// Product don't exists
			return false;
		}
	}
	
	/**
	 * Get product real price
	 *
	 * @param	integer		$pid 	Product ID
	 *
	 * @return string
	 */
	function getProductPrice($pid) {
        // Get product infos
        $product       = $this->getProductInfo($pid);
		$is_discount   = $this->isDiscount($product['type']);
		$product_price = 0;
		
		if ( isset($product['price_reduced']) && $product['price_reduced'] > 0 ) {
			// return PRICE REDUCED
			$product_price = ($this->config->is_tva == 3) ? $this->getProductCartTVA($product['price_reduced']) : $product['price_reduced'];
		} elseif ( $is_discount ) {
			// return PRICE less DISCOUNT
			if ($this->config->is_tva == 3) {
				$product_price = $this->calculPriceDiscount( $this->getProductCartTVA($product['price']), $product['type']);
			} else {
				$product_price = $this->calculPriceDiscount( $product['price'], $product['type']);
			}
		} else {
			// No discount, no price reduced, return PRICE Product
			$product_price = ($this->config->is_tva == 3) ? $this->getProductCartTVA($product['price']) : $product['price'];
		}
		
		return $product_price;
	}
	
	/**
	 * Check if is discount by type
	 *
	 * @param	string		$type 	Product Type (Category)
	 *
	 * @return bool
	 */
	function isDiscount($type) {
		global $sbsanitize;
		// Product type
		$type     = $sbsanitize->sTrim( $type );
        // --- Free SQL
        $this->free();
		// Get product reduction by group
		$sql      = "SELECT discount FROM " . $this->tbldiscount . " WHERE type = '$type'";
		$result   = $this->query($sql);
		$discount = $this->assoc($result);
        // ----------------------
        // CLOSE SQL
        // ----------------------
        //$this->close();
		
		return ($discount['discount']) ? true : false;
	}

	/**
	 * Calculation of product real price LESS discount
	 *
	 * @param	float		$price 	Product price
	 * @param	string		$type 	Product type (Category)
	 *
	 * @return float
	 */
	function calculPriceDiscount($price, $type) {
		global $sbsanitize;
		// Discount type
		$type = $sbsanitize->sTrim( $type ); // Ex: cheque, box, vegetable, chair, shoes, ...
        // --- Free SQL
        $this->free();
		// Get product reduction by group (Type)
		$sql      = "SELECT discount, method FROM " . $this->tbldiscount . " WHERE type = '$type'";
		$result   = $this->query($sql);
		$discount = $this->assoc($result);
        // ----------------------
        // CLOSE SQL
        // ----------------------
        //$this->close();
        
		$real_price = 0;
		switch($discount['method']) {
			case "percent":
				$percent    = (100 - $discount['discount']) / 100;
				$real_price = $price * $percent;
			break;
			case "less":
				$real_price = $price - $discount['discount'];
			break;
		}
		
		return $real_price;
	}
	
}

?>
