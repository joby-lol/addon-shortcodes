<?php
$attr = [
    'src' => $content,
];
if (@$alt) {
    $attr['alt'] = $this->escape($alt);
}
if (@$height) {
    $attr['height'] = $height;
}
if (@$width) {
    $attr['width'] = $width;
}
if (@$class) {
    $attr['class'] = $this->escape($class);
}
$img = ['<img'];
foreach ($attr as $k => $v) {
    $img[] = "$k=\"$v\"";
}
$img[] = '/>';
$img = implode(' ', $img);
if (@$link) {
    $img = "<a href=\"$link\">$img</a>";
}
echo $img;
