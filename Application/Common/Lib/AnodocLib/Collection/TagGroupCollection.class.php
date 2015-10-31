<?php

namespace Lib\AnodocLib\Collection;


class TagGroupCollection extends Collection {

  function __construct($array = array()) {
    foreach ($array as $key => $value) {
      $this->offsetSet($key, $value);
    }
  }

  function offsetSet($key, $value) {
    if ($value instanceof TagGroup) {
      parent::offsetSet($key, $value);
    } else {
      throw new NotATagGroupException("Offset '$key' is not a tag group");
    }
  }

}