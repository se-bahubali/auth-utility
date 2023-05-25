<?php

namespace StallionExpress\AuthUtility\Trait;

use StallionExpress\AuthUtility\Trait\STEncodeDecodeTrait;

trait PrimaryIdEncodeTrait
{
    use STEncodeDecodeTrait;

    public function getHashAttribute()
    {
        return $this->encodeHashValue($this->id);
    }
}
