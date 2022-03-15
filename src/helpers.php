<?php 

namespace Mateodioev\Alchemist;

/**
 * Replace a bool value in a string
 */
function BoolString(bool $bool, array $replace = [false => 'False', true => '❌ True']) {
  return $replace[$bool];
}

/**
 * Quit html from strings
 */
function xQuit(string $str='') {
  if (empty($str)) return $str;
  return \Mateodioev\Alchemist\Config\Utils::QuitHtml($str);
}


/**
 * Bold
 */
function b(string $str) {
  return '<b>'.xQuit($str).'</b>';
}

/**
 * Underline
 */
function u(string $str) {
  return '<u>'.xQuit($str).'</u>';
}

/**
 * Italic
 */
function i(string $str) {
  return '<i>'.xQuit($str).'</i>';
}

/**
 * Monospace
 */
function code(string $str) {
  return '<code>'.xQuit($str).'</code>';
}

/**
 * Line break
 */
function n() {
  return PHP_EOL;
}