<?php

namespace App\Utilities;

use Skilla\MaximalCliques\lib\DataTransformerInterface;

class DistanceTransformer implements DataTransformerInterface
{
    /**
     * The maximal clique will include all of the vertices in R
     *
     * @param array $rVector
     * @return array
     */
    function obtainRVector( array $rVector )
    {
        return array_values( $rVector );
    }

    /**
     * The maximal clique will include some of the vertices in P
     *
     * @param array $pVector
     * @return array
     */
    function obtainPVector( array $pVector )
    {
        return array_values( $pVector );
    }

    /**
     * The maximal clique will include none of the vertices in X
     *
     * @param array $xVector
     * @return array
     */
    function obtainXVector( array $xVector )
    {
        return array_values( $xVector );
    }

    /**
     * @param array $nVector
     * @return array
     */
    function obtainNVector( array $nVector )
    {
        $cleanNVector = [];
        foreach ( $nVector as $x => $values )
        {
            foreach ( $values as $y => $value )
            {

                // It looks like the index "$y#$x" is used to tally edges from one node to another
                // This is specific to the Skilla\MaximalCliques library
                if ( ! is_null( $value ) )
                {
                    if ( $x > $y )
                    {
                        $cleanNVector[ "$y#$x" ] = [ $y, $x ];
                    }
                    else
                    {
                        $cleanNVector[ "$x#$y" ] = [ $x, $y ];
                    }
                }
            }
        }

        return $cleanNVector;
    }
}
