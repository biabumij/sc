<!DOCTYPE html>
<html>
<head>
	<title>WAMTI EMAIL</title>
	<style type="text/css">
		body {
		    font-family: 'Open Sans',sans-serif;
		    font-size: 14px;
		    line-height: 1.42857143;
		    color: #333;
		}
		p{
			margin: 5px 0px;
		}
		.table-bordered {
		    border: 1px solid #ddd;
		}
		table, table td {
		    padding: 0;
		    border: none;
		    border-collapse: collapse;
		}
		table th{
			text-align: left;
		}
		.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
		    border: 1px solid #ddd;
		    padding: 5px 10px;
		}
	</style>
</head>
<body>

	<h1>Thank you for contacting us</h1>
	<h2>Your Message can we process as soon as possible</h2>


	<h3>WAMTI</h3>
	<address>
		<?php echo $this->m_themes->GetThemes('site_address1');?>
	</address>
	<p>Telepon : <a href="tel://<?php echo $this->m_themes->GetThemes('site_phone1');?>"><?php echo $this->m_themes->GetThemes('site_phone1');?></a></p>
	<p>Email : <a href="mailto://<?php echo $this->m_themes->GetThemes('site_email');?>"><?php echo $this->m_themes->GetThemes('site_email');?></a></p>
	
</body>
</html>