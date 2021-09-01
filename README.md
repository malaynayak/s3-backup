## Instructions

### Prerequisites

Make sure php installed on the server the program is executes.
This program needs the aws php sdk https://github.com/aws/aws-sdk-php. This package is included in the composer.json file. Run composer install to downlaod the necessary packages.

### Configurations

- Configuration related to AWS S3 needs to be pu in src/S3.php file.
- Update the below variables in backup.php
  $backup_location: Absolute path of the temporary local backup folder. Here the temporary backups will be stored prior moving to S3.
  $backup_log_path: Absolute path of the backup logs directory.
  $folders: An array of folder paths which need to be backed up on s3. The format should be `"backup name" => "folder absolute path"`
  
  
### Execution

Make sure the backup.sh file is executable and run it as `./backup.sh`. This script can also be scheduled as a cron job on a server.

### Output

The backup will be generated in compressed format: `backupname-2021-09-01-05:04:00.tar.gz`
For every backup log will be genarted in format; `back_up_log2021-09-01-05:04:00.txt`
