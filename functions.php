<?php

/**
* Converts empty string to null
* @param type $str
* @return null 
*/
function empty_to_null( $str){
    if((string) $str!=='') return (string) $str;
    return null;
}

/**
 * Checks if haystack starts with needle
 * @param string $haystack
 * @param string $needle
 * @param boolean $case
 * @return boolean 
 */
function startsWith($haystack,$needle,$case=true){
   if($case) return strpos($haystack, $needle, 0) === 0;
   return stripos($haystack, $needle, 0) === 0;
}

/**
 * Checks if haystack ends with needle
 * @param string $haystack
 * @param string $needle
 * @param boolean $case
 * @return boolean 
 */
function endsWith($haystack,$needle,$case=true){
  $expectedPosition = strlen($haystack) - strlen($needle);
  if($case) return strrpos($haystack, $needle, 0) === $expectedPosition;
  return strripos($haystack, $needle, 0) === $expectedPosition;
}

/**
* Checks if given string is numeric
* @param string $dec
* @return null|int 
*/
function CheckNumeric($dec){
    $d = str_replace(",", ".", $dec ?? "");
    if(is_numeric($d)) return $d;
    else return 0;
}

?>