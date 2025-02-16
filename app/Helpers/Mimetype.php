<?php

namespace App\Helpers;

class MimeType
{
    public static function whiteList(string $filename): bool
    {
        return preg_match("/\\b(" . env('FILE_WHITELIST') . ")\\b/i", $filename) === 1;
    }

    public static function blackList(string $filename): bool
    {
        return preg_match("/(" . env('FILE_BLACKLIST') . ")/i", $filename) === 1;
    }

    public static function whiteListBytes(string $content, string $filename): bool
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $validJpgBytes = [255, 216, 255, 224, 0, 16, 74, 70, 73, 70, 0, 1];
                $listJpgBytes = array_values(unpack('C*', substr($content, 0, 12)));
                $listMatchJpgBytes = array_map(fn($v, $i) => $v == $validJpgBytes[$i], $listJpgBytes, array_keys($validJpgBytes));
                $listNotMatchJpgBytes = array_filter($listMatchJpgBytes, fn($v) => $v === false);
                return !in_array(false, $listMatchJpgBytes) || count($listNotMatchJpgBytes) <= 6;

            case 'png':
                $validPngBytes = [137, 80, 78, 71, 13, 10, 26, 10];
                $listPngBytes = array_values(unpack('C*', substr($content, 0, 8)));
                $listMatchPngBytes = array_map(fn($v, $i) => $v == $validPngBytes[$i], $listPngBytes, array_keys($validPngBytes));
                $listNotMatchPngBytes = array_filter($listMatchPngBytes, fn($v) => $v === false);
                return !in_array(false, $listMatchPngBytes) || count($listNotMatchPngBytes) <= 4;

            case 'gif':
                $validGifBytes = [71, 73, 70, 56, 55, 97];
                $listGifBytes = array_values(unpack('C*', substr($content, 0, 6)));
                $listMatchGifBytes = array_map(fn($v, $i) => $v == $validGifBytes[$i], $listGifBytes, array_keys($validGifBytes));
                $listNotMatchGifBytes = array_filter($listMatchGifBytes, fn($v) => $v === false);
                return !in_array(false, $listMatchGifBytes) || count($listNotMatchGifBytes) <= 3;

            case 'pdf':
                $validPdfBytes = [37, 80, 68, 70, 45];
                $listPdfBytes = array_values(unpack('C*', substr($content, 0, 5)));
                $listMatchPdfBytes = array_map(fn($v, $i) => $v == $validPdfBytes[$i], $listPdfBytes, array_keys($validPdfBytes));
                $listNotMatchPdfBytes = array_filter($listMatchPdfBytes, fn($v) => $v === false);
                return !in_array(false, $listMatchPdfBytes) || count($listNotMatchPdfBytes) <= 2;

            case 'doc':
            case 'xls':
                $validDocOrXlsBytes = [208, 207, 17, 224, 161, 177, 26, 225];
                $listDocOrXlsBytes = array_values(unpack('C*', substr($content, 0, 8)));
                $listMatchDocOrXlsBytes = array_map(fn($v, $i) => $v == $validDocOrXlsBytes[$i], $listDocOrXlsBytes, array_keys($validDocOrXlsBytes));
                $listNotMatchDocOrXlsBytes = array_filter($listMatchDocOrXlsBytes, fn($v) => $v === false);
                return !in_array(false, $listMatchDocOrXlsBytes) || count($listNotMatchDocOrXlsBytes) <= 4;

            case 'docx':
            case 'xlsx':
                $validDocxOrXlsxBytes = [80, 75, 3, 4];
                $listDocxOrXlsxBytes = array_values(unpack('C*', substr($content, 0, 4)));
                $listMatchDocxOrXlsxBytes = array_map(fn($v, $i) => $v == $validDocxOrXlsxBytes[$i], $listDocxOrXlsxBytes, array_keys($validDocxOrXlsxBytes));
                $listNotMatchDocxOrXlsxBytes = array_filter($listMatchDocxOrXlsxBytes, fn($v) => $v === false);
                return !in_array(false, $listMatchDocxOrXlsxBytes) || count($listNotMatchDocxOrXlsxBytes) <= 2;

            case 'webp':
                $validWebpBytes = [82, 73, 70, 70, 98, 119, 0, 0, 87, 69, 66, 80];
                $listWebpBytes = array_values(unpack('C*', substr($content, 0, 12)));
                $listMatchWebpBytes = array_map(fn($v, $i) => $v == $validWebpBytes[$i], $listWebpBytes, array_keys($validWebpBytes));
                $listNotMatchWebpBytes = array_filter($listMatchWebpBytes, fn($v) => $v === false);
                return !in_array(false, $listMatchWebpBytes) || count($listNotMatchWebpBytes) <= 6;

            case 'csv':
                return true;

            default:
                return false;
        }
    }
}
