<?php
// Local app icons, generated with the GD extension (no remote assets).
foreach ([192, 512, 1024] as $size) {
    $image = imagecreatetruecolor($size, $size);
    $navy = imagecolorallocate($image, 8, 30, 49);
    $gold = imagecolorallocate($image, 219, 181, 101);
    imagefill($image, 0, 0, $navy);
    imagesetthickness($image, max(2, (int) ($size / 100)));
    imageellipse($image, (int) ($size / 2), (int) ($size / 2), (int) ($size * .73), (int) ($size * .73), $gold);
    imagesetthickness($image, max(3, (int) ($size / 65)));
    imageline($image, (int) ($size / 2), (int) ($size * .24), (int) ($size / 2), (int) ($size * .77), $gold);
    imageline($image, (int) ($size * .30), (int) ($size * .43), (int) ($size * .70), (int) ($size * .43), $gold);
    imagepng($image, __DIR__.'/../public/images/icon-'.$size.'.png');
    imagedestroy($image);
}
copy(__DIR__.'/../public/images/icon-1024.png', __DIR__.'/../public/icon.png');
