<?php


declare( strict_types = 1 );


namespace JDWX\DAOS\DB;


interface IExample1 {


    public function getAlpha() : string;


    public function getBeta() : int;


    public function getGamma() : ?string;


    public function getDelta() : ?int;


    public function getEpsilon() : ?IExample1;


    public function setZeta( ?string $i_nstEta ) : void;


    public function updateTheta( string $i_stIota, string $i_stKappa ) : bool;


}
