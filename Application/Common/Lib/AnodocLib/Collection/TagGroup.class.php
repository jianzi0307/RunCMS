<?php

namespace Lib\AnodocLib\Collection;

use Lib\AnodocLib\Tags\Tag;

class TagGroup extends Collection {

  private $tag_name;

  function __construct($tag_name, $array = array()) {
    $this->tag_name = $tag_name;
    foreach ($array as $key => $value) {
      $this->offsetSet($key, $value);
    }
  }

  function getTagName() {
    return $this->tag_name;
  }

  function offsetSet($key, $value) {
    if ($value instanceof Tag) {
      parent::offsetSet($key, $value);
    } else {
      throw new NotATagException("Offset '$key' is not a tag");
    }
  }

}