<?php
/**
 * Barcode Generator
 * 
 * Generates various types of barcodes as PNG images.
 * Supports Code128, Code128A, Code128B, Code39, Code25, and Codabar.
 * 
 * @version 4.0
 * @author Original: David S. Tufts (davidscotttufts.com)
 * @author Updated: Pro Developer
 * @date 2023-10-15
 * @usage <img src="/barcode.php?text=testing&codetype=code128" alt="Barcode" />
 */

// Sanitize and validate input parameters with defaults
$filepath = filter_input(INPUT_GET, 'filepath', FILTER_SANITIZE_STRING) ?: '';
$text = filter_input(INPUT_GET, 'text', FILTER_SANITIZE_STRING) ?: '0';
$size = filter_input(INPUT_GET, 'size', FILTER_VALIDATE_INT) ?: 20;
$orientation = filter_input(INPUT_GET, 'orientation', FILTER_SANITIZE_STRING) ?: 'horizontal';
$code_type = filter_input(INPUT_GET, 'codetype', FILTER_SANITIZE_STRING) ?: 'code128';
$print = filter_input(INPUT_GET, 'print', FILTER_SANITIZE_STRING) === 'true';
$sizefactor = filter_input(INPUT_GET, 'sizefactor', FILTER_VALIDATE_FLOAT) ?: 1.0;

// Generate the barcode
generateBarcode($filepath, $text, $size, $orientation, $code_type, $print, $sizefactor);

/**
 * Generates a barcode image
 * 
 * @param string $filepath Path to save the image (if empty, outputs to browser)
 * @param string $text Text to encode in the barcode
 * @param int $size Height of the barcode (for horizontal) or width (for vertical)
 * @param string $orientation 'horizontal' or 'vertical'
 * @param string $code_type Type of barcode (code128, code128a, code128b, code39, code25, codabar)
 * @param bool $print Whether to print the text below the barcode
 * @param float $sizeFactor Scaling factor for the barcode
 * @return void
 */
function generateBarcode(
    string $filepath = "",
    string $text = "0",
    int $size = 20,
    string $orientation = "horizontal",
    string $code_type = "code128",
    bool $print = false,
    float $sizeFactor = 1.0
): void {
    $code_string = "";
    $code_type = strtolower($code_type);
    
    // Generate the appropriate code string based on barcode type
    switch ($code_type) {
        case "code128":
        case "code128b":
            $code_string = generateCode128($text);
            break;
        case "code128a":
            $code_string = generateCode128A($text);
            break;
        case "code39":
            $code_string = generateCode39($text);
            break;
        case "code25":
            $code_string = generateCode25($text);
            break;
        case "codabar":
            $code_string = generateCodabar($text);
            break;
        default:
            // Default to Code128 if invalid type provided
            $code_string = generateCode128($text);
    }

    // Create and output the barcode image
    outputBarcodeImage($code_string, $filepath, $text, $size, $orientation, $print, $sizeFactor);
}

/**
 * Generates Code128 barcode pattern
 * 
 * @param string $text Text to encode
 * @return string The encoded pattern
 */
function generateCode128(string $text): string {
    $chksum = 104;
    $code_string = "";
    
    // Code128 character encodings
    $code_array = [
        " "=>"212222", "!"=>"222122", "\""=>"222221", "#"=>"121223", "$"=>"121322", "%"=>"131222", 
        "&"=>"122213", "'"=>"122312", "("=>"132212", ")"=>"221213", "*"=>"221312", "+"=>"231212", 
        ","=>"112232", "-"=>"122132", "."=>"122231", "/"=>"113222", "0"=>"123122", "1"=>"123221", 
        "2"=>"223211", "3"=>"221132", "4"=>"221231", "5"=>"213212", "6"=>"223112", "7"=>"312131", 
        "8"=>"311222", "9"=>"321122", ":"=>"321221", ";"=>"312212", "<"=>"322112", "="=>"322211", 
        ">"=>"212123", "?"=>"212321", "@"=>"232121", "A"=>"111323", "B"=>"131123", "C"=>"131321", 
        "D"=>"112313", "E"=>"132113", "F"=>"132311", "G"=>"211313", "H"=>"231113", "I"=>"231311", 
        "J"=>"112133", "K"=>"112331", "L"=>"132131", "M"=>"113123", "N"=>"113321", "O"=>"133121", 
        "P"=>"313121", "Q"=>"211331", "R"=>"231131", "S"=>"213113", "T"=>"213311", "U"=>"213131", 
        "V"=>"311123", "W"=>"311321", "X"=>"331121", "Y"=>"312113", "Z"=>"312311", "["=>"332111", 
        "\\"=>"314111", "]"=>"221411", "^"=>"431111", "_"=>"111224", "`"=>"111422", "a"=>"121124", 
        "b"=>"121421", "c"=>"141122", "d"=>"141221", "e"=>"112214", "f"=>"112412", "g"=>"122114", 
        "h"=>"122411", "i"=>"142112", "j"=>"142211", "k"=>"241211", "l"=>"221114", "m"=>"413111", 
        "n"=>"241112", "o"=>"134111", "p"=>"111242", "q"=>"121142", "r"=>"121241", "s"=>"114212", 
        "t"=>"124112", "u"=>"124211", "v"=>"411212", "w"=>"421112", "x"=>"421211", "y"=>"212141", 
        "z"=>"214121", "{"=>"412121", "|"=>"111143", "}"=>"111341", "~"=>"131141", "DEL"=>"114113", 
        "FNC 3"=>"114311", "FNC 2"=>"411113", "SHIFT"=>"411311", "CODE C"=>"113141", "FNC 4"=>"114131", 
        "CODE A"=>"311141", "FNC 1"=>"411131", "Start A"=>"211412", "Start B"=>"211214", "Start C"=>"211232", 
        "Stop"=>"2331112"
    ];
    
    $code_keys = array_keys($code_array);
    $code_values = array_flip($code_keys);
    
    // Calculate the checksum and build the code string
    for ($i = 0; $i < strlen($text); $i++) {
        $activeKey = $text[$i];
        $code_string .= $code_array[$activeKey];
        $chksum += ($code_values[$activeKey] * ($i + 1));
    }
    
    // Add the checksum character
    $code_string .= $code_array[$code_keys[($chksum % 103)]];
    
    // Add start and stop codes
    return "211214" . $code_string . "2331112";
}

/**
 * Generates Code128A barcode pattern
 * 
 * @param string $text Text to encode
 * @return string The encoded pattern
 */
function generateCode128A(string $text): string {
    $chksum = 103;
    $code_string = "";
    $text = strtoupper($text); // Code 128A doesn't support lower case
    
    // Code128A character encodings (same as Code128 but with control chars instead of lowercase)
    $code_array = [
        " "=>"212222", "!"=>"222122", "\""=>"222221", "#"=>"121223", "$"=>"121322", "%"=>"131222", 
        "&"=>"122213", "'"=>"122312", "("=>"132212", ")"=>"221213", "*"=>"221312", "+"=>"231212", 
        ","=>"112232", "-"=>"122132", "."=>"122231", "/"=>"113222", "0"=>"123122", "1"=>"123221", 
        "2"=>"223211", "3"=>"221132", "4"=>"221231", "5"=>"213212", "6"=>"223112", "7"=>"312131", 
        "8"=>"311222", "9"=>"321122", ":"=>"321221", ";"=>"312212", "<"=>"322112", "="=>"322211", 
        ">"=>"212123", "?"=>"212321", "@"=>"232121", "A"=>"111323", "B"=>"131123", "C"=>"131321", 
        "D"=>"112313", "E"=>"132113", "F"=>"132311", "G"=>"211313", "H"=>"231113", "I"=>"231311", 
        "J"=>"112133", "K"=>"112331", "L"=>"132131", "M"=>"113123", "N"=>"113321", "O"=>"133121", 
        "P"=>"313121", "Q"=>"211331", "R"=>"231131", "S"=>"213113", "T"=>"213311", "U"=>"213131", 
        "V"=>"311123", "W"=>"311321", "X"=>"331121", "Y"=>"312113", "Z"=>"312311", "["=>"332111", 
        "\\"=>"314111", "]"=>"221411", "^"=>"431111", "_"=>"111224", "NUL"=>"111422", "SOH"=>"121124", 
        "STX"=>"121421", "ETX"=>"141122", "EOT"=>"141221", "ENQ"=>"112214", "ACK"=>"112412", 
        "BEL"=>"122114", "BS"=>"122411", "HT"=>"142112", "LF"=>"142211", "VT"=>"241211", 
        "FF"=>"221114", "CR"=>"413111", "SO"=>"241112", "SI"=>"134111", "DLE"=>"111242", 
        "DC1"=>"121142", "DC2"=>"121241", "DC3"=>"114212", "DC4"=>"124112", "NAK"=>"124211", 
        "SYN"=>"411212", "ETB"=>"421112", "CAN"=>"421211", "EM"=>"212141", "SUB"=>"214121", 
        "ESC"=>"412121", "FS"=>"111143", "GS"=>"111341", "RS"=>"131141", "US"=>"114113", 
        "FNC 3"=>"114311", "FNC 2"=>"411113", "SHIFT"=>"411311", "CODE C"=>"113141", 
        "CODE B"=>"114131", "FNC 4"=>"311141", "FNC 1"=>"411131", "Start A"=>"211412", 
        "Start B"=>"211214", "Start C"=>"211232", "Stop"=>"2331112"
    ];
    
    $code_keys = array_keys($code_array);
    $code_values = array_flip($code_keys);
    
    // Calculate the checksum and build the code string
    for ($i = 0; $i < strlen($text); $i++) {
        $activeKey = $text[$i];
        $code_string .= $code_array[$activeKey];
        $chksum += ($code_values[$activeKey] * ($i + 1));
    }
    
    // Add the checksum character
    $code_string .= $code_array[$code_keys[($chksum % 103)]];
    
    // Add start and stop codes
    return "211412" . $code_string . "2331112";
}

/**
 * Generates Code39 barcode pattern
 * 
 * @param string $text Text to encode
 * @return string The encoded pattern
 */
function generateCode39(string $text): string {
    $code_array = [
        "0"=>"111221211", "1"=>"211211112", "2"=>"112211112", "3"=>"212211111", "4"=>"111221112",
        "5"=>"211221111", "6"=>"112221111", "7"=>"111211212", "8"=>"211211211", "9"=>"112211211",
        "A"=>"211112112", "B"=>"112112112", "C"=>"212112111", "D"=>"111122112", "E"=>"211122111",
        "F"=>"112122111", "G"=>"111112212", "H"=>"211112211", "I"=>"112112211", "J"=>"111122211",
        "K"=>"211111122", "L"=>"112111122", "M"=>"212111121", "N"=>"111121122", "O"=>"211121121",
        "P"=>"112121121", "Q"=>"111111222", "R"=>"211111221", "S"=>"112111221", "T"=>"111121221",
        "U"=>"221111112", "V"=>"122111112", "W"=>"222111111", "X"=>"121121112", "Y"=>"221121111",
        "Z"=>"122121111", "-"=>"121111212", "."=>"221111211", " "=>"122111211", "$"=>"121212111",
        "/"=>"121211121", "+"=>"121112121", "%"=>"111212121", "*"=>"121121211"
    ];

    $code_string = "";
    $upper_text = strtoupper($text);

    // Build the code string
    for ($i = 0; $i < strlen($upper_text); $i++) {
        $char = $upper_text[$i];
        if (isset($code_array[$char])) {
            $code_string .= $code_array[$char] . "1";
        }
    }

    // Add start and stop codes (asterisk)
    return "1211212111" . $code_string . "121121211";
}

/**
 * Generates Code25 barcode pattern
 * 
 * @param string $text Text to encode (numeric only)
 * @return string The encoded pattern
 */
function generateCode25(string $text): string {
    $code_array1 = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];
    $code_array2 = [
        "3-1-1-1-3", "1-3-1-1-3", "3-3-1-1-1", "1-1-3-1-3", "3-1-3-1-1",
        "1-3-3-1-1", "1-1-1-3-3", "3-1-1-3-1", "1-3-1-3-1", "1-1-3-3-1"
    ];

    $code_string = "";
    $temp = [];

    // Map each digit to its encoding
    for ($i = 0; $i < strlen($text); $i++) {
        $digit = $text[$i];
        $key = array_search($digit, $code_array1);
        if ($key !== false) {
            $temp[$i + 1] = $code_array2[$key];
        }
    }

    // Interleave the encodings
    for ($i = 1; $i <= strlen($text); $i += 2) {
        if (isset($temp[$i]) && isset($temp[$i + 1])) {
            $temp1 = explode("-", $temp[$i]);
            $temp2 = explode("-", $temp[$i + 1]);
            
            for ($j = 0; $j < count($temp1); $j++) {
                $code_string .= $temp1[$j] . $temp2[$j];
            }
        }
    }

    // Add start and stop codes
    return "1111" . $code_string . "311";
}

/**
 * Generates Codabar barcode pattern
 * 
 * @param string $text Text to encode
 * @return string The encoded pattern
 */
function generateCodabar(string $text): string {
    $code_array1 = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "-", "$", ":", "/", ".", "+", "A", "B", "C", "D"];
    $code_array2 = [
        "1111221", "1112112", "2211111", "1121121", "2111121", "1211112", "1211211", "1221111", 
        "2112111", "1111122", "1112211", "1122111", "2111212", "2121112", "2121211", "1121212", 
        "1122121", "1212112", "1112122", "1112221"
    ];

    $code_string = "";
    $upper_text = strtoupper($text);

    // Build the code string
    for ($i = 0; $i < strlen($upper_text); $i++) {
        $char = $upper_text[$i];
        $key = array_search($char, $code_array1);
        if ($key !== false) {
            $code_string .= $code_array2[$key] . "1";
        }
    }

    // Add start and stop codes
    return "11221211" . $code_string . "1122121";
}

/**
 * Creates and outputs the barcode image
 * 
 * @param string $code_string The encoded barcode pattern
 * @param string $filepath Path to save the image (if empty, outputs to browser)
 * @param string $text Text to print below the barcode
 * @param int $size Height of the barcode (for horizontal) or width (for vertical)
 * @param string $orientation 'horizontal' or 'vertical'
 * @param bool $print Whether to print the text below the barcode
 * @param float $sizeFactor Scaling factor for the barcode
 * @return void
 */
function outputBarcodeImage(
    string $code_string,
    string $filepath,
    string $text,
    int $size,
    string $orientation,
    bool $print,
    float $sizeFactor
): void {
    // Calculate dimensions
    $code_length = 20;
    $text_height = $print ? 30 : 0;
    
    // Calculate total code length
    for ($i = 0; $i < strlen($code_string); $i++) {
        $code_length += (int)$code_string[$i];
    }

    // Set image dimensions based on orientation
    if (strtolower($orientation) === "horizontal") {
        $img_width = (int)($code_length * $sizeFactor);
        $img_height = $size;
    } else {
        $img_width = $size;
        $img_height = (int)($code_length * $sizeFactor);
    }

    // Create the image
    $image = imagecreate($img_width, $img_height + $text_height);
    if (!$image) {
        throw new Exception("Failed to create barcode image");
    }
    
    // Allocate colors
    $black = imagecolorallocate($image, 0, 0, 0);
    $white = imagecolorallocate($image, 255, 255, 255);

    // Fill background
    imagefill($image, 0, 0, $white);
    
    // Add text if requested
    if ($print) {
        imagestring($image, 5, 31, $img_height, $text, $black);
    }

    // Draw the barcode
    $location = 10;
    for ($position = 1; $position <= strlen($code_string); $position++) {
        $cur_size = $location + (int)$code_string[$position - 1];
        $color = ($position % 2 === 0) ? $white : $black;
        
        if (strtolower($orientation) === "horizontal") {
            imagefilledrectangle(
                $image,
                (int)($location * $sizeFactor),
                0,
                (int)($cur_size * $sizeFactor),
                $img_height,
                $color
            );
        } else {
            imagefilledrectangle(
                $image,
                0,
                (int)($location * $sizeFactor),
                $img_width,
                (int)($cur_size * $sizeFactor),
                $color
            );
        }
        
        $location = $cur_size;
    }
    
    // Output or save the image
    if (empty($filepath)) {
        // Output to browser
        header('Content-type: image/png');
        imagepng($image);
    } else {
        // Save to file
        imagepng($image, $filepath);
    }
    
    // Free memory
    imagedestroy($image);
}
?>
