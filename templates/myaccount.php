<h1> ������ �������� </h1>
<p>������������, <?=$_SESSION['first_name'].' '.$_SESSION['second_name']?>! �� ���������� � ������ ��������. ����� �� ������:<br/><br/> 
</p>
<table id = 'myaccount'>
  <tr>
    <td><a href = '/edit_user'><img src="/images/userdata.jpg"/></a></td>
    <td><a href = '/current_order'><img src="/images/currentorder.jpg"/></a></td>
    <td><a href = '/order_hist'><img src="/images/history.jpg"/></a></td>
  </tr>
  <tr>
    <td><a href = '/edit_user'>�������� ������ ������</a></td>
    <td><a href = '/current_order'>���������� ��� �������� ������� �����</a></td>
    <td><a href = '/order_hist'>���������� ������� �������</a></td>
  </tr>
</table>