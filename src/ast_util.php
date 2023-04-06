<?php

/* These are taken from https://github.com/nikic/php-ast/blob/master/util.php.

Copyright (c) 2014 by Nikita Popov.

Some rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are
met:

 * Redistributions of source code must retain the above copyright
notice, this list of conditions and the following disclaimer.

 * Redistributions in binary form must reproduce the above
copyright notice, this list of conditions and the following
disclaimer in the documentation and/or other materials provided
with the distribution.

 * The names of the contributors may not be used to endorse or
promote products derived from this software without specific
prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
"AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

 */


const AST_DUMP_LINENOS = 1;
const AST_DUMP_EXCLUDE_DOC_COMMENT = 2;


function get_flag_info() : array {
    static $info;
    if ( $info !== null ) {
        return $info;
    }

    foreach ( ast\get_metadata() as $data ) {
        if ( empty( $data->flags ) ) {
            continue;
        }

        $flagMap = [];
        foreach ( $data->flags as $fullName ) {
            $shortName = substr( $fullName, strrpos( $fullName, '\\' ) + 1 );
            $flagMap[ constant( $fullName ) ] = $shortName;
        }

        $info[ (int) $data->flagsCombinable ][ $data->kind ] = $flagMap;
    }

    return $info;
}


function is_combinable_flag( int $kind ) : bool {
    return isset( get_flag_info()[ 1 ][ $kind ] );
}


function format_flags( int $kind, int $flags ) : string {
    [ $exclusive, $combinable ] = get_flag_info();
    if ( isset( $exclusive[ $kind ] ) ) {
        $flagInfo = $exclusive[ $kind ];
        if ( isset( $flagInfo[ $flags ] ) ) {
            return "{$flagInfo[$flags]} ($flags)";
        }
    } elseif ( isset( $combinable[ $kind ] ) ) {
        $flagInfo = $combinable[ $kind ];
        $names = [];
        foreach ( $flagInfo as $flag => $name ) {
            if ( $flags & $flag ) {
                $names[] = $name;
            }
        }
        if ( ! empty( $names ) ) {
            return implode( " | ", $names ) . " ($flags)";
        }
    }
    return (string) $flags;
}


/** Dumps abstract syntax tree */
function ast_dump( $ast, int $options = 0 ) : string {
    if ( $ast instanceof ast\Node ) {
        $result = ast\get_kind_name( $ast->kind );

        if ( $options & AST_DUMP_LINENOS ) {
            $result .= " @ $ast->lineno";
            if ( isset( $ast->endLineno ) ) {
                $result .= "-$ast->endLineno";
            }
        }

        if ( ( ast\kind_uses_flags( $ast->kind ) && ! is_combinable_flag( $ast->kind ) ) || $ast->flags != 0 ) {
            $result .= "\n    flags: " . format_flags( $ast->kind, $ast->flags );
        }
        foreach ( $ast->children as $i => $child ) {
            if ( ( $options & AST_DUMP_EXCLUDE_DOC_COMMENT ) && $i === 'docComment' ) {
                continue;
            }
            $result .= "\n    $i: " . str_replace( "\n", "\n    ", ast_dump( $child, $options ) );
        }
        return $result;
    } elseif ( $ast === null ) {
        return 'null';
    } elseif ( is_string( $ast ) ) {
        return "\"$ast\"";
    } else {
        return (string) $ast;
    }
}

