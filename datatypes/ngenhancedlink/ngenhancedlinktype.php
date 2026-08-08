<?php

require_once( __DIR__ . '/../../classes/ngenhancedlinkvalue.php' );

class ngEnhancedLinkType extends eZDataType
{
    const DATA_TYPE_STRING = 'ngenhancedlink';

    const SETTINGS_FIELD = 'data_text5';

    public function __construct()
    {
        parent::__construct( self::DATA_TYPE_STRING,
                             ezpI18n::tr( 'kernel/classes/datatypes', 'Enhanced Link', 'Datatype name' ),
                             array( 'serialize_supported' => true ) );
    }

    public function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion !== false )
        {
            $contentObjectAttribute->setAttribute( 'data_text', $originalContentObjectAttribute->attribute( 'data_text' ) );
            $contentObjectAttribute->setAttribute( 'sort_key_string', $originalContentObjectAttribute->attribute( 'sort_key_string' ) );
            $contentObjectAttribute->setAttribute( 'data_int', $originalContentObjectAttribute->attribute( 'data_int' ) );
        }
    }

    public function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $variableName = $base . '_data_ngenhancedlink_json_' . $contentObjectAttribute->attribute( 'id' );

        if ( !$http->hasPostVariable( $variableName ) )
            return eZInputValidator::STATE_ACCEPTED;

        $json = trim( $http->postVariable( $variableName ) );
        if ( $json === '' && $contentObjectAttribute->validateIsRequired() )
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Input required.' ) );
            return eZInputValidator::STATE_INVALID;
        }

        if ( $json !== '' )
        {
            $data = json_decode( $json, true );
            if ( !is_array( $data ) || !isset( $data['type'] ) )
            {
                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Input is not valid JSON.' ) );
                return eZInputValidator::STATE_INVALID;
            }
        }

        return eZInputValidator::STATE_ACCEPTED;
    }

    public function fixupObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
    }

    public function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $variableName = $base . '_data_ngenhancedlink_json_' . $contentObjectAttribute->attribute( 'id' );

        if ( !$http->hasPostVariable( $variableName ) )
            return false;

        $json = trim( $http->postVariable( $variableName ) );

        if ( $json === '' )
        {
            $contentObjectAttribute->setAttribute( 'data_text', '' );
            $contentObjectAttribute->setAttribute( 'sort_key_string', '' );
            $contentObjectAttribute->setAttribute( 'data_int', 0 );
            return true;
        }

        $data = json_decode( $json, true );
        if ( !is_array( $data ) )
            return false;

        $contentObjectAttribute->setAttribute( 'data_text', $json );
        $this->updateSortKey( $contentObjectAttribute, $data );
        return true;
    }

    public function validateClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    public function fixupClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
    }

    public function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $settingsName = $base . '_data_ngenhancedlink_settings_' . $classAttribute->attribute( 'id' );

        if ( $http->hasPostVariable( $settingsName ) )
        {
            $classAttribute->setAttribute( self::SETTINGS_FIELD, $http->postVariable( $settingsName ) );
            return true;
        }

        return false;
    }

    public function initializeClassAttribute( $classAttribute )
    {
        $settings = $classAttribute->attribute( self::SETTINGS_FIELD );

        if ( $settings === null || $settings === '' )
        {
            $defaults = array(
                'selectionMethod' => 0,
                'selectionRoot' => '',
                'rootDefaultLocation' => false,
                'selectionContentTypes' => array(),
                'allowedLinkType' => 'all',
                'allowedTargetsInternal' => array( 'link', 'link_new_tab', 'embed', 'modal' ),
                'allowedTargetsExternal' => array( 'link', 'link_new_tab' ),
                'enableSuffix' => true,
                'enableLabelInternal' => true,
                'enableLabelExternal' => true,
            );

            $classAttribute->setAttribute( self::SETTINGS_FIELD, json_encode( $defaults ) );
            $classAttribute->store();
        }
    }

    public function storeObjectAttribute( $contentObjectAttribute )
    {
        $data = $this->decodeValue( $contentObjectAttribute );
        if ( is_array( $data ) )
        {
            $this->updateSortKey( $contentObjectAttribute, $data );
        }
    }

    public function objectAttributeContent( $contentObjectAttribute )
    {
        $data = $this->decodeValue( $contentObjectAttribute );
        if ( !is_array( $data ) )
            return new ngEnhancedLinkValue();

        $type = isset( $data['type'] ) ? $data['type'] : 'internal';
        $label = isset( $data['label'] ) ? $data['label'] : null;
        $target = isset( $data['target'] ) ? $data['target'] : ngEnhancedLinkValue::TARGET_LINK;
        $suffix = isset( $data['suffix'] ) ? $data['suffix'] : null;
        $relAttribute = isset( $data['rel_attribute'] ) ? $data['rel_attribute'] : null;

        if ( $type === 'external' )
        {
            $reference = $this->resolveExternalReference( $contentObjectAttribute, $data );
            return new ngEnhancedLinkValue( $reference, $label, $target, null, $relAttribute, $type );
        }

        $reference = isset( $data['id'] ) ? (int) $data['id'] : 0;
        return new ngEnhancedLinkValue( $reference, $label, $target, $suffix, $relAttribute, $type );
    }

    public function hasObjectAttributeContent( $contentObjectAttribute )
    {
        $data = $this->decodeValue( $contentObjectAttribute );
        return is_array( $data ) && isset( $data['type'] );
    }

    public function isIndexable()
    {
        return true;
    }

    public function isInformationCollector()
    {
        return false;
    }

    public function sortKeyType()
    {
        return 'string';
    }

    public function sortKey( $contentObjectAttribute )
    {
        $sortKey = $contentObjectAttribute->attribute( 'sort_key_string' );
        if ( $sortKey !== null && $sortKey !== '' )
            return $sortKey;

        $value = $this->objectAttributeContent( $contentObjectAttribute );
        return (string) $value;
    }

    public function title( $contentObjectAttribute, $name = null )
    {
        $value = $this->objectAttributeContent( $contentObjectAttribute );
        return $value->label !== null && $value->label !== '' ? (string) $value->label : (string) $value;
    }

    public function metaData( $contentObjectAttribute )
    {
        $value = $this->objectAttributeContent( $contentObjectAttribute );
        $parts = array();

        if ( $value->label !== null && $value->label !== '' )
            $parts[] = (string) $value->label;

        $ref = (string) $value;
        if ( $ref !== '' )
            $parts[] = $ref;

        return implode( ' ', $parts );
    }

    public function toString( $contentObjectAttribute )
    {
        return (string) $contentObjectAttribute->attribute( 'data_text' );
    }

    public function fromString( $contentObjectAttribute, $string )
    {
        $contentObjectAttribute->setAttribute( 'data_text', $string );

        $data = json_decode( $string, true );
        if ( is_array( $data ) )
            $this->updateSortKey( $contentObjectAttribute, $data );
        else
            $contentObjectAttribute->setAttribute( 'sort_key_string', '' );
    }

    protected function decodeValue( $contentObjectAttribute )
    {
        $text = trim( (string) $contentObjectAttribute->attribute( 'data_text' ) );
        if ( $text === '' )
            return null;

        $data = json_decode( $text, true );
        return is_array( $data ) ? $data : null;
    }

    protected function updateSortKey( $contentObjectAttribute, $data )
    {
        $type = isset( $data['type'] ) ? $data['type'] : 'internal';

        if ( $type === 'external' )
        {
            $url = $this->resolveExternalReference( $contentObjectAttribute, $data );
            $contentObjectAttribute->setAttribute( 'sort_key_string', (string) $url );
            $contentObjectAttribute->setAttribute( 'data_int', 0 );
        }
        else
        {
            $id = isset( $data['id'] ) ? (int) $data['id'] : 0;
            $contentObjectAttribute->setAttribute( 'sort_key_string', (string) $id );
            $contentObjectAttribute->setAttribute( 'data_int', $id );
        }
    }

    protected function resolveExternalReference( $contentObjectAttribute, $data )
    {
        if ( isset( $data['url'] ) && $data['url'] !== '' && $data['url'] !== null )
            return (string) $data['url'];

        $sortKey = $contentObjectAttribute->attribute( 'sort_key_string' );
        if ( $sortKey !== null && $sortKey !== '' )
            return (string) $sortKey;

        $urlId = isset( $data['id'] ) ? (int) $data['id'] : 0;
        if ( $urlId > 0 && class_exists( 'eZURL' ) )
        {
            $url = eZURL::fetch( $urlId );
            if ( $url instanceof eZURL )
                return $url->attribute( 'url' );
        }

        return '';
    }
}

eZDataType::register( ngEnhancedLinkType::DATA_TYPE_STRING, 'ngEnhancedLinkType' );
