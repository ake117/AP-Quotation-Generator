<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>{$email_title}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>

            <!-- Table goes in the document BODY -->
            <table style="font-family: verdana, arial, sans-serif;font-size: 12px;color: #333333;border-width: 1px;border-color: #E1E1E1;border-collapse: collapse;">
                    <tbody>
                    <tr>
                            <td style = "background-color:#ffffff;border-width: 1px;border-color: #E1E1E1;min-width:100px;border-width: 1px;padding: 8px;border-style: solid;border-color: #F1F1F1;"> ชื่อ</td>
                            <td style = "background-color:#ffffff;border-width: 1px;border-color: #E1E1E1;min-width:250px;border-width: 1px;padding: 8px;border-style: solid;border-color: #F1F1F1;">{$name}</td>
                    </tr>
                    <tr>
                            <td style = "background-color:#F1F1F1;border-width: 1px;border-color: #E1E1E1;min-width:100px;border-width: 1px;padding: 8px;border-style: solid;border-color: #F1F1F1;">อีเมล</td>
                            <td style = "background-color:#F1F1F1;border-width: 1px;border-color: #E1E1E1;min-width:250px;border-width: 1px;padding: 8px;border-style: solid;border-color: #F1F1F1;">{$email}</td>
                    </tr>
                    <tr>
                            <td style = "background-color:#ffffff;border-width: 1px;border-color: #E1E1E1;min-width:100px;border-width: 1px;padding: 8px;border-style: solid;border-color: #F1F1F1;">เรื่อง</td>
                            <td style = "background-color:#ffffff;border-width: 1px;border-color: #E1E1E1;min-width:250px;border-width: 1px;padding: 8px;border-style: solid;border-color: #F1F1F1;">{$title}</td>
                    </tr>
                    <tr>
                            <td style = "background-color:#F1F1F1;border-width: 1px;border-color: #E1E1E1;min-width:100px;border-width: 1px;padding: 8px;border-style: solid;border-color: #F1F1F1;">ข้อความ</td>
                            <td style = "background-color:#F1F1F1;border-width: 1px;border-color: #E1E1E1;min-width:250px;border-width: 1px;padding: 8px;border-style: solid;border-color: #F1F1F1;">{$message}</td>
                    </tr>

                    </tbody>
            </table>

    </body>
</html>
