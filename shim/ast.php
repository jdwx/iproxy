<?php /** @noinspection PhpMissingFieldTypeInspection */


namespace ast;


const AST_STMT_LIST = 0;
const AST_USE = 1;
const AST_NAMESPACE = 2;
const AST_CLASS = 3;
const AST_DECLARE = 4;
const AST_METHOD = 5;
const AST_NULLABLE_TYPE = 6;
const AST_NAME = 7;
const AST_USE_ELEM = 8;


class Node {
    public $kind;
    public $flags;
    public $lineno;
    public $children;
}


function get_kind_name( int $kind ) : string {
    return "AST_$kind";
}

function get_metadata() : array {
    return [];
}


/** @noinspection PhpUnusedParameterInspection */
function kind_uses_flags( int $kind ) : bool {
    return false;
}


/** @noinspection PhpUnusedParameterInspection */
function parse_code( string $code, int $version, int $flags = 0 ) : Node {
    return new Node();
}

