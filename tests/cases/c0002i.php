<?php


class ExampleClass {
}


interface InwardInterface {


    public function getInward() : string;


    public function setInward( string $i_nstInward ) : void;


    public function exampleMethod( ExampleClass $i_nstExampleClass ) : void;


    public function getExample() : ExampleClass;


}
