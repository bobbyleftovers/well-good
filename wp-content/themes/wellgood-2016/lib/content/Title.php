<?php
/*
 * https://docs.google.com/document/d/1j9IFhTMqSI_2yXlWSoiZhfelZb9a-35-mYtx6y9WeVA/edit
 *
 * @package Well_Good
 * @author BarrelNY
 * @since 12.4.0
 */

namespace WG\Content;

class Title {

  static $maintain_case = array();

  static $date_threshold = '2020-06-14 23:59:59';

  // https://www.bkacontent.com/how-to-correctly-use-apa-style-title-case/
  static $always_lowercase = array(
    'a',
    'for',
    'so',
    'an',
    'in',
    'the',
    'and',
    'nor',
    'to',
    'at',
    'of',
    'up',
    'but',
    'on',
    'yet',
    'by',
    'or'
  );

  static $maintain_symbols = array(
    '#'
  );


  static $unicode_separators = array(
    '0020', // https://www.compart.com/en/unicode/U+0020
    '0022', // https://www.compart.com/en/unicode/U+0022
    '0028', // https://www.compart.com/en/unicode/U+0028
    '0029', // https://www.compart.com/en/unicode/U+0029
    '002A', // https://www.compart.com/en/unicode/U+002A
    '002B', // https://www.compart.com/en/unicode/U+002B
    '002C', // https://www.compart.com/en/unicode/U+002C
    '002D', // https://www.compart.com/en/unicode/U+002D
    '002E', // https://www.compart.com/en/unicode/U+002E
    '002F', // https://www.compart.com/en/unicode/U+002F
    '0028', // https://www.compart.com/en/unicode/U+0028
    '0029', // https://www.compart.com/en/unicode/U+0029
    '003A', // https://www.compart.com/en/unicode/U+003A
    '003B', // https://www.compart.com/en/unicode/U+003B
    '003F', // https://www.compart.com/en/unicode/U+003F
    '2010', // https://www.compart.com/en/unicode/U+2010
    '2011', // https://www.compart.com/en/unicode/U+2011
    '2012', // https://www.compart.com/en/unicode/U+2012
    '2013', // https://www.compart.com/en/unicode/U+2013
    '2014', // https://www.compart.com/en/unicode/U+2014
    '2015', // https://www.compart.com/en/unicode/U+2015
    '2018', // https://www.compart.com/en/unicode/U+2018
    '201C', // https://www.compart.com/en/unicode/U+201C
    '201D', // https://www.compart.com/en/unicode/U+201D
    '2026', // https://www.compart.com/en/unicode/U+2026
    '2116'  // https://www.compart.com/en/unicode/U+2116
  );

  /**
   * Hooks
   */
  function __construct() {
    self::set_default_params();

    add_filter( 'the_title', array( $this, 'filter_the_title'), 10, 2 );
  }

  static function filter_the_title( $title, $id = NULL ) {
    $date = NULL;
    $override = FALSE;

    if ( $id ) :
      $post = get_post( $id );
      $date = $post->post_date;
      $override = get_field( 'override_automatic_title_casing', $id );
    endif;

    return self::verify_title_case( $title, $date, $override );
  }

  /**
   * Set Default Params
   * Check site options for function settings.
   *
   * Currently just `title_case_maintain` which checks
   * for specialized words to maintain the casing for
   */
  function set_default_params() {
    while ( have_rows('title_case_maintain', 'options' ) ) :
      the_row();

      $word = get_sub_field('title_case_maintain_word');
      if ( $word ) :
        self::$maintain_case[] = $word;
      endif;
    endwhile;
  }

  /**
   * Verify Title Case
   *
   * @param [string] $title
   * @return $result - Title with correct title casing
   */
  static function verify_title_case( $title, $date = NULL, $override = FALSE ) {
    if ( is_admin() || $override ) :
      return $title;
    endif;

    if ( $date != NULL && new \DateTime( $date ) > new \DateTime( self::$date_threshold ) ) :
      return $title;
    endif;

    $words = preg_split(
      '~([' . implode( '|', array_map( function( $symbol ) {
        return "\x{{$symbol}}";
      }, self::$unicode_separators ) ) . '])~u',
      html_entity_decode( $title ), -1, PREG_SPLIT_DELIM_CAPTURE
    );

    $first_word = 0;
    $last_word = count( $words ) - 1;
    $title_is_all_caps = ctype_upper( implode( '', preg_split( '/[^a-z_]/i', html_entity_decode( $title ), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY ) ) );

    foreach( $words as $i => $word ) :
      $edited_word = html_entity_decode( $word );

      $before_word = '';
      $after_word = '';
      $maintain_symbols = false;
      if ( strlen( $word ) > 1 ) :
        $before_word = mb_substr( $word, 0, 1, 'utf-8' );
        $after_word = mb_substr( $word, strlen($word) - 1, 1, 'utf-8' );

        if ( in_array( $before_word, self::$maintain_symbols ) ) :
          $maintain_symbols = true;
          $before_word = '';
          $after_word = '';
        else :
          if ( preg_match( '/[^a-z_\-0-9]/i', $before_word ) ) :
            $edited_word = mb_substr( $edited_word, 1, strlen($word) - 1, 'utf-8' );
          else :
            $before_word = '';
          endif;

          if ( preg_match( '/[^a-z_\-0-9]/i', $after_word ) ) :
            $edited_word = mb_substr( $edited_word, 0, -1, 'utf-8' );
          else :
            $after_word = '';
          endif;
        endif;

      endif;

      $first_or_last = ( $i === $first_word || $i === $last_word );
      $maintain_case = ( $maintain_symbols || in_array( $edited_word, self::$maintain_case ) );
      $always_lowercase = in_array( strtolower( $edited_word ), self::$always_lowercase );
      $word_is_all_caps = ctype_upper( $edited_word );

      $new_sentence = false;
      if ( ( $edited_word != '' && $edited_word != ' ' ) && $i > 0 ) :
        $prev = $i - 1;
        while ( $prev > 0 && ( $words[$prev] == '' || $words[$prev] == ' ' ) ) :
          $prev--;
        endwhile;
        if ( in_array( $words[$prev], array( '.', '?', '!' ) ) ) :
          $new_sentence = true;
        endif;
      endif;

      $force_title_case = ( ! $always_lowercase || $first_or_last ) && ( ! $word_is_all_caps || $title_is_all_caps );
      $force_lowercase = ( $always_lowercase && ! $first_or_last );

      if ( ! $maintain_case && ( $force_title_case || $new_sentence ) ) :
        $edited_word = ucwords( strtolower( $edited_word ) );

      elseif ( ! $maintain_case && $force_lowercase ) :
        $edited_word = strtolower( $edited_word );

      endif;

      $words[$i] = $before_word . $edited_word . $after_word;

    endforeach;

    $result = implode( '', $words );
    return $result;
  }
}
