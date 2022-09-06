<?php


$args = getopt('', ['file:', 'unique-combinations:']);


try {
    //Check all requrie arguments presents
    if(!array_key_exists('file', $args)){
        throw new Exception('File name is required.');
    }else if(!array_key_exists('unique-combinations', $args)){
        throw new Exception('unique-combinations file name is required.');
    }

    $input_filename = $args['file'];
    $output_filename = $args['unique-combinations'];


    // Check the input file exists
    if(!file_exists($input_filename)){
        throw new Exception("The file $input_filename does not exist");
    }

    //Check the input file is writable
    if(!is_readable($input_filename)){
        throw new Exception("The file $input_filename can't readble");
    }

    // Check the output file writable
    if(!is_writable($output_filename)){
        throw new Exception("The file $output_filename can't writable");
    }


    // Get file extension from file name
    $input_file_extension = pathinfo($input_filename, PATHINFO_EXTENSION);
    $output_file_extension = pathinfo($output_filename, PATHINFO_EXTENSION);

    $data = [];
    if($input_file_extension == 'csv') {
        $data = processCSVFile($input_filename);
    }

    if($output_file_extension == 'csv') {
        writeCSVFile($data, $output_filename);
    }

} catch (Exception $e) {
    echo $e->getMessage().PHP_EOL;
}

/**
 * Read CSV file from current directory & process
 *
 * @param string $input_filename
 * @return array
 */
function processCSVFile(string $input_filename): array {
    $file = fopen($input_filename,"r");
    $header =  fgetcsv($file);
    $data = [];
    $data[] = [...$header, 'count'];
    while(($row = fgetcsv($file)) != false){
        $key = preg_replace('/\s/', '_', join('_',$row));
        if(array_key_exists($key, $data)){
            $data[$key]['count'] =  $data[$key]['count'] +1;
        }else {
            $data[$key] = $row;
            $data[$key]['count'] = 1;
        }
    }
    fclose($file);

    return $data;
}




/**
 * Write csv file from array
 *
 * @param array $data
 * @param string $fileName
 * @return void
 */
function writeCSVFile(array $data, string $fileName) {
    $file = fopen($fileName, 'w');
    foreach($data as $key => $value){
        fputcsv($file, $value);
    }
    fclose($file);

    echo "The file $fileName write successfully!".PHP_EOL;
}
