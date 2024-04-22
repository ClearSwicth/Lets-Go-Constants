<?php
 require_once (__DIR__.'/vendor/autoload.php');


$docComment = <<<DOCCOMMENT
/**
 * This is an example of a summary.
 *
 * This is a Description. A Summary and Description are separated by either
 * two subsequent newlines (thus a whiteline in between as can be seen in this
 * example), or when the Summary ends with a dot (`.`) and some form of
 * whitespace.
 */
DOCCOMMENT;

$factory  = \phpDocumentor\Reflection\DocBlockFactory::createInstance();
$docblock = $factory->create($docComment);

// You can check if a DocBlock has one or more see tags
$hasSeeTag = $docblock->hasTag('see');

// Or we can get a complete list of all tags
$tags = $docblock->getTags();

// But we can also grab all tags of a specific type, such as `see`
$seeTags = $docblock->getTagsByName('see');

$a=$docblock->getSummary();
print_r($docblock->getDescription());exit;

