<?php
namespace iiif\model\resources;

use iiif\model\properties\NavDateTrait;
use iiif\model\properties\ViewingDirectionTrait;

class Manifest extends AbstractIiifResource
{
    use NavDateTrait;
    use ViewingDirectionTrait;
    
    const CONTEXT="http://iiif.io/api/presentation/2/context.json";
    const TYPE="sc:Manifest";
    
    protected $sequences = array();
    
    protected $viewingDirection;
    protected $navDate;
    
    protected $structures;
    
    protected static function fromArray($jsonAsArray)
    {
        $manifest = new Manifest();
        $manifest->loadPropertiesFromArray($jsonAsArray);
        if (array_key_exists("sequences", $jsonAsArray))
        {
            $sequencesAsArray = $jsonAsArray["sequences"];
            foreach ($sequencesAsArray as $sequenceAsArray)
            {
                $sequence = Sequence::fromArray($sequenceAsArray);
                $this->sequences[] = $sequence;
            }
        }
    }
    /**
     * @return multitype:
     */
    public function getSequences()
    {
        return $this->sequences;
    }

    /**
     * @param multitype: $sequences
     */
    public function setSequences($sequences)
    {
        $this->sequences = $sequences;
    }

    
    
}

