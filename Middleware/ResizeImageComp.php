<?php

namespace App\Http\Middleware;

class ResizeImageComp {

    var $image;
    public $imageType = '';

    function load($filename) {
       //print_r($filename); exit;
        $imageInfo = getimagesize($filename);
        $this->imageType = $imageInfo[2];
        if ($this->imageType == '2') {
            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->imageType == '1') {
            $this->image = imagecreatefromgif($filename);
        } elseif ($this->imageType == '3') {
            $this->image = imagecreatefrompng($filename);
        } elseif ($this->imageType == '6') {
            $this->image = $this->ImageCreateFromBMP($filename);
        }
    }
    
    function save($filename, $imageType = '2', $compression = 75, $permissions = null) {
        if ($imageType == '2') {
            imagejpeg($this->image, $filename, $compression);
        } elseif ($imageType == '1') {
            imagegif($this->image, $filename);
        } elseif ($imageType == '3') {
            imagepng($this->image, $filename);
        }
        if ($permissions != null) {
            chmod($filename, $permissions);
        }
    }

    function output($imageType = '2') {
        if ($imageType == '2') {
            imagejpeg($this->image);
        } elseif ($imageType == '1') {
            imagegif($this->image);
        } elseif ($imageType == '3') {
            imagepng($this->image);
        }
    }

    function getWidth() {
        return imagesx($this->image);
    }

    function getHeight() {
        return imagesy($this->image);
    }

    function resizeToHeight($height) {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    function resizeToWidth($width) {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
    }

    function scale($scale) {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    function resize($width, $height) {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }

    function ImageCreateFromBMP($filename) {
        if (!$f1RB = fopen($filename, "rb"))
            return FALSE;
        $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1RB, 14));
        if ($FILE['file_type'] != 19778)
            return FALSE;
        $BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel' .
                '/Vcompression/Vsize_bitmap/Vhoriz_resolution' .
                '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1RB, 40));
        $BMP['colors'] = pow(2, $BMP['bits_per_pixel']);
        if ($BMP['size_bitmap'] == 0)
            $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
        $BMP['bytes_per_pixel'] = $BMP['bits_per_pixel'] / 8;
        $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
        $BMP['decal'] = ($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
        $BMP['decal'] -= floor($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
        $BMP['decal'] = 4 - (4 * $BMP['decal']);
        if ($BMP['decal'] == 4)
            $BMP['decal'] = 0;
        $PALETTE = array();
        if ($BMP['colors'] < 16777216) {
            $PALETTE = unpack('V' . $BMP['colors'], fread($f1RB, $BMP['colors'] * 4));
        }

        $IMG = fread($f1RB, $BMP['size_bitmap']);
        $VIDE = chr(0);

        $res = imagecreatetruecolor($BMP['width'], $BMP['height']);
        $pix = 0;
        $yHgt = $BMP['height'] - 1;
        while ($yHgt >= 0) {
            $xVar = 0;
            while ($xVar < $BMP['width']) {
                if ($BMP['bits_per_pixel'] == 24)
                    $COLOR = unpack("V", substr($IMG, $pix, 3) . $VIDE);
                elseif ($BMP['bits_per_pixel'] == 16) {
                    $COLOR = unpack("n", substr($IMG, $pix, 2));
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } elseif ($BMP['bits_per_pixel'] == 8) {
                    $COLOR = unpack("n", $VIDE . substr($IMG, $pix, 1));
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } elseif ($BMP['bits_per_pixel'] == 4) {
                    $COLOR = unpack("n", $VIDE . substr($IMG, floor($pix), 1));
                    if (($pix * 2) % 2 == 0)
                        $COLOR[1] = ($COLOR[1] >> 4);
                    else
                        $COLOR[1] = ($COLOR[1] & 0x0F);
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } elseif ($BMP['bits_per_pixel'] == 1) {
                    $COLOR = unpack("n", $VIDE . substr($IMG, floor($pix), 1));
                    if (($pix * 8) % 8 == 0)
                        $COLOR[1] = $COLOR[1] >> 7;
                    elseif (($pix * 8) % 8 == 1)
                        $COLOR[1] = ($COLOR[1] & 0x40) >> 6;
                    elseif (($pix * 8) % 8 == 2)
                        $COLOR[1] = ($COLOR[1] & 0x20) >> 5;
                    elseif (($pix * 8) % 8 == 3)
                        $COLOR[1] = ($COLOR[1] & 0x10) >> 4;
                    elseif (($pix * 8) % 8 == 4)
                        $COLOR[1] = ($COLOR[1] & 0x8) >> 3;
                    elseif (($pix * 8) % 8 == 5)
                        $COLOR[1] = ($COLOR[1] & 0x4) >> 2;
                    elseif (($pix * 8) % 8 == 6)
                        $COLOR[1] = ($COLOR[1] & 0x2) >> 1;
                    elseif (($pix * 8) % 8 == 7)
                        $COLOR[1] = ($COLOR[1] & 0x1);
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } else
                    return FALSE;
                imagesetpixel($res, $xVar, $yHgt, $COLOR[1]);
                $xVar++;
                $pix += $BMP['bytes_per_pixel'];
            }
            $yHgt--;
            $pix+=$BMP['decal'];
        }
        fclose($f1RB);
        return $res;
    }
}
