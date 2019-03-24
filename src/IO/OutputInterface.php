<?php

namespace Jennifer\IO;

interface OutputInterface
{
    public function ajax($data, $json = false, $jsonOpt = JSON_UNESCAPED_SLASHES);

    public function download(DownloadableInterface $downloadable);

    public function html($html = "");
}