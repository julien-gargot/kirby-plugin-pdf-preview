<?php

use Kirby\Cms\Filename;

Kirby::plugin('julien-gargot/pdf-preview', [

  'fileMethods' => [
    'preview' => function ($page = 0) {

      if($this->mime() !== 'application/pdf') return $this;

      $extension   = 'jpg';
      $mediaRoot = dirname($this->mediaRoot());
      $src       = $this->root() . '.' . $extension;
      $dst       = $mediaRoot . '/{{ name }}.{{ extension }}';
      $thumbRoot = (new Filename($src, $dst))->toString();
      $thumbName = basename($thumbRoot);

      if (!is_dir($mediaRoot)) mkdir($mediaRoot, 0777, true);

      if (!f::exists($thumbRoot) || (f::modified($thumbRoot) < $this->modified())) {

        $im = new Imagick();
        $im->setResolution(96,96);
        $im->readImage($src.'['.$page.']');
        $im->setImageBackgroundColor('white');
        $im->setImageAlphaChannel(imagick::ALPHACHANNEL_REMOVE);
        $im->setImageFormat($extension);
        $im->setImageCompression(Imagick::COMPRESSION_JPEG);
        $im->writeImage($thumbRoot);

      }

      return new File([
        'filename' => $thumbName,
        'url'      => dirname($this->mediaUrl()) . '/' . $thumbName,
        'parent'   => $this->parent(),
      ]);

    }
  ]

]);
