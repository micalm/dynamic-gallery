<?php
/**
 * Copyright 2018 Mateusz Micał and Contributors
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions
 * of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 * TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */

$galleryName = basename(__DIR__);
$files = scandir(__DIR__);
$images = [];

if (!empty($files)) {
    foreach ($files as $file) {
        if (@exif_imagetype(__DIR__  . '/' . $file)) {
            $imagick = new Imagick(realpath('./' . $file));
            $imagick->setImageFormat('jpeg');
            $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
            $imagick->setImageCompressionQuality(100);
            $imagick->cropThumbnailImage(512, 512);
            $thumbnail = base64_encode($imagick->getImageBlob());
            $images[] = ['filename' => $file, 'uri' => './' . $file, 'thumbnail' => $thumbnail];
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
     <meta charset="utf-8">
     <title><?= $galleryName ?></title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <style>
        body {
            background: #242424;
            color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif,
            "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            font-weight: light;
        }
        header h1 {
            margin: auto auto;
            width: 100%;
            text-align: center;
        }
        main {
            max-width: 100vw;
        }
        main.flex-parent {
            display: flex;
            flex-flow: row wrap;
            justify-content: flex-start;
        }
        article {
            display: block;
            margin: auto;
            max-width: 100%;
        }
        article img,
        article.filler {
            width: 23vw;
            height: 40vh;
            max-width: 100%;
        }
        aside {
            position: relative;
            top: -2em;
            text-align: center;
            background: rgba(0,0,0,.3);
            padding: .4em;
        }
        footer {
            position: fixed;
            bottom: 0px;
            width: 100%;
            background: rgba(0,0,0,.3);
            padding: .35em;
        }
        footer p {
            text-align: center;
        }
        a {
            color: #ddd;
            text-decoration: none;
        }
        @media (max-width: 1025px) {
            article img,
            article.filler {
                width: 50vh;
                height: 25vh;
            }
        }
        @media (max-width: 1441px) {
            article img,
            article.filler {
                width: 80vh;
                height: 50vh;
            }
        }
     </style>
</head>
<body>
    <header>
        <h1><?= $galleryName ?></h1>
    </header>
    <main class="flex-parent">
    <?php
    if (!empty($images)) :
        foreach ($images as $image) :
    ?>
        <article>
            <main>
                <a href="<?= $image['uri'] ?>">
                    <img src="data:image/jpeg;base64, <?= $image['thumbnail'] ?>" alt="<?= $image['filename'] ?>">
                </a>
            </main>
            <aside>
                <?= $image['filename'] ?>
            </aside>
        </article>
    <?php
        endforeach;
    endif;
    ?>
    <?php for ($i = 0; $i <= abs((count($images) % 4) - 4); $i++): ?>
        <article class="filler"></article>
    <?php endfor; ?>
</main>
    <footer>
        <p>Dynamic Gallery by <a href="https://mical.pl/">Mateusz Micał <?= date('Y') ?></a></p>
    </footer>
</body>
</html>
