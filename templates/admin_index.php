<h1>�������</h1>
<p>�� ���������� � ������ ����������������� �����. ��������� �� ������������ ������</p>
<? if (isset ($params[params])) {
	foreach ($params[params] as $param) { ?>
	<h2><a href = '/admin/<?=$param['url']?>'><?=$param['name']?></a></h2>	
<?	}
}?>
<h1>�������</h1>
<p>�������������� ������� �����������������</p>
<?/*
<a href = '/admin/adddoc/' >������� ����� ������</a> <br/>
<a href = "/admin/manage_types"> ���������� ������ ����������</a><br/>
</p>*/?>
<h2><a href = "/admin/manage_orders"> ���������� �������� ��������</a></h2>


