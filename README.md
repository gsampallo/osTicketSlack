# osTicketSlack
Genera un mensaje a Slack, con los tickets que se abren en el sistema.

El archivo class.ticket.php se debe copiar en la carpeta tickets\include. Esta sobre la version 1.8 de osticket.

conectar.php Contiene los datos de conexion a la base de datos.

Dentro del archivo slack.php hay que tener en cuenta dos parametros: 
$URL Hay que editarlo para que tenga la url de osticket
SLACK_WEBHOOK Setear con el webhook correspondiente al canal de Slack

Los mensajes llegan con el nombre de la persona que genero el ticket; se puede setear el icono modificandolo en el mensaje (editar la linea 43 :ghost:) o desde la generación del webhook en Slack.

Se tiene que crear esta tabla dentro de la base de los tickets:
CREATE TABLE `ticket_slack` (
  `ticket_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `enviado` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ticket_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

Finalmente hay que programar una tarea, para que cada x tiempo ejecute slack.php .


