# Supplier Product List Processor


## Requirements
- Your system must have installed php & accessible from terminal/command prompt
- Your PHP version must be >= 7+ 


## Installation Instructions
- Download or clone the project to your system


## Run Application
- copy your csv file to your script directory
- Open terminal/command prompt in your script directory or browse script directory by command line
- Run `parser.php --input=yourInputFileName --output=yourOutputFileName`
- You will see your outfile file on script directory



## Example 
`parser.php --input products.csv --output=combination_count.csv`



## Note
- If your input filename or output filename not provide you will see an error message.
- If your input file not exists on script directory you will see an error message.
- If your input file not readable you will see an error message.
- If your input or output file not supported you will see an error message.
- If your output file write failed you will see an error message.
- If your output file write successfully you will see a success message.
- New input and/or output file format can be implemented easily if you needed. like (json, xml etc)





