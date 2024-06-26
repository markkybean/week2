<?php

class tab_ezpdf extends Cezpdf
{
    function ezPlaceData($xpos, $ypos, $data, $font_size, $align)
    {
        global $glo_arr;
        global $glo_top;
        $top = $glo_top + 700;

        switch ($align) {
            case "right":
                $formatted = $data;
                $formatted_width = $this->getTextWidth($font_size, $formatted);
                $x = $xpos - $formatted_width;
                $this->addText($x, $ypos, $font_size, $formatted, $angle = 0, $wordSpaceAdjust = 1);
                $glo_arr[$top - $ypos][$x] = $formatted;
                break;

            case "centre":
            case "center":
                $formatted = $data;
                $formatted_width = $this->getTextWidth($font_size, $formatted);
                $x = $xpos - ($formatted_width / 2);
                $this->addText($x, $ypos, $font_size, $formatted, $angle = 0, $wordSpaceAdjust = 1);
                $glo_arr[$top - $ypos][$x] = $formatted;
                break;

            default:
            case "left":
                $this->addText($xpos, $ypos, $font_size, $data, $angle = 0, $wordSpaceAdjust = 1);
                $glo_arr[$top - $ypos][$xpos] = $data;
                break;
        }
    }

    function ezStream()
    {
        global $glo_arr;

        ksort($glo_arr);

        $xchunk3 = '';
        $xline = "\r\n";
        $xtab = "\t";

        foreach ($glo_arr as $key => $value) {
            ksort($glo_arr[$key]);
        }

        foreach ($glo_arr as $key => $value) {
            foreach ($glo_arr[$key] as $key2 => $value2) {
                $xchunk3 .= $glo_arr[$key][$key2] . $xtab;
            }
            $xchunk3 .= $xline;
        }

        $xchunk3 = str_replace("<b>", "", $xchunk3);
        $xchunk3 = str_replace("</b>", "", $xchunk3);
        $xchunk3 = str_replace("<i>", "", $xchunk3);
        $xchunk3 = str_replace("</i>", "", $xchunk3);

        $xfilename = uniqid(rand(1, 100000), true) . ".xls";

        $xfhndl = fopen($xfilename, 'w+');
        if (fwrite($xfhndl, $xchunk3) === false) {
            echo "Cannot write to file ($xfilename)";
            exit;
        }
        fclose($xfhndl);

        header("Cache-control: private");
        header("Content-Disposition: attachment; filename=$xfilename");
        header("Content-Type: application/octet-stream");
        header("Content-Transfer-Encoding: binary");
        header("Pragma: no-cache");
        header("Expires: 0");

        readfile($xfilename);
        unlink($xfilename);
    }

    function ezStream_zip($xzip_filename = "", $xpassword = "")
    {
        global $glo_arr;

        ksort($glo_arr);

        $xchunk3 = '';
        $xline = "\r\n";
        $xtab = "\t";

        foreach ($glo_arr as $key => $value) {
            ksort($glo_arr[$key]);
        }

        foreach ($glo_arr as $key => $value) {
            foreach ($glo_arr[$key] as $key2 => $value2) {
                $xchunk3 .= $glo_arr[$key][$key2] . $xtab;
            }
            $xchunk3 .= $xline;
        }

        $xchunk3 = str_replace("<b>", "", $xchunk3);
        $xchunk3 = str_replace("</b>", "", $xchunk3);
        $xchunk3 = str_replace("<i>", "", $xchunk3);
        $xchunk3 = str_replace("</i>", "", $xchunk3);

        $xfilename = uniqid(rand(1, 100000), true) . ".txt";
        $xfilename_zip = "$xzip_filename.zip";

        $xfhndl = fopen($xfilename, 'w+');
        if (fwrite($xfhndl, $xchunk3) === false) {
            echo "Cannot write to file ($xfilename)";
            exit;
        }
        fclose($xfhndl);

        $zip = new ZipArchive;
        if ($zip->open($xfilename_zip, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($xfilename);
            $zip->close();
        }

        if (trim($xpassword) != "") {
            $xos = PHP_OS;
            if ($xos == "Linux" || strtoupper($xos) == "LINUX") {
                system("zip -P " . $xpassword . " $xfilename_zip $xfilename");
            } else {
                $xdir = dirname(__FILE__);
                system("$xdir\\zip_exe\\zip.exe -P " . $xpassword . " $xfilename_zip $xfilename");
            }
        }

        header("Cache-control: private,must-revalidate");
        header("Content-Disposition: attachment; filename=$xfilename_zip");
        header("Content-Type: application/octet-stream");
        header("Content-Transfer-Encoding: binary");
        header("Pragma: no-cache");
        header("Expires: 0");

        readfile($xfilename_zip);

        unlink($xfilename);
        unlink($xfilename_zip);
    }

    function ezNewPage()
    {
        global $glo_top;
        $glo_top = $glo_top + 1000;

        $pageRequired = 1;

        if (isset($this->ez['columns']) && $this->ez['columns']['on'] == 1) {
            $this->ez['columns']['colNum']++;

            if ($this->ez['columns']['colNum'] <= $this->ez['columns']['options']['num']) {
                $pageRequired = 0;
            } else {
                $this->ez['columns']['colNum'] = 1;
                $this->ez['topMargin'] = $this->ez['columns']['margins'][2];
            }

            $width = $this->ez['columns']['width'];
            $this->ez['leftMargin'] = $this->ez['columns']['margins'][0] + ($this->ez['columns']['colNum'] - 1) * ($this->ez['columns']['options']['gap'] + $width);
            $this->ez['rightMargin'] = $this->ez['pageWidth'] - $this->ez['leftMargin'] - $width;
        }

        if ($pageRequired) {
            $this->y = $this->ez['pageHeight'] - $this->ez['topMargin'];
            $this->ezPageCount++;

            if (isset($this->ez['insertMode']) && $this->ez['insertMode'] == 1) {
                $id = $this->ezPages[$this->ezPageCount] = $this->newPage(1, $this->ez['insertOptions']['id'], $this->ez['insertOptions']['pos']);
                $this->ez['insertOptions']['id'] = $id;
                $this->ez['insertOptions']['pos'] = 'after';
            } else {
                $this->ezPages[$this->ezPageCount] = $this->newPage();
            }
        } else {
            $this->y = $this->ez['pageHeight'] - $this->ez['topMargin'];
        }
    }
}

?>
