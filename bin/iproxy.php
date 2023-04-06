<?php


use ast\flags;


function BuildProxy( ast\Node $i_rootNode ) : void {
    if ( $i_rootNode->kind != ast\AST_STMT_LIST ) {
        throw new Exception( "Root node is not AST_STMT_LIST" );
    }
    $nstNamespace = null;
    echo "<?php\n\n\n";
    $bLastWasUse = false;
    foreach ( $i_rootNode->children as $child ) {
        if ( $child->kind == ast\AST_USE ) {
            BuildProxyUse( $child );
            $bLastWasUse = true;
            continue;
        }
        if ( $bLastWasUse ) {
            echo "\n\n";
            $bLastWasUse = false;
        }
        if ( $child->kind == ast\AST_NAMESPACE ) {
            if ( $nstNamespace !== null )
                throw new Exception( "Multiple namespaces in file" );
            $nstNamespace = $child->children[ 'name' ];
            echo "namespace ", $nstNamespace, ";\n\n\n";
        } elseif ( $child->kind == ast\AST_CLASS && $child->flags & flags\CLASS_INTERFACE ) {
            BuildProxyInterface( $child );
        } elseif ( $child->kind ==  ast\AST_DECLARE ) {
            BuildProxyDeclare( $child );
        }
    }
}


function BuildProxyDeclare( ast\Node $i_declare ) : void {
    foreach ( $i_declare->children[ 'declares' ]->children as $declare ) {
        echo "declare( ", $declare->children[ 'name' ], " = ", $declare->children[ 'value' ], " );\n\n\n";
    }
}


function BuildProxyInterface( ast\Node $i_interface ) : void {
    $stInterface = $i_interface->children[ 'name' ];
    $stName = $stInterface;
    if ( $stName[ 0 ] == 'I' && ctype_upper( $stName[ 1 ] ) ) {
        $stName = substr( $stName, 1 );
    }
    $stName = str_replace( "Interface", "", $stName );
    echo "trait T{$stName}Proxy {\n\n\n";
    echo "    private ", $stInterface, " \$proxy{$stName};\n\n\n";
    foreach ( $i_interface->children[ 'stmts' ]->children as $stmt ) {
        if ( $stmt->kind == ast\AST_METHOD ) {
            BuildProxyMethod( $stName, $stmt );
        }
    }
    echo "    protected function setProxy{$stName}( ", $stInterface, " \$i_proxy{$stName} ) : void {\n";
    echo "        \$this->proxy{$stName} = \$i_proxy{$stName};\n";
    echo "    }\n\n\n";
    echo "}\n";
}


function BuildProxyMethod( string $i_stName, ast\Node $i_method ) : void {
    if ( $i_method->flags & flags\MODIFIER_STATIC ) {
        return;
    }
    if ( $i_method->flags & flags\MODIFIER_PRIVATE ) {
        return;
    }
    if ( $i_method->flags & flags\MODIFIER_PROTECTED ) {
        return;
    }
    $stMethod = $i_method->children[ 'name' ];
    echo "    public function {$stMethod}(";
    $rParams = [];
    if ( ! empty( $i_method->children[ 'params' ]->children ) ) {
        $bFirst = true;
        foreach ( $i_method->children[ 'params' ]->children as $param ) {
            $stName = $param->children[ 'name' ];
            if ( $bFirst ) {
                $bFirst = false;
            } else {
                echo ", ";
            }
            echo " ";
            if ( $param->children[ 'type' ] ) {
                $type = BuildProxyType( $param->children[ 'type' ] );
                echo $type, " ";
            }
            echo "\$", $stName;
            $rParams[] = "\$" . $stName;
        }
        echo " ";
    }
    echo ")";
    $type = null;
    if ( array_key_exists( 'returnType', $i_method->children ) ) {
        $type = BuildProxyType( $i_method->children[ 'returnType' ] );
        echo " : ", $type;
    }
    $bVoid = $type === 'void';
    echo " {\n";
    echo "        ", $bVoid ? "" : "return " ,"\$this->proxy{$i_stName}->{$stMethod}(";
    if ( ! empty( $rParams ) ) {
        echo " ", implode( ", ", $rParams ), " ";
    }
    echo ");\n";
    echo "    }\n\n\n";
}


function BuildProxyType( ast\Node $i_type ) : string {
    # echo ast_dump( $i_type ), "\n";
    $rt = $i_type;
    if ( $rt->kind == ast\AST_NULLABLE_TYPE ) {
        $type = BuildProxyType( $rt->children[ 'type' ] );
        if ( $type == "mixed" ) {
            return "mixed";
        }
        if ( str_contains( $type, "|" ) ) {
            return "({$type})|null";
        }
        return "?{$type}";
    }
    if ( $rt->kind == ast\AST_NAME ) {
        $st = $rt->children[ 'name' ];
        if ( ! ( $rt->flags & flags\NAME_NOT_FQ ) ) {
            $st = "\\" . $st;
        }
        return $st;
    }
    $type = match( $rt->flags ) {
        flags\TYPE_ARRAY => "array",
        flags\TYPE_CALLABLE => "callable",
        flags\TYPE_BOOL => "bool",
        flags\TYPE_LONG => "int",
        flags\TYPE_STRING => "string",
        flags\TYPE_DOUBLE => "float",
        flags\TYPE_ITERABLE => "iterable",
        flags\TYPE_OBJECT => "object",
        flags\TYPE_VOID => "void",
        flags\TYPE_MIXED => "mixed",
        flags\TYPE_NULL => "null",
        flags\TYPE_TRUE => "true",
        flags\TYPE_FALSE => "false",
        flags\TYPE_NEVER => "never",
        default => "unknown",
    };
    if ( $type == "unknown" ) {
        echo "Unknown: ", ast_dump( $rt ), "\n";
    }
    return $type;
}


function BuildProxyUse( ast\Node $i_use ) : void {
    if ( ! ( $i_use->flags & flags\USE_NORMAL ) ) {
        return;
    }
    echo "use ";
    $rUse = [];
    foreach ( $i_use->children as $use ) {
        if ( $use->kind != ast\AST_USE_ELEM ) {
            continue;
        }
        $stName = $use->children[ 'name' ];
        if ( $use->children[ 'alias' ] ) {
            $stName .= " as " . $use->children[ 'alias' ];
        }
        $rUse[] = $stName;
    }
    echo implode( ", ", $rUse ), ";\n";
    # echo ast_dump( $i_use ), "\n";
}


$stCommand = array_shift( $argv );
foreach ( $argv as $arg ) {
    $php = file_get_contents( $arg );
    $x = ast\parse_code( $php, $version = 80 );
    BuildProxy( $x );
}

