<?php
/**
 * Sample controller for JFileUploader
 * @see \html\jobject\FileUploader
 */
namespace cons;
require_once(DOC_ROOT . '/plugins/jquery/fileuploader/FileUploader.php');
use FileUploader;

class ControllerFileUploader extends Controller {

  public function __construct() {
    parent::__construct();
  }

  public function ajaxUpload() {
    // initialize the FileUploader
    $FileUploader = new FileUploader('files', [
      // limit of files {null, Number}
      // also with the appended files
      // if null - has no limits
      // example: 3
      'limit'       => null,

      // file's maximal size in MB {null, Number}
      // also with the appended files
      // if null - has no limits
      // example: 2
      'maxSize'     => null,

      // each file's maximal size in MB {null, Number}
      // if null - has no limits
      // example: 2
      'fileMaxSize' => null,

      // allowed extensions or file types {null, Array}
      // if null - has no limits
      // example: ['jpg', 'jpeg', 'png', 'audio/mp3', 'text/plain']
      'extensions'  => null,

      // check if file input exists ($_FILES[ file_input_name ]) {Boolean}
      // check if files were choosed (minimum 1 file should be choosed)
      'required'    => false,

      // upload directory {String}
      // note that main directory is the directory where you are initializing the FileUploader class
      // example: '../uploads/'
      'uploadDir'   => 'uploads/',

      // file title {String, Array}
      // example: 'name' - original file name
      // example: 'auto' - random text from 12 letters
      // example: 'my_custom_filename' - custom file name
      // example: 'my_custom_filename_{random}' - my_custom_filename_(+ random text from 12 letters)
      // '{random} {file_name} {file_size} {timestamp} {date} {extension}' - variables that can be used to generate a new file name
      // example: array('auto', 24) - [0] is a string as in the examples above, [1] is the length of the random string
      'title'       => 'name',

      // replace the file with the same name? {Boolean}
      // if it will be false - will automatically generate a new file name with (1,2,3...) at the end of the file name
      'replace'     => false,

      // input with the listed files {Boolean, String}
      // this list is an input[type="hidden"]
      // this list is important to check which files shouldn't be uploaded or need to be removed
      // example: true
      // example: 'custom_listInput_name'
      'listInput'   => true,

      'files' => null]);

    // call to upload the files
    $upload = $FileUploader->upload();

    if ($upload['isSuccess']) {
      // get the uploaded files
      $files = $upload['files'];
    }
    if ($upload['hasWarnings']) {
      // get the warnings
      $warnings = $upload['warnings'];
    }

    // get listInput value
    $FileUploader->getListInput();

    // get removed list
    // give a String parameter (ex: 'file' or 'name' or 'data.url') to get a file by a custom input attribute. Default is 'file'
    // note that FileUploader will not remove your appended files that were removed on Front-End
    // to remove them, please use this example:
    // foreach($FileUploader->getRemovedFiles('file') as $key=>$value) {
    //     unlink('../uploads/' . $value['name']);
    // }
    $FileUploader->getRemovedFiles();

    // get the list of the files
    // without parameter it will return an array with appended and uploaded files
    // give a String parameter (ex: 'file' or 'name' or 'data.url') to generate a custom input list of the files
    // example: you can store the files in the MySQL using this function
    // $myFilesForSql = implode('|', $FileUploader->getFileList('name'));
    // $myFilesForSql = json_encode($FileUploader->getFileList('name'));
    $FileUploader->getFileList();

    // get the HTML generated input
    $FileUploader->generateInput();
  }

  public function ajaxRemove($para) {
  }
}