<?php

//Set time & memory limit for php script
set_time_limit(0);
ini_set('memory_limit', -1);


try {
    //Get arguments from CLI
    $args = getopt('', ['input:', 'output:']);

    //Check all requrie arguments presents
    if(!array_key_exists('input', $args)){
        throw new Exception('Input file name is required.');
    }else if(!array_key_exists('output', $args)){
        throw new Exception('Output file name is required.');
    }

    $input_filename = $args['input'];
    $output_filename = $args['output'];

    // Check the input file exists
    if(!file_exists($input_filename)){
        throw new Exception("The file $input_filename does not exist");
    }

    //Check the input file is writable
    if(!is_readable($input_filename)){
        throw new Exception("The file $input_filename can't readble");
    }

    // Get file extension from file name
    $input_file_extension = strtolower(pathinfo($input_filename, PATHINFO_EXTENSION));
    $output_file_extension = strtolower(pathinfo($output_filename, PATHINFO_EXTENSION));

    $supported_input_file_extensions = ['csv', 'tsv'];
    $supported_output_file_extensions = ['csv', 'tsv'];


    //Check supported input file extension
    if(!in_array($input_file_extension, $supported_input_file_extensions)){
        throw new Exception("The $input_file_extension format input file not support.");
    }

    //Check supported output file extension
    if(!in_array($output_file_extension, $supported_output_file_extensions)){
        throw new Exception("The $output_file_extension format output file not support.");
    }

    $data = [];
    if($input_file_extension == 'csv' || $input_file_extension == 'tsv') {
        $data = processCSVFile($input_filename);
    }

    if($output_file_extension == 'csv' || $output_file_extension == 'tsv') {
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
    $seperator =  strtolower(pathinfo($input_filename, PATHINFO_EXTENSION)) == 'csv' ? "," :"\t";


    $file = fopen($input_filename,"r");
    $header =  fgetcsv($file, 0, $seperator);
    $data = [];
    $data[] = [...$header, 'count'];
    while(($row = fgetcsv($file, 0, $seperator)) != false){
        $key = preg_replace('/\s/', '_', join('_',$row));
        if(array_key_exists($key, $data)){
            $data[$key]['count'] +=  1;
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
    $seperator =  strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) == 'csv' ? "," :"\t";
    $file = fopen($fileName, 'w');
    foreach($data as $key => $value){
        if(fputcsv($file, $value, $seperator) == FALSE){
            throw new Exception("The file $fileName can't readble");
        }
    }
    fclose($file);

    echo "The file $fileName write successfully!".PHP_EOL;
}
