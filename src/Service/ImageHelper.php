<?php

/*
 * This file is part of YeetBin.
 * Copyright (C) 2023 Arthur Bols
 *
 * YeetBin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * YeetBin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with YeetBin.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace App\Service;

use App\Config\Config;
use App\Util\ImageFormat;
use Imagick;
use ImagickException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageHelper
{
    public function isImage(UploadedFile $file) :bool
    {
        return str_starts_with($file->getMimeType(), 'image/');
    }

    /**
     * @throws ImagickException
     */
    public function processImage(UploadedFile $file, string $uid, bool $strip, bool $resize, ImageFormat $format) :void
    {
        $imagick = new Imagick($file->getRealPath());

        $imagick->setImageFormat($format->value);
        $imagick->setImageDepth(8);

        if ($imagick->getImageColorspace() !== Imagick::COLORSPACE_SRGB) {
            $imagick->transformImageColorspace(Imagick::COLORSPACE_SRGB);
        }

        if ($format === ImageFormat::PNG) {
            // We need to use setCompressionQuality for png:
            // https://github.com/ImageMagick/ImageMagick/issues/29#issuecomment-143263506
            $imagick->setCompressionQuality(95);
        } else {
            $imagick->setImageCompressionQuality(75);
        }

        if ($strip) {
            $imagick->stripImage();
        }
        if ($resize) {
            $this->resizeImage($imagick);
        }

        $imagick->writeImage(Config::getUploadDirectory().'/'.$uid);
    }

    /**
     * @throws ImagickException
     */
    public function resizeImage(Imagick $imagick) :void
    {
        if ($imagick->getImageHeight() > $imagick->getImageWidth() && $imagick->getImageHeight() > 1920) {
            $imagick->resizeImage(0, 1920, Imagick::FILTER_LANCZOS, 1);
        } elseif ($imagick->getImageWidth() > 1920) {
            $imagick->resizeImage(1920, 0, Imagick::FILTER_LANCZOS, 1);
        }
    }
}