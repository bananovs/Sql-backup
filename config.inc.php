<?php
//----------------------------------------------------------------
//Set defaults
//----------------------------------------------------------------

set_time_limit(0);
$db_count 						= -1;
$ftp_count 						= -1;
$token = ''; // telegram bot api token
$chatID = ''; // id chat group -543959345


// cron
// 0 1 * * * php /var/database_backups/database_backup.php

//----------------------------------------------------------------
//DB credentials
//----------------------------------------------------------------
//Configure credentials of one or more database to backup

//DB 1
$db_count++;
$db[$db_count]['db_host'] 		= "localhost";
$db[$db_count]['db_user'] 		= "root";
$db[$db_count]['db_password'] 	= "root";
$db[$db_count]['db_name'] 		= "";
$db[$db_count]['sql_file'] 		= "dump_".date('Y-m-d')."_{$db[$db_count]['db_name']}.sql";


//DB 2
/*
$db_count++;
$db[$db_count]['db_host'] 		= "";
$db[$db_count]['db_user'] 		= "";
$db[$db_count]['db_password'] 	= "";
$db[$db_count]['db_name'] 		= "";
$db[$db_count]['sql_file'] 		= "dump_".date('Y-m-d')."_{$db[$db_count]['db_name']}.sql";
*/


//----------------------------------------------------------------
//FTP credentials
//----------------------------------------------------------------
//Configure credentials of one or more ftp server to transfer the backup

//FTP 1
$ftp_count++;
$ftp[$ftp_count]['ftps'] 				= false;
$ftp[$ftp_count]['ftp_server'] 			= "";
$ftp[$ftp_count]['ftp_user'] 			= "";
$ftp[$ftp_count]['ftp_password'] 		= "";
$ftp[$ftp_count]['ftp_passive_mode'] 	= true;


//FTP 2
/*
$ftp_count++;
$ftp[$ftp_count]['ftps'] 				= false;
$ftp[$ftp_count]['ftp_server'] 			= "";
$ftp[$ftp_count]['ftp_user'] 			= "";
$ftp[$ftp_count]['ftp_password'] 		= "";
$ftp[$ftp_count]['ftp_passive_mode'] 	= true;
*/
