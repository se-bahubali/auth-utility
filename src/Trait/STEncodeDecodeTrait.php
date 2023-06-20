<?php

namespace StallionExpress\AuthUtility\Trait;

trait STEncodeDecodeTrait
{
    public function encodeHashValue($value)
    {
        return '_st_'.base64_encode($value);
    }

    public function decodeHashValue($value)
    {
        return (int) base64_decode(str_replace('_st_', '', $value));
    }
}
