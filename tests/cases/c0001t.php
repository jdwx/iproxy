<?php


declare( strict_types = 1 );


namespace JDWX\DAOS\DB;


trait TExample1Proxy {


    private IExample1 $proxyExample1;


    public function getAlpha() : string {
        return $this->proxyExample1->getAlpha();
    }


    public function getBeta() : int {
        return $this->proxyExample1->getBeta();
    }


    public function getGamma() : ?string {
        return $this->proxyExample1->getGamma();
    }


    public function getDelta() : ?int {
        return $this->proxyExample1->getDelta();
    }


    public function getEpsilon() : ?IExample1 {
        return $this->proxyExample1->getEpsilon();
    }


    public function setZeta( ?string $i_nstEta ) : void {
        $this->proxyExample1->setZeta( $i_nstEta );
    }


    public function updateTheta( string $i_stIota, string $i_stKappa ) : bool {
        return $this->proxyExample1->updateTheta( $i_stIota, $i_stKappa );
    }


    protected function setProxyExample1( IExample1 $i_proxyExample1 ) : void {
        $this->proxyExample1 = $i_proxyExample1;
    }


}
