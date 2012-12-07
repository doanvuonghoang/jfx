<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>

  <meta content="text/html; charset=UTF-8" http-equiv="content-type">
  <title>C&#7845;u hình ứng dụng JF</title>


</head>
<body>

<h1>C&#7845;u hình JF</h1>

C&#7845;u hình k&#7871;t n&#7889;i c&#417; s&#7903; d&#7919; li&#7879;u
<form method="post" action="<?php echo $request->createURL(null, '__p=do_config') ?>">
		<table style="text-align: left;" border="0" cellpadding="2"
			cellspacing="2">

			<tbody>

				<tr>
					<td>Loại kết nối</td>
					<td><select name="type"><option value="mysql">MySQL</option>
					</select>
					</td>
				</tr>
				<tr>

					<td>Máy ch&#7911;</td>

					<td><input size="25" name="server" value="127.0.0.1">
						<br> <span style="font-style: italic;">(&#273;i&#7873;n
							thông tin &#273;&#7883;a ch&#7881; máy ch&#7911; ph&#7909;c
							v&#7909; c&#417; s&#7903; d&#7919; li&#7879;u, ví d&#7909;:
							127.0.0.1)</span>
					</td>

				</tr>

				<tr>
					<td>Cổng kết nối</td>
					<td><input name="port" size="10" value="3306"></td>
				</tr>
				<tr>

					<td>Tên truy c&#7853;p</td>

					<td><input size="10" name="username" value="root">
					</td>

				</tr>

				<tr>

					<td>M&#7853;t kh&#7849;u</td>

					<td><input size="10" name="password" type="password">
					</td>

				</tr>

				<tr>

					<td>CSDL</td>

					<td><input size="10" name="database">
					</td>

				</tr>

				<tr>
					<td></td>
					<td><input type="submit" value="Tiếp theo">
					</td>
				</tr>
			</tbody>
		</table>

	</form>

</body>
</html>
