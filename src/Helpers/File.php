<?php

namespace Elab\Lite\Helpers;

class File
{
    public static function move_file($src, $dest) {
        $dir = dirname($dest);
        if(!is_dir($dir)) {
            mkdir($dir,0775, true);
        }
        if(copy($src, $dest)) {
            return unlink($src);
        }
        return false;
    }


    public static function get_font_file($font_name)
    {
        $fonts_paths = array();
        if (defined('FONTS_PATH')) {
            $fonts_paths[] = FONTS_PATH;
        }
        foreach ($fonts_paths as $path) {
            $font_file = $path . $font_name;
            if (file_exists($font_file)) {
                return $font_file;
            }
        }
        return $font_name;
    }

    public static function mime2ext($mime)
    {
        $types = array(
            'application/bmp' => 'bmp',
            'application/cdr' => 'cdr',
            'application/coreldraw' => 'cdr',
            'application/csv' => 'csv',
            'application/excel' => 'xls',
            'application/java-archive' => 'jar',
            'application/json' => 'json',
            'application/msexcel' => 'xls',
            'application/msword' => 'doc',
            'application/pdf' => 'pdf',
            'application/php' => 'php',
            'application/postscript' => 'ps',
            'application/powerpoint' => 'ppt',
            'application/rar' => 'rar',
            'application/s-compressed' => 'zip',
            'application/videolan' => 'vlc',
            'application/vnd.google-earth.kml+xml' => 'kml',
            'application/vnd.google-earth.kmz' => 'kmz',
            'application/vnd.mpegurl' => 'm4u',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.ms-office' => 'doc',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/x-bmp' => 'bmp',
            'application/x-cdr' => 'cdr',
            'application/x-compress' => 'z',
            'application/x-compressed' => '7zip',
            'application/x-coreldraw' => 'cdr',
            'application/x-csv' => 'csv',
            'application/x-dos_ms_excel' => 'xls',
            'application/x-excel' => 'xls',
            'application/x-gtar' => 'gtar',
            'application/x-gzip' => 'gz',
            'application/x-gzip-compressed' => 'tgz',
            'application/x-httpd-php' => 'php',
            'application/x-httpd-php-source' => 'php',
            'application/x-jar' => 'jar',
            'application/x-java-application' => 'jar',
            'application/x-javascript' => 'js',
            'application/x-ms-excel' => 'xls',
            'application/x-msexcel' => 'xls',
            'application/x-photoshop' => 'psd',
            'application/x-php' => 'php',
            'application/x-rar' => 'rar',
            'application/x-rar-compressed' => 'rar',
            'application/x-shockwave-flash' => 'swf',
            'application/x-tar' => 'tar',
            'application/x-troff-msvideo' => 'avi',
            'application/x-win-bitmap' => 'bmp',
            'application/x-xls' => 'xls',
            'application/x-zip' => 'zip',
            'application/x-zip-compressed' => 'zip',
            'application/xhtml+xml' => 'xhtml',
            'application/xls' => 'xls',
            'application/xml' => 'xml',
            'application/zip' => 'zip',
            'audio/ac3' => 'ac3',
            'audio/aiff' => 'aif',
            'audio/midi' => 'mid',
            'audio/mp3' => 'mp3',
            'audio/mpeg' => 'mp3',
            'audio/mpeg3' => 'mp3',
            'audio/mpg' => 'mp3',
            'audio/ogg' => 'ogg',
            'audio/wav' => 'wav',
            'audio/wave' => 'wav',
            'audio/x-aiff' => 'aif',
            'audio/x-au' => 'au',
            'audio/x-flac' => 'flac',
            'audio/x-m4a' => 'm4a',
            'audio/x-ms-wma' => 'wma',
            'audio/x-wav' => 'wav',
            'image/bmp' => 'bmp',
            'image/cdr' => 'cdr',
            'image/gif' => 'gif',
            'image/jpeg' => 'jpg',
            'image/ms-bmp' => 'bmp',
            'image/pjpeg' => 'jpg',
            'image/png' => 'png',
            'image/svg+xml' => 'svg',
            'image/tiff' => 'tif',
            'image/vnd.adobe.photoshop' => 'psd',
            'image/vnd.microsoft.icon' => 'ico',
            'image/x-bitmap' => 'bmp',
            'image/x-bmp' => 'bmp',
            'image/x-cdr' => 'cdr',
            'image/x-ico' => 'ico',
            'image/x-icon' => 'ico',
            'image/x-ms-bmp' => 'bmp',
            'image/x-png' => 'png',
            'image/x-win-bitmap' => 'bmp',
            'image/x-windows-bmp' => 'bmp',
            'image/x-xbitmap' => 'bmp',
            'message/rfc822' => 'eml',
            'multipart/x-zip' => 'zip',
            'text/comma-separated-values' => 'csv',
            'text/css' => 'css',
            'text/csv' => 'csv',
            'text/html' => 'html',
            'text/json' => 'json',
            'text/php' => 'php',
            'text/plain' => 'txt',
            'text/richtext' => 'rtx',
            'text/rtf' => 'rtf',
            'text/srt' => 'srt',
            'text/vtt' => 'vtt',
            'text/x-comma-separated-values' => 'csv',
            'text/x-csv' => 'csv',
            'text/x-log' => 'log',
            'text/x-php' => 'php',
            'text/x-vcard' => 'vcf',
            'text/xml' => 'xml',
            'text/xsl' => 'xsl',
            'video/3gp' => '3gp',
            'video/3gpp' => '3gp',
            'video/avi' => 'avi',
            'video/mp4' => 'mp4',
            'video/mpeg' => 'mpg',
            'video/msvideo' => 'avi',
            'video/quicktime' => 'mov',
            'video/x-f4v' => 'f4v',
            'video/x-flv' => 'flv',
            'video/x-ms-asf' => 'wmv',
            'video/x-ms-wmv' => 'wmv',
            'video/x-msvideo' => 'avi',
            'zz-application/zz-winassoc-cdr' => 'cdr',
        );
        return @$types[$mime];
    }

    /**
     * is failo pavadinimo atskiria pletini (extension)
     *
     * @param Str $file_name - failo pavadinimas
     * @param boolean $dot - ar rodyti taska
     * @return Str - pletinys
     */
    public static function file_ext($file_name, $dot = false)
    {
        $ext = strrchr($file_name, ".");
        if ($ext && !$dot) {
            $ext = substr($ext, 1);
        }
        return $ext;
    }

    /**
     * suformuoja "validu" failo pavadinima is stringo
     * arba seno failo vardo, pagal tam tikrus parametrus
     *
     * @param Str $str - is ko formuosim failo varda
     * @param array $params:
     * 		'length' - failo vardo max ilgis (kartu su pletiniu)
     * 		'ext' - failo pletinys
     * 		'prefix' - failo prefixas
     */
    public static function generate_file_name($str, $params = array())
    {
        $str = mb_strtolower($str, "utf-8");
        $ext = self::file_ext($str); // originalus pletinys be tasko
        if (@$params['fname']) {
            $fname = $params['fname'];
        } else {
            $fname = substr($str, 0, strlen($str) - strlen($ext)); // failo pavadinimas be pletinio
            $fname = Inflector::slug($fname);
        }

        // apdorojam paramsus:
        // 1. Pletinys
        if (!empty($params['ext'])) {
            $ext = $params['ext'];
        }
        // 2. prefiksas
        if (!empty($params['prefix'])) {
            $fname = "$params[prefix]_$fname";
        }
        // 3. ilgis
        if (!empty($params['length']) && is_numeric($params['length'])) {
            $length = $ext ? $params['length'] - 1 - strlen($ext) : $params['length'];
            $fname = substr($fname, 0, $length);
        }

        if ($ext) {
            $fname = "$fname.$ext";
        }
        return $fname;
    }

    public static function human_file_size($bytes, $decimal = '2')
    {
        if (is_numeric($bytes)) {
            $position = 0;
            $units = array(" Bytes", " KB", " MB", " GB", " TB");
            while ($bytes >= 1024 && ($bytes / 1024) >= 1) {
                $bytes /= 1024;
                $position++;
            }
            return round($bytes, $decimal) . $units[$position];
        } else {
            return "0 Bytes";
        }
    }

    public static function delete_directory($dir)
    {
        if (substr($dir, strlen($dir) - 1, 1) != '/') {
            $dir .= '/';
        }
        if ($handle = opendir($dir)) {
            while ($obj = readdir($handle)) {
                if ($obj != '.' && $obj != '..') {
                    if (is_dir($dir . $obj)) {
                        if (!self::delete_directory($dir . $obj)) {
                            return false;
                        }
                    } elseif (is_file($dir . $obj)) {
                        if (!unlink($dir . $obj)) {
                            return false;
                        }
                    }
                }
            }
            closedir($handle);
            if (!@rmdir($dir)) {
                return false;
            }
            return true;
        }
        return false;
    }

    public static function directory_map($source_dir, $directory_depth = 0, $hidden = false, $full_path = true, $ext = false)
    {
        if ($fp = @opendir($source_dir)) {
            $filedata = array();
            $new_depth = $directory_depth - 1;
            $source_dir = rtrim($source_dir, "/") . "/";

            while (false !== ($file = readdir($fp))) {
                // Remove '.', '..', and hidden files [optional]
                if (!trim($file, '.') or ($hidden == false && $file[0] == '.')) {
                    continue;
                }

                if (($directory_depth < 1 or $new_depth > 0) && @is_dir($source_dir . $file)) {
                    $filedata = array_merge($filedata, self::directory_map($source_dir . $file . "/", $new_depth, $hidden));
                } else {
                    $dir = ($full_path) ? $source_dir : "";

                    if (!empty($ext)) {
                        if (Str::endsWith($file, $ext) !== false) {
                            $filedata[] = $dir . $file;
                        }
                    } else {
                        $filedata[] = $dir . $file;
                    }
                }
            }

            closedir($fp);
            return $filedata;
        }

        return array();
    }


    /*
     * Find files in a directory matching a pattern
     *
     *
     * Paul Gregg <pgregg@pgregg.com>
     * 20 March 2004,  Updated 20 April 2004
     * Updated 18 April 2007 to add the ability to sort the result set
     * Updated 9 June 2007 to prevent multiple calls to sort during recursion
     * Updated 12 June 2009 to allow for sorting by extension and prevent following
     * symlinks by default
     * Version: 2.3
     * This function is backwards capatible with any code written for a
     * previous version of preg_find()
     *
     * Open Source Code:   If you use this code on your site for public
     * access (i.e. on the Internet) then you must attribute the author and
     * source web site: http://www.pgregg.com/projects/php/preg_find/preg_find.phps
     * Working examples: http://www.pgregg.com/projects/php/preg_find/
     *
     */

    public const PREG_FIND_RECURSIVE = 1;
    public const PREG_FIND_DIRMATCH = 2;
    public const PREG_FIND_FULLPATH = 4;
    public const PREG_FIND_NEGATE = 8;
    public const PREG_FIND_DIRONLY = 16;
    public const PREG_FIND_RETURNASSOC = 32;
    public const PREG_FIND_SORTDESC = 64;
    public const PREG_FIND_SORTKEYS = 128;
    public const PREG_FIND_SORTBASENAME = 256; # requires PREG_FIND_RETURNASSOC
    public const PREG_FIND_SORTMODIFIED = 512; # requires PREG_FIND_RETURNASSOC
    public const PREG_FIND_SORTFILESIZE = 1024; # requires PREG_FIND_RETURNASSOC
    public const PREG_FIND_SORTDISKUSAGE = 2048; # requires PREG_FIND_RETURNASSOC
    public const PREG_FIND_SORTEXTENSION = 4096; # requires PREG_FIND_RETURNASSOC
    public const PREG_FIND_FOLLOWSYMLINKS = 8192;

    // PREG_FIND_RECURSIVE   - go into subdirectorys looking for more files
    // PREG_FIND_DIRMATCH    - return directorys that match the pattern also
    // PREG_FIND_DIRONLY     - return only directorys that match the pattern (no files)
    // PREG_FIND_FULLPATH    - search for the pattern in the full path (dir+file)
    // PREG_FIND_NEGATE      - return files that don't match the pattern
    // PREG_FIND_RETURNASSOC - Instead of just returning a plain array of matches,
    //                         return an associative array with file stats
    // PREG_FIND_FOLLOWSYMLINKS - Recursive searches (from v2.3) will no longer
    //                            traverse symlinks to directories, unless you
    //                            specify this flag. This is to prevent nasty
    //                            endless loops.
    //
    // You can also request to have the results sorted based on various criteria
    // By default if any sorting is done, it will be sorted in ascending order.
    // You can reverse this via use of:
    // PREG_FIND_SORTDESC    - Reverse order of sort
    // PREG_FILE_SORTKEYS    - Sort on the keyvalues or non-assoc array results
    // The following sorts *require* PREG_FIND_RETURNASSOC to be used as they are
    // sorting on values stored in the constructed associative array
    // PREG_FIND_SORTBASENAME - Sort the results in alphabetical order on filename
    // PREG_FIND_SORTMODIFIED - Sort the results in last modified timestamp order
    // PREG_FIND_SORTFILESIZE  - Sort the results based on filesize
    // PREG_FILE_SORTDISKUSAGE - Sort based on the amount of disk space taken
    // PREG_FIND_SORTEXTENSION - Sort based on the filename extension
    // to use more than one simply seperate them with a | character
    // Search for files matching $pattern in $start_dir.
    // if args contains PREG_FIND_RECURSIVE then do a recursive search
    // return value is an associative array, the key of which is the path/file
    // and the value is the stat of the file.
    public function preg_find($pattern, $start_dir = '.', $args = null)
    {
        static $depth = -1;
        ++$depth;

        $files_matched = array();

        $fh = opendir($start_dir);

        while (($file = readdir($fh)) !== false) {
            if (strcmp($file, '.') == 0 || strcmp($file, '..') == 0) {
                continue;
            }
            $filepath = $start_dir . '/' . $file;
            if (preg_match($pattern, ($args & self::PREG_FIND_FULLPATH) ? $filepath : $file)) {
                $doadd = is_file($filepath) || (is_dir($filepath) && ($args & self::PREG_FIND_DIRMATCH)) || (is_dir($filepath) && ($args & self::PREG_FIND_DIRONLY));
                if ($args & self::PREG_FIND_DIRONLY && $doadd && !is_dir($filepath)) {
                    $doadd = false;
                }
                if ($args & self::PREG_FIND_NEGATE) {
                    $doadd = !$doadd;
                }
                if ($doadd) {
                    if ($args & self::PREG_FIND_RETURNASSOC) { // return more than just the filenames
                        $fileres = array();
                        if (function_exists('stat')) {
                            $fileres['stat'] = stat($filepath);
                            $fileres['du'] = $fileres['stat']['blocks'] * 512;
                        }
                        if (function_exists('fileowner')) {
                            $fileres['uid'] = fileowner($filepath);
                        }
                        if (function_exists('filegroup')) {
                            $fileres['gid'] = filegroup($filepath);
                        }
                        if (function_exists('filetype')) {
                            $fileres['filetype'] = filetype($filepath);
                        }
                        if (function_exists('mime_content_type')) {
                            $fileres['mimetype'] = mime_content_type($filepath);
                        }
                        if (function_exists('dirname')) {
                            $fileres['dirname'] = dirname($filepath);
                        }
                        if (function_exists('basename')) {
                            $fileres['basename'] = basename($filepath);
                        }
                        if (($i = strrpos($fileres['basename'], '.')) !== false) {
                            $fileres['ext'] = substr($fileres['basename'], $i + 1);
                        } else {
                            $fileres['ext'] = '';
                        }
                        if (isset($fileres['uid']) && function_exists('posix_getpwuid')) {
                            $fileres['owner'] = posix_getpwuid($fileres['uid']);
                        }
                        $files_matched[$filepath] = $fileres;
                    } else {
                        array_push($files_matched, $filepath);
                    }
                }
            }
            if (is_dir($filepath) && ($args & self::PREG_FIND_RECURSIVE)) {
                if (!is_link($filepath) || ($args & self::PREG_FIND_FOLLOWSYMLINKS)) {
                    $files_matched = array_merge($files_matched, preg_find($pattern, $filepath, $args));
                }
            }
        }

        closedir($fh);

        // Before returning check if we need to sort the results.
        if (($depth == 0) && ($args & (self::PREG_FIND_SORTKEYS | self::PREG_FIND_SORTBASENAME | self::PREG_FIND_SORTMODIFIED | self::PREG_FIND_SORTFILESIZE | self::PREG_FIND_SORTDISKUSAGE))) {
            $order = ($args & self::PREG_FIND_SORTDESC) ? 1 : -1;
            $sortby = '';
            if ($args & self::PREG_FIND_RETURNASSOC) {
                if ($args & self::PREG_FIND_SORTMODIFIED) {
                    $sortby = "['stat']['mtime']";
                }
                if ($args & self::PREG_FIND_SORTBASENAME) {
                    $sortby = "['basename']";
                }
                if ($args & self::PREG_FIND_SORTFILESIZE) {
                    $sortby = "['stat']['size']";
                }
                if ($args & self::PREG_FIND_SORTDISKUSAGE) {
                    $sortby = "['du']";
                }
                if ($args & self::PREG_FIND_SORTEXTENSION) {
                    $sortby = "['ext']";
                }
            }
            $filesort = create_function('$a,$b', "\$a1=\$a$sortby;\$b1=\$b$sortby; if (\$a1==\$b1) return 0; else return (\$a1<\$b1) ? $order : 0- $order;");
            uasort($files_matched, $filesort);
        }
        --$depth;
        return $files_matched;
    }
}
