<h1> Личная страница </h1>
<p>Здравствуйте, <?=$_SESSION['first_name'].' '.$_SESSION['second_name']?>! Вы находитесь в личном кабинете. Здесь вы можете:<br/><br/> 
</p>
<table id = 'myaccount'>
  <tr>
    <td><a href = '/edit_user'><img src="/images/userdata.jpg"/></a></td>
    <td><a href = '/current_order'><img src="/images/currentorder.jpg"/></a></td>
    <td><a href = '/order_hist'><img src="/images/history.jpg"/></a></td>
  </tr>
  <tr>
    <td><a href = '/edit_user'>Изменить личные данные</a></td>
    <td><a href = '/current_order'>Посмотреть или изменить текущий заказ</a></td>
    <td><a href = '/order_hist'>Посмотреть историю заказов</a></td>
  </tr>
</table>