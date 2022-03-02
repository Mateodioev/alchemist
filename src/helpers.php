<?php 

namespace App;

/**
 * Replace a bool value in a string
 */
function BoolString(bool $bool, array $replace = [false => 'False', true => '❌ True']) {
  return $replace[$bool];
}

/**
 * Bold
 */
function b(string $str) {
  return '<b>'.$str.'</b>';
}
/**
 * Underline
 */
function u(string $str) {
  return '<u>'.$str.'</u>';
}
/**
 * Italic
 */
function i(string $str) {
  return '<i>'.$str.'</i>';
}
/**
 * Monospace
 */
function code(string $str) {
  return '<code>'.$str.'</code>';
}
/**
 * Line break
 */
function n() {
  return PHP_EOL;
}