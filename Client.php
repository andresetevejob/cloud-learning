<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/MY_REST.php';

class Client extends MY_REST {

    public function __construct() {
        // Construct our parent class
        parent::__construct();
        $this->load->library("JWT");
        // Configure limits on our controller methods. Ensure
        // you have created the 'limits' table and enabled 'limits'
        // within application/config/rest.php
        $this->load->model('Client_model', 'client');
        $this->methods['user_get']['limit'] = 500; //500 requests per hour per user/key
        $this->methods['user_post']['limit'] = 100; //100 requests per hour per user/key
        $this->methods['user_delete']['limit'] = 50; //50 requests per hour per user/key
    }

    /**
     * Recuperer la liste des clients
     * par nom
     */
    public function searchClientInfos_get() {
        //verifier le nombre d'arguments de la requete
        if (count($this->_get_args)>= 2) {
            $nom =  $this->_get_args['nom'];
            $start = (int) $this->_get_args['start'];
            $limit = (int) $this->_get_args['limit'];
            $this->client->search($nom,$start,$limit);
        }
    }

    /**
     * Retourne les informations concernant le client
     * @param type $ID
     */
    private function clientDetails_get() {
        $ID = (int) $this->_get_args['clientID'];
        if ($ID > 0) {
            $clientInfos = $this->client->getByProperties(array('id' => $ID), "id,nom,prenoms,email,datenaissance");
            $success['token'] = $this->generateToken("kkk");
            $success['clientInfos'] = $clientInfos;
            $this->response($success, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'client could not be found'), 404);
        }
    }
    

}
