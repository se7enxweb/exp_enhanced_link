<?php

class ngEnhancedLinkValue
{
    public $reference;
    public $label;
    public $target;
    public $suffix;
    public $relAttribute;
    public $type;

    const LINK_TYPE_INTERNAL = 'internal';
    const LINK_TYPE_EXTERNAL = 'external';

    const TARGET_LINK = 'link';
    const TARGET_EMBED = 'embed';
    const TARGET_MODAL = 'modal';
    const TARGET_LINK_IN_NEW_TAB = 'link_new_tab';

    public function __construct( $reference = null, $label = null, $target = self::TARGET_LINK, $suffix = null, $relAttribute = null, $type = null )
    {
        $this->reference = $reference;
        $this->label = $label;
        $this->target = $target;
        $this->suffix = $suffix;
        $this->relAttribute = $relAttribute;
        $this->type = $type;
    }

    public function __toString()
    {
        if ( is_string( $this->reference ) )
            return $this->reference;

        if ( is_int( $this->reference ) )
            return (string) $this->reference;

        return '';
    }

    public function isTypeExternal()
    {
        return $this->type === self::LINK_TYPE_EXTERNAL || is_string( $this->reference );
    }

    public function isTypeInternal()
    {
        return $this->type === self::LINK_TYPE_INTERNAL || is_int( $this->reference );
    }

    public function isTargetModal()
    {
        return $this->target === self::TARGET_MODAL;
    }

    public function isTargetEmbed()
    {
        return $this->target === self::TARGET_EMBED;
    }

    public function isTargetLink()
    {
        return $this->target === self::TARGET_LINK;
    }

    public function isTargetLinkInNewTab()
    {
        return $this->target === self::TARGET_LINK_IN_NEW_TAB;
    }
}
