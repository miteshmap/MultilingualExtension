<?php
namespace kolevCustomized\MultilingualExtension\ServiceContainer;

class MultilingualFactory {

  private $currentLag;

  public function setCurrentLang($lang) {
    $this->currentLag = $lang;
  }

  public function getCurrentLag() {
    return $this->currentLag;
  }
}