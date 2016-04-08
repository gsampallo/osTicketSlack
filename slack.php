<?php
  require("conectar.php");
  $bd =  mysql_connect($bd_host,$bd_usuario,$bd_password);  
  mysql_query("SET NAMES 'utf8'");
  mysql_select_db($bd_base,$bd);

  //WebHook de Slack
  define( "SLACK_WEBHOOK", "https://hooks.slack.com/services/T03837D7B/B0YPHQSJH/dlkfjsdlkj" );

  //URL a la web de osticket
  $URL = "http://webdeTickets/scp";
  
  $sql = "SELECT
            ticket_slack.ticket_id,
            ticket_slack.id,
            ost_department.dept_name,
            ost_user.`name`,
            ost_ticket_thread.body,
            ost_ticket__cdata.`subject`
          FROM ticket_slack
            INNER JOIN ost_ticket ON ticket_slack.id = ost_ticket.ticket_id
            INNER JOIN ost_department ON ost_ticket.dept_id = ost_department.dept_id
            INNER JOIN ost_user ON ost_ticket.user_id = ost_user.id
            INNER JOIN ost_ticket_thread ON ticket_slack.id = ost_ticket_thread.ticket_id
            INNER JOIN ost_ticket__cdata ON ticket_slack.id = ost_ticket__cdata.ticket_id
            WHERE ticket_slack.enviado = 0";

  $rs = mysql_query($sql);
  while($row=mysql_fetch_array($rs)) {

  	$ticketID = $row["ticket_id"];
    $id = $row["id"];
    //echo $ticketID."<br>";
    $messageText = "*<http://$URL/scp/tickets.php?id=$id|#$ticketID>:* _".
    $row["dept_name"]."_ ".$row["subject"]." \n".$row["body"];

    $usuario = $row["name"];
    $message = array(
        "payload" =>
            json_encode(array(
                "channel" => "#general",
                "username" => $usuario,
                "icon_emoji" => ":ghost:",
                "text" => $messageText,
            ))
    );

    //echo $messageText;

    //print($message);

    $ch = curl_init( SLACK_WEBHOOK );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch, CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $message );
    curl_exec( $ch );
    curl_close( $ch );

    $update = "UPDATE `ticket_slack` SET `enviado`='1' WHERE (`ticket_id`='$ticketID')";
    $rs1 = mysql_query($update);

}

?>