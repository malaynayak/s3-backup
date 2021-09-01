<?php

require_once('src/s3.php');

// Location of the temporary local backup folder.
$backup_location = '/home/path-to-temp/backup';

// Location of the backup logs location.
$backup_log_path = '/home/path-to-temp/logs';

// A unique datestamp.
$date_stamp = date("Y-m-d-H:i:s");

/**
 * List of folder for backup.
 */
$folders = [
  'codebase1' => '/home/path-to/codebase1',
  'codebase2' =>'/home/path-to/codebase2',
  'codebase3' => '/home/path-to/codebase3'
];

// Initilise the backup logs.
$log_data = [
  "Backup Log for: $date_stamp",
  "====================================================",
  "\n"
];

// Iterate through the directories and take backup.
foreach ($folders as $name => $folder_path) {
  $path = realpath($folder_path);
  // The the directory is valid.
  if ($path !== false AND is_dir($path)) {
    $backup_file = $backup_location . "/" . $name. "-" . $date_stamp.".tar.gz";

    // Archive the directory for backup.
    `tar -czvf "${backup_file}" -C / "$folder_path"`;

    // Start backup to S3.
    try {
      if(upload_to_s3($backup_file)){
        $log_data[] = "Backup sucessfull for: $folder_path as $backup_file";
      }
    } catch (Exception $e){
      $log_data[] = "Backup errored due to error: ". $e->getMessage();
    }
    // Remove the local backup after its is moved to S3.
    `rm ${backup_file}`;
  } else {
    $log_data[] = "Backup skipped for invalid directory: ${folder_path}";
  }
}

// Generate the appropriate log after the migration.
create_log($log_data, "${backup_log_path}/back_up_log${date_stamp}.txt");

/**
 * Generates logs after the backup is complete.
 *
 * @param array $logs the logs array.
 * @param array $file_name the file where the log to be added.
 */
function create_log($logs, $file_name) {
  $handler = fopen($file_name,'a+');
  //write the info into the file
  fwrite($handler,implode($logs, "\n"));
  //close handler
  fclose($handler);
}
