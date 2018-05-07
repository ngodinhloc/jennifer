<?php

namespace thedaysoflife\sys;

use jennifer\sys\Config;

class Configs extends Config {
  //URL
  const LIST_URL = self::SITE_URL . '/day/';
  // META
  const SITE_TITLE       = 'The Days Of Life';
  const SITE_AUTHOR      = 'The Days Of Life';
  const SITE_DESCRIPTION = 'Share memories, inspire people';
  const SITE_KEYWORDS    = 'the days of life, share to inspire, share memories, best day of life, best memories, inspiration';
  // NUMBER
  const PREVIEW_LENGTH     = 500;
  const SUMMARY_LENGTH     = 1000;
  const DESC_LENGTH        = 200;
  const NUM_TOP_RIGHT      = 10;
  const NUM_PER_PAGE_ADMIN = 40;
  const NUM_PER_PAGE       = 12;
  const NUM_CALENDAR       = 5;
  const NUM_PICTURE        = 48;
  const NUM_PHOTO_UPLOAD   = 10;
  //PHOTOS
  const PHOTO_DIR            = "/uploads/photos/";
  const PHOTO_EXT            = ".jpg";
  const PHOTO_FULL_NAME      = "_full";
  const PHOTO_TITLE_NAME     = "_title";
  const PHOTO_THUMB_NAME     = "_thumb";
  const PHOTO_FULL_COMPRESS  = 100;
  const PHOTO_TITLE_COMPRESS = 100;
  const PHOTO_THUMB_COMPRESS = 100;
  const PHOTO_FULL_WIDTH     = 720;
  const PHOTO_FULL_HEIGHT    = 720;
  const PHOTO_TITLE_WIDTH    = 320;
  const PHOTO_TITLE_HEIGHT   = 240;
  const PHOTO_THUMB_WIDTH    = 75;
  const PHOTO_THUMB_HEIGHT   = 75;
}