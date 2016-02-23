<?php

/**
 * PDFMerger
 *
 *
 * @author 		Dualcube
 * @package 	Class/
 * @version    0.0.1
 */


 
class DC_Rate_Plan_Pdf_All_PDFMerger
{
    private $_files;    //['form.pdf']  ["1,2,4, 5-19"]
    private $_fpdi;

    /**
     * Add a PDF for inclusion in the merge with a valid file path. Pages should be formatted: 1,3,6, 12-16.
     * @param $filepath
     * @param $pages
     * @return void
     */
    public function addPDF($filepath, $pages = 'all', $orientation = null)
    {
        if (file_exists($filepath)) {
            if (strtolower($pages) != 'all') {
                $pages = $this->_rewritepages($pages);
            }

            $this->_files[] = array($filepath, $pages, $orientation);
        } else {
            throw new Exception("Could not locate PDF on '$filepath'");
        }

        return $this;
    }

    /**
     * Merges your provided PDFs and outputs to specified location.
     * @param $outputmode
     * @param $outputname
     * @param $orientation
     * @return PDF
     */
    public function merge($outputmode = 'browser', $outputpath = 'newfile.pdf', $orientation = 'P')
    {
        if (!isset($this->_files) || !is_array($this->_files)) {
            throw new Exception("No PDFs to merge.");
        }

        $fpdi = new FPDI_with_annots;

        // merger operations
        foreach ($this->_files as $file) {
            $filename  = $file[0];
            $filepages = $file[1];
            $fileorientation = (!is_null($file[2])) ? $file[2] : $orientation;

            $count = $fpdi->setSourceFile($filename);

            //add the pages
            if ($filepages == 'all') {
                for ($i=1; $i<=$count; $i++) {
                    $template   = $fpdi->importPage($i);
                    $size       = $fpdi->getTemplateSize($template);

                    $fpdi->AddPage($fileorientation, array($size['w'], $size['h']));
                    $fpdi->useTemplate($template);
                }
            } else {
                foreach ($filepages as $page) {
                    if (!$template = $fpdi->importPage($page)) {
                        throw new Exception("Could not load page '$page' in PDF '$filename'. Check that the page exists.");
                    }
                    $size = $fpdi->getTemplateSize($template);

                    $fpdi->AddPage($fileorientation, array($size['w'], $size['h']));
                    $fpdi->useTemplate($template);
                }
            }
        }

        //output operations
        $mode = $this->_switchmode($outputmode);

        if ($mode == 'S') {
            return $fpdi->Output($outputpath, 'S');
        } else {
            if ($fpdi->Output($outputpath, $mode) == '') {
                return true;
            } else {
                throw new Exception("Error outputting PDF to '$outputmode'.");
                return false;
            }
        }


    }

    /**
     * FPDI uses single characters for specifying the output location. Change our more descriptive string into proper format.
     * @param $mode
     * @return Character
     */
    private function _switchmode($mode)
    {
        switch(strtolower($mode))
        {
            case 'download':
                return 'D';
                break;
            case 'browser':
                return 'I';
                break;
            case 'file':
                return 'F';
                break;
            case 'string':
                return 'S';
                break;
            default:
                return 'I';
                break;
        }
    }

    /**
     * Takes our provided pages in the form of 1,3,4,16-50 and creates an array of all pages
     * @param $pages
     * @return unknown_type
     */
    private function _rewritepages($pages)
    {
        $pages = str_replace(' ', '', $pages);
        $part = explode(',', $pages);

        //parse hyphens
        foreach ($part as $i) {
            $ind = explode('-', $i);

            if (count($ind) == 2) {
                $x = $ind[0]; //start page
                $y = $ind[1]; //end page

                if ($x > $y) {
                    throw new Exception("Starting page, '$x' is greater than ending page '$y'.");
                    return false;
                }

                //add middle pages
                while ($x <= $y) {
                    $newpages[] = (int) $x;
                    $x++;
                }
            } else {
                $newpages[] = (int) $ind[0];
            }
        }

        return $newpages;
    }
}
