<?php 

class ProfileController {
    private $clientModel;
    private $providerModel;

    public function __construct() {
        $this->clientModel = new ClientModel();
        $this->providerModel = new ProviderModel();
    }

    public function view(){
        session_start();
    }
}
