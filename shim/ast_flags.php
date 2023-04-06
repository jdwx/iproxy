<?php


namespace ast\flags;


const TYPE_ARRAY = 1;
const TYPE_CALLABLE = 2;
const TYPE_VOID = 4;
const TYPE_BOOL = 8;
const TYPE_LONG = 16;
const TYPE_DOUBLE = 32;
const TYPE_STRING = 64;
const TYPE_ITERABLE = 128;
const TYPE_OBJECT = 256;
const TYPE_NULL = 512;
const TYPE_FALSE = 1024;
const TYPE_TRUE = 2048;
const TYPE_MIXED = 8192;
const TYPE_NEVER = 16384;


const MODIFIER_PROTECTED = 2;
const MODIFIER_PRIVATE = 4;
const MODIFIER_STATIC = 8;

const CLASS_INTERFACE = 1;


const USE_NORMAL = 1;

const NAME_NOT_FQ = 1;


const FUNC_RETURNS_REF = 1;
