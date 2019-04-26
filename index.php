<?php

Kirby::plugin('julien-gargot/pdf-preview', [

  'fileMethods' => [
    'preview' => function ($page = 0) {

      if($this->mime() !== 'application/pdf') return $this;

      $extension   = 'jpg';
      $src         = $this->root();
      $dst         = $this->root() . '.' . $extension;
      $previewName = basename($dst);

      if (!f::exists($dst) || (f::modified($dst) < $this->modified())) {

        $im = new Imagick();
        $im->setResolution(96,96);
        $im->readImage($src.'['.$page.']');
        $im->setImageBackgroundColor('white');
        $im->setImageAlphaChannel(imagick::ALPHACHANNEL_REMOVE);
        $im->setImageFormat($extension);
        $im->setImageCompression(Imagick::COMPRESSION_JPEG);
        $im->writeImage($dst);

      }

      return new File([
        'filename' => $previewName,
        'parent'   => $this->parent(),
      ]);

    }
  ]

]);
