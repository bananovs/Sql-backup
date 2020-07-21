<?
include 'config.php';

//----------------------------------------------------------------
//Interate over all databases
//----------------------------------------------------------------

foreach($db as $db_item)
{
    //Create SQL dump and gzip the dumped file
    
    $ip = !empty($db_item['db_host']) ? "-h {$db_item['db_host']}" : '';
    exec("mysqldump $ip -u {$db_item['db_user']} -p{$db_item['db_password']} --allow-keywords --add-drop-table --complete-insert --hex-blob --quote-names {$db_item['db_name']} > {$db_item['sql_file']}");
    //message_to_telegram('зоебись, сохранил -> '.$db_item['sql_file'],$token,$chatID);
    
    if (!file_exists($db_item['sql_file'])) {
        $text = "Ошибка! Файлик не создался " . $ftp_item['ftp_server'];
        message_to_telegram($text,$token,$chatID);
    }
    
    //----------------------------------------------------------------
	//Telegram transfer: Transfer sql dump to the chat
	//----------------------------------------------------------------
    
    
    sendFile($db_item['sql_file'],$token,$chatID);
    

	//----------------------------------------------------------------
	//FTP transfer: Transfer sql dump to the configured ftp servers
	//----------------------------------------------------------------

	if($ftp_count >= 0)
	{
		foreach($ftp as $ftp_item)
		{
			//Initiate connection
			if($ftp_item['ftps'])
				$connection_id = ftp_ssl_connect($ftp_item['ftp_server']);
			else
				$connection_id = ftp_connect($ftp_item['ftp_server']);

			if(!$connection_id)
                $text = "Error: Can't connect to {$ftp_item['ftp_server']}";
                message_to_telegram($text,$token,$chatID);


			//Login with user and password
			$login_result = ftp_login($connection_id, $ftp_item['ftp_user'], $ftp_item['ftp_password']);

			if(!$login_result)
                $text = "Error: Login wrong for {$ftp_item['ftp_server']}\n";
                message_to_telegram($text,$token,$chatID);


			//Passive mode?
			ftp_pasv($connection_id, $ftp_item['ftp_passive_mode']);
            
            // Показывает содержимое фтп папочки

            // $site = ftp_nlist($connection_id,""); 
            // $d = 5;
            // for ($i = 0; $i < $d; $i++) echo $site[$i]."<br>"; 

			//Upload file to ftp
			if (!ftp_put($connection_id, $db_item['sql_file'], $db_item['sql_file'], FTP_BINARY))
			{
                $text = "Error: While uploading {$db_item['sql_file']}.gz to {$ftp_item['ftp_server']}.\n";
                message_to_telegram($text,$token,$chatID);
			}

            //Close ftp connection
            $text = "Файлик {$db_item['sql_file']} загрузил на {$ftp_item['ftp_server']}.\n";
            message_to_telegram($text,$token,$chatID);
            ftp_close($connection_id);
            
		}
	}

	//Delete original *.sql file
	if(file_exists($db_item['sql_file'])) {
        unlink($db_item['sql_file']);
    }
		
}


function message_to_telegram($text,$token,$chatID) {
    $ch = curl_init();
    curl_setopt_array(
        $ch,
        array(
            CURLOPT_URL => 'https://api.telegram.org/bot' . $token . '/sendMessage',
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POSTFIELDS => array(
                'chat_id' => $chatID,
                'text' => $text,
            ),
        )
    );
    curl_exec($ch);
}

function sendFile($file,$token,$chatID) {
    $filename = realpath($file);
    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    $mimetype = $finfo->file($filename);
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.telegram.org/bot'.$token.'/sendDocument?caption=БД на сегодня&chat_id='.$chatID,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: multipart/form-data'
        ],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            'document' => curl_file_create($filename, $mimetype, basename($filename))
        ]
    ]);
    
    $data = curl_exec($curl);
    curl_close($curl);
    
  
}



 
?>