<?php
/**
 * Compile Attributes
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 15.0.1
 */


function compile_attrs( $vars ) {
  array_walk( $vars, function(&$a, $b) { 
    switch( $b ) :
      case 'class' :
        if ( is_array( $a ) ) :
          $a = implode( ' ', $a );
        endif;
        break;

      default :
        $a = $a;
        
    endswitch;

    $a = "$b='$a'"; 
  } );

  return implode( ' ', $vars );
}