<?php

use Leafcutter\Images\Gallery;
use Leafcutter\Leafcutter;

$leafcutter = Leafcutter::get();
$images = $leafcutter->images();
$gallery = new Gallery();

$content = trim($content);
if (empty($content)) {
    $content = '*';
}
$content = preg_split('/[\r\n]+/', $content);

foreach ($content as $s) {
    foreach ($images->search($s) as $im) {
        if (!$gallery->contains($im)) {
            $gallery->add($im);
        }
    }
}

if (@$thumb) {
    $gallery->setThumbnail($thumb);
}

if (@$full) {
    $gallery->setFull($full);
}

// by default sort by date modified, then name
$gallery->sortBy('name');
$gallery->sortBy('date.modified', true);

echo $gallery;
