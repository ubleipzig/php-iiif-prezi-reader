<?php
namespace iiif\context;

class JsonLdContextHelper
{
    public static function loadJsonLdContext($contextUrl)
    {
        $contextJson = file_get_contents($contextUrl);
        $contextJsonArray = json_decode($contextJson, true);
        foreach ($contextJsonArray["@context"] as $term => $definition) {
            
            if (is_array($definition)) {
                foreach ($definition as $keyword => $value) {
                    echo $term." ".$keyword." ";
                    if (is_array($value)) {
                        echo "[".implode(",", $value)."]\n";
                    } else {
                        echo $value."\n";
                    }
                }
            } else {
                echo $term.": ".$definition."\n";
            }
            
        }
    }
    
    protected static function analyzeTerm($term, $definition, JsonLdContext &$callingContext) {
        if (Keywords::isKeyword($term)) {
            switch ($term) {
                case Keywords::CONTEXT:
                    $context = new JsonLdContext();
                    $contextJsonAsArray = null; 
                    if (is_array($definition)) {
                        // Context is a list of contexts 
                        if (self::isSequentialArray($definition)) {
                            foreach ($definition as $contextIndex => $contextDefinition) {
                                $context->contexts[] = self::analyzeTerm(Keywords::CONTEXT, $contextDefinition, $context);
                            }
                        } else {
                            // Context is an actual context definition - 
                            $contextStringContent = file_get_contents($definition);
                            $contextJsonAsArray = json_decode($contextStringContent, true);
                        }
                    } else {
                        $contextJsonAsArray = $definition;
                    }
                    foreach ($contextJsonAsArray as $contextTerm => $contextDefinition) {
                        //$contextItem
                    }
                    
                    break;
                case Keywords::VERSION:
                    
                    break;
                case Keywords::ID:
                    //$callingContext
                    break;
                default:
                    break;
            }
        } else {
            if (self::isSequentialArray($definition)) {
                
            } elseif (is_array($definition)) {
                
            } elseif (is_string($definition)) {
                if (IRI::isUri($uri)) {
                    $callingContext->addPrefix(new JsonLdPrefix($term, $definition));
                }
                
                
            } else {
                // Scheise was ist pasirt
            }
        }
        
    }
    
    public static function isSequentialArray($array) {
        if (!is_array($array)) {
            return false;
        }
        $lastIndex = sizeof($array) - 1;
        foreach ($array as $key => $value) {
            if (!is_int($key) || $key < 0 || $key > $lastIndex) {
                return false;
            }
        }
        return true;
    }
}

JsonLdContextHelper::loadJsonLdContext("http://iiif.io/api/presentation/3/combined-context.json");